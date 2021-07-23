<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Setting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class ManagePortalAccessController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_student_portal_access');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManagePortalAccessPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();
        $students = Student::getStudentPerYear($academicYear, $sectionCode);
        if ($students->isEmpty()) {
            $student_list = Setting::getAlertFailure(trans('student_management/student_portal_access.no_student_alert', ['year' => $academicYear]));
        } else {
            $secret_codes = $this->getShuffleSecrete($students);
            $student_list = View::make('student_management.student_portal_list', compact('students', 'secret_codes', 'academicYear'));
        }
        return view('student_management.student_portal_access_home', compact('student_list'));
    }

    /**
     * @param $students
     * @return \Illuminate\Support\Collection
     */
    private function getShuffleSecrete($students)
    {
        $matricules = $students->unique(trans('database/table.matricule'))->pluck(trans('database/table.matricule'))->toArray();
        $secrets = Student::getPortalSecretCodes();

        $secret_codes = $secrets->whereIn(trans('database/table.matricule'), $matricules);

        return $secret_codes;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStudentList(Request $request)
    {
        $this->validate($request, ['academic-year' => 'required']);
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = $request->get('academic-year');

       $students = Student::getStudentPerYear($academicYear, $sectionCode);
        if ($students->isEmpty()) {
            $student_list = Setting::getAlertFailure(trans('student_management/student_portal_access.no_student_alert', ['year' => $academicYear]));
        } else {
            $secret_codes = $this->getShuffleSecrete($students);
            $student_list = View::make('student_management.student_portal_list', compact('students', 'secret_codes', 'academicYear'));
        }
        return view('student_management.student_portal_access_home', compact('student_list'));

    }

    /**
     * @param $mat
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function studentSuspension($mat)
    {
        try {
            $matricule = Encrypter::decrypt($mat);

            $student = Student::getStudentByMatricule($matricule);
            if (empty($student)) {
                return redirect()->back();
            }
            $student->academic_state = 0;
            $student->save();
            Student::recordStudentActions(1,$student->full_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('student_management/student_portal_access.suspension_success', ['name' => $student->full_name]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/student_portal_access.session_not_found'))]);

        }
    }

    /**
     * @param $mat
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function revertStudentSuspension($mat)
    {
        $matricule = Encrypter::decrypt($mat);
        try {
            $matricule = Encrypter::decrypt($mat);

            $student = Student::getStudentByMatricule($matricule);
            if (empty($student)) {
                return redirect()->back();
            }
            $student->academic_state = 1;
            $student->save();
            Student::recordStudentActions(2,$student->full_name);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('student_management/student_portal_access.revert_suspension_success', ['name' => $student->full_name]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/student_portal_access.session_not_found'))]);

        }
    }

    /**
     * @param $mat
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function generateResultPortalAccessCode($mat)
    {
        $matricule = Encrypter::decrypt($mat);
        try {
            $matricule = Encrypter::decrypt($mat);

            $student = Student::getStudentByMatricule($matricule);
        if (empty($student)) {
            return redirect()->back();
        }
        $secret = Str::random(8);
        Student::recordStudentActions(4,$student->full_name);
        Student::resetSecret($matricule, $secret);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('student_management/student_portal_access.generate_secret_success', ['name' => $student->full_name,'code' => $secret]))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_management/student_portal_access.session_not_found'))]);

        }

    }

}
