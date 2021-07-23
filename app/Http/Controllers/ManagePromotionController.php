<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Evaluation;
use App\Setting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ManagePromotionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.manage_class_promotion');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showClassPromotionPage()
    {
        $academicYear = Setting::getAcademicYear();
        if (Session::has('promotion_year')) {
            Session::forget('promotion_year');
        }
        Session::put('promotion_year', $academicYear);
        $sectionCode = Auth::user()->sections_section_code;

        $class_promotion_management_list = Evaluation::autoPromotionList($sectionCode, $academicYear);
        return view('result_management.school_promotion_home', compact('class_promotion_management_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getPromotionList(Request $request)
    {
        $this->validate($request, ['academic-year' => 'required']);

        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = $request->get('academic-year');

        if (Session::has('promotion_year')) {
            Session::forget('promotion_year');
        }
        Session::put('promotion_year', $academicYear);

        $class_promotion_management_list = Evaluation::autoPromotionList($sectionCode, $academicYear);
        return view('result_management.school_promotion_home', compact('class_promotion_management_list'));
    }

    /**
     * @param $ay
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchSchoolStudentAutoPromote($ay)
    {
        $academicYear = Encrypter::decrypt($ay);
        $sectionCode = Auth::user()->sections_section_code;

        $promotion_matricules = Session::get('general_promotion');
        $repeating_matricules = Session::get('general_repeat_class');

        $this->nextClassPromotionGeneral($promotion_matricules, $sectionCode, $academicYear);
        $this->classRepeatsGeneral($repeating_matricules, $sectionCode, $academicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/auto_promotion.auto_p_r'))]);
    }

    /**
     * @param $matricules
     * @param $sectionCode
     * @param $academicYear
     * @return int
     */
    private function nextClassPromotionGeneral($matricules, $sectionCode, $academicYear)
    {
        $class_has_students = AcademicLevel::getSchoolClassStudentRecords($sectionCode, $academicYear);
        foreach ($matricules as $matricule) {
            if (empty($matricule) || empty($academicYear)) {
            } else {
                $class_has_student = $class_has_students->where(trans('database/table.matricule'), $matricule)->first();
                if (!empty($class_has_student)) {
                    $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
                    $class = AcademicLevel::getClassByCode($class_has_student->classes_class_code);
                    if (!empty($class)) {
                        if(strcmp($class->next_promotion_class, trans('general.university_code')) == 0 || strcmp($class->next_promotion_class, trans('general.none')) == 0){
                            $promotionClass =null;
                        }else {
                            $promotionClass = AcademicLevel::getClassByCode($class->next_promotion_class);
                        }
                        if (empty($promotionClass)) {
                        } else {
                            AcademicLevel::updateOrCreate($matricule, $promotionClass->class_code, $nextAcademicYear);
                        }
                    }
                }
            }
        }
        return 0;
    }

    /**
     * @param $matricules
     * @param $classCode
     * @param $academicYear
     * @return int
     */
    private function classRepeatsGeneral($matricules, $sectionCode, $academicYear)
    {
        $class_has_students = AcademicLevel::getSchoolClassStudentRecords($sectionCode, $academicYear);
        foreach ($matricules as $matricule) {
            if (empty($matricule) || empty($academicYear)) {
            } else {
                $class_has_student = $class_has_students->where(trans('database/table.matricule'), $matricule)->first();
                if (!empty($class_has_student)) {
                    $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
                    AcademicLevel::updateOrCreate($matricule, $class_has_student->classes_class_code, $nextAcademicYear);
                }
            }
        }
        return 0;
    }

    /**
     * @param $mat
     * @param $ay
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteStudent($mat, $ay)
    {
        $academicYear = Encrypter::decrypt($ay);
        $matricule = Encrypter::decrypt($mat);

        if (empty($matricule) || empty($academicYear)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/auto_promotion.session_not_found'))]);
        }
        $student = Student::getStudentByMatricule($matricule);
        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);
        $class = AcademicLevel::getClassByCode($classCode);
        if(strcmp($class->next_promotion_class, trans('general.university_code')) == 0 || strcmp($class->next_promotion_class, trans('general.none')) == 0){
            $promotionClass =null;
        }else {
            $promotionClass = AcademicLevel::getClassByCode($class->next_promotion_class);
        }
        if (empty($promotionClass)) {
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/auto_promotion.promoted_to', ['name' => $student->full_name, 'class' => trans('result_management/auto_promotion.p_class'),'year' =>$academicYear]))]);
        }
        AcademicLevel::updateOrCreate($matricule, $promotionClass->class_code, $nextAcademicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/auto_promotion.promoted_to', ['name' => $student->full_name, 'class' => $promotionClass->class_name,'year' => $academicYear]))]);
    }

    /**
     * @param $mat
     * @param $ay
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repeatClass($mat, $ay)
    {
        $academicYear = Encrypter::decrypt($ay);
        $matricule = Encrypter::decrypt($mat);

        if (empty($matricule) || empty($academicYear)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/auto_promotion.session_not_found'))]);
        }
        $student = Student::getStudentByMatricule($matricule);
        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);

        AcademicLevel::updateOrCreate($matricule, $classCode, $nextAcademicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/auto_promotion.repeat_class_p', ['name' => $student->full_name, 'class' => AcademicLevel::getClassNameByCode($classCode),'year' => $academicYear]))]);

    }
}
