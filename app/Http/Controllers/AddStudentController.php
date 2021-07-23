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
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;


class AddStudentController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.add_student');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * Show th batch student upload page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAddStudentPage()
    {

        $student_list = '';
        $success_alert = '';
        return view('student_management.add_student', compact('student_list', 'success_alert'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addStudent(Request $request)
    {
        $this->validate($request, ['full-name' => 'required',
            'place-of-birth' => 'required', 'date-of-birth' => 'required',
            'admission-date' => 'required', 'program-code' => 'required',
            'class-code' => 'required', 'father-phone' => 'required',
            'region-of-origin' => 'required'
        ]);

        $seriesCodes = collect([trans('settings/setting.s1'), trans('settings/setting.s2'),
            trans('settings/setting.s3'), trans('settings/setting.s4'),
            trans('settings/setting.s5'), trans('settings/setting.s6'),
            trans('settings/setting.s7'), trans('settings/setting.s8'),
            trans('settings/setting.a1'), trans('settings/setting.a2'),
            trans('settings/setting.a3'), trans('settings/setting.a4'),
            trans('settings/setting.a5'), trans('settings/setting.a6')
        ]);

        $data = $request->all();
        $sectionCode = Auth::user()->sections_section_code;
        $userId = Auth::user()->user_id;

        try {
            if ($request->file('student-picture')->isValid()) {

                $file = $request->file('student-picture');
                if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg') {
                    $student_picture = $data['father-phone'] . '_student_photo' . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $request->file('student-picture')->move(public_path('student_profile'), $student_picture);
                    $student_profile_path = 'student_profile/' . $student_picture;

                } else {

                    $photo_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('student_management/add_student.photo_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                    return redirect()->back()->with(['status' => $photo_validity_alert]);
                }
            } else {
                $student_profile_path = trans('img/img.default_profile');

            }

            $classCode = $data['class-code'];
            $class = AcademicLevel::getClassByCode($classCode);
            $programCode = $data['program-code'];
            $program_name = Program::getCycleNameByCode($programCode);
            if ($class->programs_program_code != $programCode) {
                $mismatch_alert = '<div class="alert alert-dismissible alert-danger">' . trans('student_management/add_student.program_code_mismatch', ['class' => $class->class_name, 'program' => $program_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $mismatch_alert]);
            }

            $data['program_code'] = $programCode;
            if (!Setting::matriculeInitialExist($data)) {
                $mat_initial_alert = '<div class="alert alert-dismissible alert-danger">' . trans('student_management/add_student.mat_initial_alert', ['program' => $program_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $mat_initial_alert]);
            }

            if (Student::studentAlreadyExist($data['full-name'], $data['father-phone'], $programCode)) {
                $student_exist = '<div class="alert alert-dismissible alert-danger">' . trans('student_management/add_student.data_exist', ['name' => $data['full-name'], 'phone' => $data['father-phone'], 'program' => $program_name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';
                return redirect()->back()->with(['status' => $student_exist]);

            }
            $academicYear = Setting::getAcademicYear();
            $matricule = Student::generateMatricule($programCode, $sectionCode, $academicYear);

            $resource = [
                trans('database/table.matricule') => trim($matricule),
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

            $accounts = collect([
                trans('database/table.matricule') => trim($matricule),
                trans('database/table.secret_code') => Encrypter::encrypt(Str::random(8)),
                trans('database/table.state') => 1,
            ]);

            $classList = collect([
                trans('database/table.matricule') => trim($matricule),
                trans('database/table.classes_class_code') => $class->class_code,
                trans('database/table.academic_year') => $academicYear,
                trans('database/table.sections_section_code') => $sectionCode,
                trans('database/table.users_user_id') => $userId
            ]);

            if (($class->programs_program_code == trans('student_management/add_student.al') || $class->programs_program_code == 'alt' || $class->programs_program_code == 'alc') && $seriesCodes->contains($data['series-code'])) {
                $seriesList = collect([
                    trans('database/table.matricule') => trim($matricule),
                    trans('database/table.classes_class_code') => $class->class_code,
                    trans('database/table.series_series_code') => $data['series-code'],
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.sections_section_code') => $sectionCode,
                    trans('database/table.users_user_id') => $userId
                ]);
                Series::batchStudentSeriesSave($seriesList->toArray());
            }
            Student::batchStudentSave($resource);
            Student::batchAccountSave($accounts->toArray());
            AcademicLevel::batchStudentLevelSave($classList->toArray());

            //display a success alert to the user
            $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('student_management/add_student.added_student_title', ['name' => $resource['full_name'], 'class' => $class->class_name, 'year' => $academicYear]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

            $student = Student::getStudentByMatricule($matricule);

            $student_list = View::make('student_management.add_student_table', compact('student', 'class'));

            return view('student_management.add_student', compact('student_list', 'success_alert'));

        } catch (\Exception $e) {
            $unexpected_error = '<div class="alert alert-dismissible alert-danger">' . trans('student_management/add_student.error') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                 </button></div>';
            return redirect()->back()->with(['status' => $unexpected_error]);

        }
    }
}
