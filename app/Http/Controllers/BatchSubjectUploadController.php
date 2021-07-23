<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AcademicLevel;
use App\Program;
use App\Setting;
use App\Subject;
use App\Department;
use App\User;
use App\Series;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class BatchSubjectUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.batch_subject_upload');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show th batch subject upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBatchSubjectUploadPage()
    {

        $subject_list = '';
        return view('subject_management.batch_subject_upload', compact('subject_list'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function BatchSubjectUpload(Request $request)
    {

        $this->validate($request, ['class-code' => 'required',
            'program-code' => 'required',
            'subject-csv-file' => 'required'
        ]);
        $sectionCode = Auth::user()->sections_section_code;

        if ($request->file('subject-csv-file')->isValid()) {
            try {
                $file = $request->file('subject-csv-file');
                if ($file->getClientOriginalExtension() != 'csv') {
                    $csv_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/batch_subject_upload.csv_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $csv_validity_alert]);
                }

                $classCode = $request->get('class-code');
                $class = AcademicLevel::getClassByCode($classCode);
                $programCode = $request->get('program-code');
                $program_name = Program::getCycleNameByCode($programCode);
                if ($class->programs_program_code != $programCode) {
                    $mismatch_alert = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/batch_subject_upload.program_code_mismatch', ['class' => $class->class_name, 'program' => $program_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $mismatch_alert]);
                }

                $academicYear = Setting::getAcademicYear();

                $subject_list_file_name = '' . '.' . $file->getClientOriginalExtension();
                $request->file('subject-csv-file')->move(public_path('subject_list'), $subject_list_file_name);
                $subject_list_file_path = 'subject_list/' . $subject_list_file_name;
                $subject_list_log_file_path = 'subject_list/' . 'log_' . $subject_list_file_name;



                $upload_status = self::performBatchSubjectUploadFromCSV($subject_list_file_path, $subject_list_log_file_path, $academicYear, $class, $sectionCode);
                if ($upload_status == 0) {
                    $file_open_error = Setting::getAlertFailure(trans('student_management/batch_student_upload.file_open_error'));
                    return redirect()->back()->with(['status' => $file_open_error]);

                }

                $subjects = Subject::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.classes_class_code'), $classCode)->get();

                $subject_list = View::make('subject_management.batch_subject_list', compact('subjects', 'class'));

                return view('subject_management.batch_subject_upload', compact('subject_list'));

            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                $csv_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('subject_management/batch_subject_upload.csv_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                return redirect()->back()->with(['status' => $csv_validity_alert]);

            }

        }
    }

    /**
     * download the list of subjects that could not successfully be uploaded
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */

    public function downloadSubjectList()
    {
        try {
            $subject_list_log_file_path = session('batch_subject_list_path');
            return response()->download($subject_list_log_file_path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * @param $subject_list_file_path
     * @param $subject_list_log_file_path
     * @param $academicYear
     * @param $class
     * @param $sectionCode
     * @return int
     */
    public static function performBatchSubjectUploadFromCSV($subject_list_file_path, $subject_list_log_file_path, $academicYear, $class, $sectionCode)
    {

        $userId= Auth::user()->user_id;
        $departmentId = Auth::user()->departments_department_id;
        $seriesCodes = Series::where(trans('database/table.sections_section_code'),Auth::user()->sections_section_code)->get();

        try{
            $errorCounter = 0;
            $successCounter = 0;


            $subjectList = collect([]);
            $seriesSubjectList = collect([]);
            $resource = collect([]);
            $subject_list_file = fopen(public_path($subject_list_file_path), 'r');
            $subject_list_log_file = fopen(public_path($subject_list_log_file_path), 'a+');

            if ($subject_list_file == false || $subject_list_log_file == false) {
                return 0;
            }
            $subjects = Subject::where(trans('database/table.sections_section_code'), $sectionCode)->where(trans('database/table.programs_program_code'), $class->programs_program_code)->get();

            $trash = fgetcsv($subject_list_file, 1000);
            while (!feof($subject_list_file)) {
                $row = fgetcsv($subject_list_file, 1000);
                //check whether a subject data already exist in the database or in the temporal collection
                if (self::checkDuplicateData($resource, $row)) {
                    $row[] = trans('subject_management/batch_subject_upload.data_duplicate_within');
                    fputcsv($subject_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::checkSubjectExistance($subjects, $row)) {
                    $row[] = trans('subject_management/batch_subject_upload.data_exist');
                    fputcsv($subject_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::isEmptyRow($row)){

                } else {
                    $resource = $resource->push([
                        trans('database/table.subject_code') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]),
                        trans('database/table.subject_title') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[1]),
                        trans('database/table.coefficient') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[2]),
                        trans('database/table.state') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[3]),
                        trans('database/table.subject_weight') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[4]),
                        trans('database/table.academic_year') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[5]),
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.programs_program_code') => $class->programs_program_code,
                        trans('database/table.sections_section_code') => $sectionCode,
                        trans('database/table.users_user_id') => $userId,
                        trans('database/table.departments_department_id') => $departmentId
                    ]);
                    $subjectList = $subjectList->push([
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.subjects_subject_code') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]),
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.sections_section_code') => $sectionCode,
                        trans('database/table.users_user_id') => $userId
                    ]);
                    if (!empty($row[6]) && ($class->programs_program_code == "al" || $class->programs_program_code == "alt" || $class->programs_program_code == "alc" ) && $seriesCodes->where(trans('database/table.series_code'),$row[6])->isNotEmpty()) {
                        $seriesSubjectList = $seriesSubjectList->push([
                            trans('database/table.subjects_subject_code') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]),
                            trans('database/table.classes_class_code') => $class->class_code,
                            trans('database/table.series_series_code') => $row[6],
                            trans('database/table.academic_year') => $academicYear,
                            trans('database/table.sections_section_code') => $sectionCode,
                            trans('database/table.users_user_id') => $userId
                        ]);
                    }

                    $successCounter ++;
                }
            }
            fclose($subject_list_file);
            fclose($subject_list_log_file);

            //File::delete(public_path($subject_list_file_path));

            Subject::batchSubjectSave($resource->toArray());
            AcademicLevel::batchSubjectLevelSave($subjectList->toArray());
            Series::batchSubjectSeriesSave($seriesSubjectList->toArray());

            session(['batch_subject_list_path' => public_path($subject_list_log_file_path)]);
            $data['subjects'] = $resource->toArray();
            $data['class'] = $class;
            $data['success_counter'] = $successCounter;
            $data['error_counter'] = $errorCounter;
            return $data;
        }
        catch (Illuminate\Filesystem\FileNotFoundException $e) {
            return 0;
        }




    }


    /**
     * @param $resources
     * @param $row
     * @return bool
     */
    public static function checkDuplicateData($resources, $row)
    {
        $sentinel = 0;
        foreach ($resources as $resource) {
            if ( ($resource['subject_code'] == $row[0] &&  $resource['series_series_code'] == $row[6]) ) {
                $sentinel++;
            }
        }
        if ($sentinel == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $subjects
     * @param $row
     * @return bool
     */
    public static function checkSubjectExistance($subjects, $row)
    {
        $sentinel = 0;
        foreach ($subjects as $subject) {
            if ($subject->subject_code == $row[0] && $subject->series_series_code == $row[6]) {
                $sentinel++;
            }
        }
        if ($sentinel == 0) {
            return false;
        }

        return true;
    }

    public static function isEmptyRow($row)
    {
        if (empty($row[0])) {
            return true;
        }
        return false;
    }


}

