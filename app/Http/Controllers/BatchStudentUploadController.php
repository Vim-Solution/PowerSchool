<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Program;
use App\Series;
use App\Setting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

define('MATRICULE_END_NUMBER_LENGTH', 4);

class BatchStudentUploadController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.batch_student_upload');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show th batch student upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBatchStudentUploadPage()
    {
        $student_list = '';
        return view('student_management.batch_student_upload', compact('student_list'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function batchStudentUpload(Request $request)
    {

        $this->validate($request, ['class-code' => 'required',
            'program-code' => 'required',
            'student-csv-file' => 'required'
        ]);

        $sectionCode = Auth::user()->sections_section_code;

        if ($request->file('student-csv-file')->isValid()) {
            try {
                $file = $request->file('student-csv-file');
                if ($file->getClientOriginalExtension() != 'csv') {
                    $csv_validity_alert = Setting::getAlertFailure(trans('student_management/batch_student_upload.csv_validity_text'));
                    return redirect()->back()->with(['status' => $csv_validity_alert]);
                }

                $classCode = $request->get('class-code');
                $class = AcademicLevel::getClassByCode($classCode);
                $programCode = $request->get('program-code');
                $program_name = Program::getCycleNameByCode($programCode);
                if ($class->programs_program_code != $programCode) {
                    $mismatch_alert = Setting::getAlertFailure(trans('student_management/batch_student_upload.program_code_mismatch', ['class' => $class->class_name, 'program' => $program_name]));
                    return redirect()->back()->with(['status' => $mismatch_alert]);
                }

                $data['program_code'] = $programCode;
                if (!Setting::matriculeInitialExist($data)) {
                    $mat_initial_alert = Setting::getAlertFailure(trans('student_management/batch_student_upload.mat_initial_alert', ['program' => $program_name]));
                    return redirect()->back()->with(['status' => $mat_initial_alert]);
                }
                $academicYear = Setting::getAcademicYear();

                $student_list_file_name = 'student_list' . '_' . time() . '.' . $file->getClientOriginalExtension();
                $request->file('student-csv-file')->move(public_path('student_list'), $student_list_file_name);
                $student_list_file_path = 'student_list/' . $student_list_file_name;
                $student_list_log_file_path = 'student_list/' . 'log_' . $student_list_file_name;

                $data = self::performBatchStudentUploadFromCSV($student_list_file_path, $student_list_log_file_path, $academicYear, $class, $sectionCode);

                if ($data == 0) {
                    $file_open_error = Setting::getAlertFailure(trans('student_management/batch_student_upload.file_open_error'));
                    return redirect()->back()->with(['status' => $file_open_error]);

                }

                $student_list = View::make('student_management.student_list')->with($data);

                return view('student_management.batch_student_upload', compact('student_list'));

            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                $csv_validity_alert = Setting::getAlertFailure(trans('student_management/batch_student_upload.csv_validity_text'));

                return redirect()->back()->with(['status' => $csv_validity_alert]);

            }

        }
    }

    /**
     * download the list of students that could not successfully be uploaded
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */

    public function downloadStudentList()
    {
        try {
            $student_list_log_file_path = session('batch_student_list_path');
            return response()->download($student_list_log_file_path)->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * @param $student_list_file_path
     * @param $student_list_log_file_path
     * @param $academicYear
     * @param $class
     * @param $sectionCode
     * @return int
     */
    public static function performBatchStudentUploadFromCSV($student_list_file_path, $student_list_log_file_path, $academicYear, $class, $sectionCode)
    {

        $userId = Auth::user()->user_id;
        $seriesCodes = Series::where(trans('database/table.sections_section_code'), Auth::user()->sections_section_code)->get();

        try {
            $errorCounter = 0;
            $successCounter = 0;
            $matCounter = 0;
            $lastMatricule = Student::getLastMatriculeByCode($class->programs_program_code, $sectionCode, $academicYear);
            $matriculeSetup = Setting::getMatriculeSetting($class->programs_program_code, $sectionCode, $academicYear);

            $matriculeSetupLength = strlen($matriculeSetup);
            if (strcasecmp($lastMatricule, $matriculeSetup) == 0) {
                $matCounter++;
            } else {
                $let = strlen($lastMatricule) - $matriculeSetupLength;
                $matCounter = substr($lastMatricule, -$let);
                $matCounter++;
            }

            $resource = collect([]);
            $classList = collect([]);
            $seriesList = collect([]);
            $accounts = collect([]);
            $student_list_file = fopen(public_path($student_list_file_path), 'r');
            $student_list_log_file = fopen(public_path($student_list_log_file_path), 'a+');

            if ($student_list_file == false || $student_list_log_file == false) {
                return 0;
            }

            $students = Student::where(trans('database/table.sections_section_code'), $sectionCode)
                ->where(trans('database/table.programs_program_code'), $class->programs_program_code)
                ->get();

            $trash = fgetcsv($student_list_file, 1000);
            while (!feof($student_list_file)) {
                $row = fgetcsv($student_list_file, 1000);
                //check whether a student data already exist in the database or in the temporal collection
                if (self::checkDuplicateData($resource, $row)) {
                    $row[] = trans('student_management/batch_student_upload.data_duplicate_within');
                    fputcsv($student_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::checkStudentExistance($students, $row)) {
                    $row[] = trans('student_management/batch_student_upload.data_exist');
                    fputcsv($student_list_log_file, $row);
                    $errorCounter++;
                } elseif (self::isEmptyRow($row)) {

                } else {
                    $padNumber = (MATRICULE_END_NUMBER_LENGTH - 1) + $matriculeSetupLength;
                    $matricule = str_pad($matriculeSetup, $padNumber, '0') . $matCounter++;
                    $resource = $resource->push([
                        trans('database/table.matricule') => trim($matricule),
                        trans('database/table.full_name') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]),
                        trans('database/table.date_of_birth') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[1]),
                        trans('database/table.place_of_birth') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[2]),
                        trans('database/table.region_of_origin') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[3]),
                        trans('database/table.father_address') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[4]),
                        trans('database/table.mother_address') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[5]),
                        trans('database/table.tutor_name') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[6]),
                        trans('database/table.tutor_address') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[7]),
                        trans('database/table.admission_date') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', date('Y-m-d', strtotime($row[8]))),
                        trans('database/table.programs_program_code') => $class->programs_program_code,
                        trans('database/table.sections_section_code') => $sectionCode,
                        trans('database/table.users_user_id') => $userId
                    ]);
                    $classList = $classList->push([
                        trans('database/table.matricule') => trim($matricule),
                        trans('database/table.classes_class_code') => $class->class_code,
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.sections_section_code') => $sectionCode,
                        trans('database/table.users_user_id') => $userId
                    ]);

                    $accounts = $accounts->push([
                        trans('database/table.matricule') => trim($matricule),
                        trans('database/table.secret_code') => Encrypter::encrypt(Str::random(8)),
                        trans('database/table.state') => 1,
                    ]);
                    if (!empty($row[9]) && ($class->programs_program_code == "al" || $class->programs_program_code == "alt" || $class->programs_program_code == "alc") && $seriesCodes->where(trans('database/table.series_code'), $row[9])->isNotEmpty()) {
                        $seriesList = $seriesList->push([
                            trans('database/table.matricule') => trim($matricule),
                            trans('database/table.classes_class_code') => $class->class_code,
                            trans('database/table.series_series_code') => $row[9],
                            trans('database/table.academic_year') => $academicYear,
                            trans('database/table.sections_section_code') => $sectionCode,
                            trans('database/table.users_user_id') => $userId
                        ]);
                    }
                    $successCounter++;
                }
            }
            fclose($student_list_file);
            fclose($student_list_log_file);

            File::delete(public_path($student_list_file_path));

            Student::batchStudentSave($resource->toArray());
            Student::batchAccountSave($accounts->toArray());
            AcademicLevel::batchStudentLevelSave($classList->toArray());
            Series::batchStudentSeriesSave($seriesList->toArray());

            session(['batch_student_list_path' => public_path($student_list_log_file_path)]);
            $data['students'] = $resource->toArray();
            $data['class'] = $class;
            $data['success_counter'] = $successCounter;
            $data['error_counter'] = $errorCounter;
            return $data;
        } catch (Illuminate\Filesystem\FileNotFoundException $e) {
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
            if ($resource['full_name'] == $row[0] && (date('d F Y', strtotime($resource['date_of_birth'])) == date('d F Y', strtotime($row[1]))) && $resource['father_address'] == $row[4]) {
                $sentinel++;
            } elseif ($resource['full_name'] == $row[0] && (date('d F Y', strtotime($resource['date_of_birth'])) == date('d F Y', strtotime($row[1]))) && $resource['mother_address'] == $row[5]) {
                $sentinel++;
            } elseif ($resource['full_name'] == $row[0] && (date('d F Y', strtotime($resource['date_of_birth'])) == date('d F Y', strtotime($row[1]))) && $resource['tutor_address'] == $row[7]) {
                $sentinel++;
            }
        }
        if ($sentinel == 0) {
            return false;
        }

        return true;
    }

    /**
     * @param $students
     * @param $row
     * @return bool
     */
    public static function checkStudentExistance($students, $row)
    {
        $sentinel = 0;
        foreach ($students as $student) {
            if ($student->full_name == $row[0] && (date('d F Y', strtotime($student->date_of_birth)) == date('d F Y', strtotime($row[1]))) && $student->father_address == $row[4]) {
                $sentinel++;
            } elseif ($student->full_name == $row[0] && (date('d F Y', strtotime($student->date_of_birth)) == date('d F Y', strtotime($row[1]))) && $student->mother_address == $row[5]) {
                $sentinel++;
            } elseif ($student->full_name == $row[0] && (date('d F Y', strtotime($student->date_of_birth)) == date('d F Y', strtotime($row[1]))) && $student->tutor_address == $row[7]) {
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
        if (empty($row)) {
            return true;
        }
        return false;
    }

}
