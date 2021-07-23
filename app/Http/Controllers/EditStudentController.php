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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class EditStudentController extends Controller
{
    /**
     * EditStudentController constructor.
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.search_student');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     *  show the edit Subject page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSearchStudentPage()
    {
        $student_table_list = '';
        $success_alert='';
        return view('student_management.search_student', compact('student_table_list', 'success_alert'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function searchStudents(Request $request)
    {
        $this->validate($request, ['search-code' => 'required']);

        $sectionCode = Auth::user()->sections_section_code;
        $searchCode = $request->get('search-code');

        $students = DB::table(trans('database/table.students'))
            ->where(trans('database/table.sections_section_code'), $sectionCode)
            ->where(trans('database/table.full_name'), 'LIKE', '%' . $searchCode . '%')
            ->orwhere(trans('database/table.matricule'), 'LIKE', '%' . $searchCode . '%')
            ->orwhere(trans('database/table.date_of_birth'), 'LIKE', '%' . $searchCode . '%')
            ->get();

        $student_table_list = View::make('student_management/search_student_table', compact('students'));
        return view('student_management.search_student', compact('student_table_list'));

    }


    /**
     * @param $sId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showEditStudentPage($sId)
    {
        try {
            $student = Student::find(Encrypter::decrypt($sId));
            $seriesCode = Student::getStudentSeriesCodeByMatricule($student->matricule);
            $classCode = Student::getStudentClassCodeByMatricule($student->matricule);
            $success_alert = '';
            return \view('student_management.edit_student', compact('seriesCode', 'classCode', 'student', 'success_alert'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/edit_student.error'))]);
        }
    }


    /**
     * @param Request $request
     * @param $sId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editStudent(Request $request, $sId)
    {
        $this->validate($request, ['full-name' => 'required',
            'place-of-birth' => 'required', 'date-of-birth' => 'required',
            'admission-date' => 'required', 'program-code' => 'required',
            'class-code' => 'required', 'father-phone' => 'required',
            'region-of-origin' => 'required',
        ]);

        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        $seriesCodes = Series::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $data = $request->all();
        try {
            $student = Student::find(Encrypter::decrypt($sId));
            $data['matricule'] = $student->matricule;
            if ($request->hasFile('student-picture')) {
                if ($request->file('student-picture')->isValid()) {
                    $file = $request->file('student-picture');
                    if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg') {
                        $student_picture = $data['father-phone'] . '_student_photo' . '_' . time() . '.' . $file->getClientOriginalExtension();
                        $request->file('student-picture')->move(public_path('student_profile'), $student_picture);
                        $student_profile_path = 'student_profile/' . $student_picture;

                    } else {
                        return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/add_student.photo_validity_text'))]);
                    }
                } else {
                    $student_profile_path = $student->profile;
                }
            } else {
                $student_profile_path = $student->profile;
            }


            $classCode = $data['class-code'];
            $class = AcademicLevel::getClassByCode($classCode);
            $programCode = $data['program-code'];
            $program_name = Program::getCycleNameByCode($programCode);
            if ($class->programs_program_code != $programCode) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/add_student.program_code_mismatch', ['class' => $class->class_name, 'program' => $program_name]))]);
            }

            if ($data['full-name'] != $student->full_name) {
                if (Student::studentAlreadyExist($data['full-name'], $data['father-phone'], $programCode)) {
                    return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/add_student.data_exist', ['name' => $data['full-name'], 'phone' => $data['father-phone'], 'program' => $program_name]))]);

                }
            }
            $data['program_code'] = $programCode;
            $academicYear = Setting::getAcademicYear();
            $matricule = Student::generateMatricule($programCode, $sectionCode, $academicYear);

            $resource = [
                trans('database/table.full_name') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['full-name']),
                trans('database/table.date_of_birth') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['date-of-birth']),
                trans('database/table.place_of_birth') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['place-of-birth']),
                trans('database/table.region_of_origin') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['region-of-origin']),
                trans('database/table.father_address') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['father-phone']),
                trans('database/table.mother_address') => !empty($data['mother-phone']) ? preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['mother-phone']) : null,
                trans('database/table.tutor_name') => !empty($data['tutor-name']) ? preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['tutor-name']) : null,
                trans('database/table.tutor_address') => !empty($data['tutor-phone']) ? preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['tutor-phone']) : null,
                trans('database/table.admission_date') => preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data['admission-date']),
                trans('database/table.profile') => $student_profile_path,
                trans('database/table.programs_program_code') => $class->programs_program_code,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId,
            ];
            $classList = collect([
                trans('database/table.classes_class_code') => $class->class_code,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);

            if (($class->programs_program_code == trans('student_management/add_student.al') || $class->programs_program_code == 'alt' || $class->programs_program_code == 'alc') && $seriesCodes->contains($data['series-code'])) {
                $seriesList = collect([
                    trans('database/table.classes_class_code') => $class->class_code,
                    trans('database/table.series_series_code') => $data['series-code'],
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.sections_section_code') => $sectionCode,
                    trans('database/table.users_user_id') => $userId
                ]);
                Series::batchStudentSeriesUpdate($data['matricule'], $seriesList->toArray());
            }
            Student::batchStudentUpdate($data['matricule'], $resource);
            AcademicLevel::batchStudentLevelUpdate($data['matricule'], $classList->toArray());

            //display a success alert to the user
            $success_alert = Setting::getAlertSuccess(trans('student_management/edit_student.edited_student_title', ['name' => $resource['full_name']]));

            $seriesCode = Student::getStudentSeriesCodeByMatricule($student->matricule);
            $classCode = Student::getStudentClassCodeByMatricule($student->matricule);
            Student::recordStudentActions(3, $student->full_name);
            return view('student_management.edit_student', compact('success_alert', 'student', 'seriesCode', 'classCode'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/add_student.error'))]);

        }
    }
}
