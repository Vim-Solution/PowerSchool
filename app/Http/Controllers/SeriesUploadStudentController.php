<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Program;
use App\Series;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SeriesUploadStudentController extends Controller
{

    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.series_data_upload');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show th batch student series infos upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSeriesDataUploadPage()
    {

        $student_series_list = '';
        return view('subject_management.series_infos_upload', compact('student_series_list'));
    }

    public function seriesDataUpload(Request $request)
    {

        $this->validate($request, ['class-code' => 'required',
            'program-code' => 'required',
            'student_series-csv-file' => 'required'
        ]);
        $sectionCode = Auth::user()->sections_section_code;

        if ($request->file('student_series-csv-file')->isValid()) {
            try {
                $file = $request->file('student_series-csv-file');
                if ($file->getClientOriginalExtension() != 'csv') {
                    $csv_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/series_data_upload.csv_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $csv_validity_alert]);
                }

                $classCode = $request->get('class-code');
                $class = AcademicLevel::getClassByCode($classCode);
                $programCode = $request->get('program-code');
                $program_name = Program::getCycleNameByCode($programCode);
                $academicYear = Setting::getAcademicYear();

                $student_series_list_file_name = '' . '.' . $file->getClientOriginalExtension();
                $request->file('student_series-csv-file')->move(public_path('student_series_list'), $student_series_list_file_name);
                $student_series_list_file_path = 'student_series_list/' . $student_series_list_file_name;
                $student_series_list_log_file_path = 'student_series_list/' . 'log_' . $student_series_list_file_name;
                $upload_status = self::performStudentSeriesDataUploadFromCSV($student_series_list_file_path, $student_series_list_log_file_path, $academicYear, $class, $sectionCode);
                $series_students = DB::table(trans('database/table.series_has_students'))
                    ->where(trans('database/table.sections_section_code'), $sectionCode)
                    ->where(trans('database/table.academic_year'), $academicYear)
                    ->get();


                $upload_status = self::performStudentSeriesDataUploadFromCSV($student_series_list_file_path, $student_series_list_log_file_path, $academicYear, $class, $sectionCode);

                $student_series_list = View::make('subject_management.student_series_list', compact('series_students', 'class'));

                return view('subject_management.series_infos_upload', compact('student_series_list'));

            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                $csv_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/series_data_upload.csv_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                return redirect()->back()->with(['status' => $csv_validity_alert]);

            }

        }

    }

    public function performStudentSeriesDataUploadFromCSV($student_series_list_file_path, $student_series_list_log_file_path, $academicYear, $class, $sectionCode)
    {
        $userId = Auth::user()->user_id;

        try {
            $errorCounter = 0;
            $successCounter = 0;
            $resource = collect([]);
            $student_series_list_file = fopen(public_path($student_series_list_file_path), 'r');
            $student_series_list_log_file = fopen(public_path($student_series_list_log_file_path), 'a+');

            if ($student_series_list_file == false || $student_series_list_log_file == false) {
                return 0;
            }
            $series_students = DB::table(trans('database/table.series_has_students'))
                ->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.sections_section_code'), $sectionCode)
                ->get();

            $trash = fgetcsv($student_series_list_file, 1000);
            while (!feof($student_series_list_file)) {
                $row = fgetcsv($student_series_list_file, 1000);
                //check whether a student series data already exist in the database or in the temporal collection
                if (self::checkDuplicateData($resource, $row)) {
                    $row[] = trans('subject_management/series_data_upload.data_duplicate_within');
                    fputcsv($student_series_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::checkSeriesStudentExistance($series_students, $row)) {
                    $row[] = trans('subject_management/series_data_upload.data_exist');
                    fputcsv($student_series_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::isEmptyRow($row)) {

                } else {
                    $resource = $resource->push([
                        trans('database/table.series_series_code') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[2]),
                        trans('database/table.matricule') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]),
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.sections_section_code') => $sectionCode,
                        trans('database/table.users_user_id') => $userId

                    ]);
                    $successCounter++;
                }
            }
            fclose($student_series_list_file);
            fclose($student_series_list_log_file);

            //File::delete(public_path($student_series_list_file_path));

            Series::batchStudentSeriesSave($resource->toArray());

            session(['batch_series_list_path' => public_path($student_series_list_log_file_path)]);
            $data['series_students'] = $resource->toArray();
            $data['class'] = $class;
            $data['success_counter'] = $successCounter;
            $data['error_counter'] = $errorCounter;
            return $data;
        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
            return 0;
        }

    }

    public function downloadStudentSeriesList()
    {
        try {
            $student_series_list_file_path = session('batch_series_list_path');
            return response()->download($student_series_list_file_path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    public static function checkDuplicateData($resources, $row)
    {
        $sentinel = 0;
        foreach ($resources as $resource) {
            if ($resource['matricule'] == $row[0] && $resource['series_series_code'] == $row[2]) {
                $sentinel++;
            }
        }
        if ($sentinel == 0) {
            return false;
        }

        return true;
    }

    public static function checkSeriesStudentExistance($studentseries, $row)
    {
        $sentinel = 0;
        foreach ($studentseries as $studentserie) {
            if ($studentserie->matricule == $row[0] && $studentserie->series_series_code == $row[2]) {
                $sentinel++;
            }
        }
        if ($sentinel == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $row
     * @return bool
     */
    public static function isEmptyRow($row)
    {
        if (empty($row[0]) || empty($row[2])) {
            return true;
        }
        return false;
    }

}
