<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Evaluation;
use App\PublishStatus;
use App\Sequence;
use App\Setting;
use App\Student;
use App\Term;
use Barryvdh\Snappy\Facades\SnappyPdf as PDFPRINT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class PrintReportCardController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.print_report_card');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showPrintReportCardPage()
    {
        $report_card = [];
        return view('result_management.report_card', compact('report_card'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function printReportCard(Request $request)
    {
        $this->validate($request, ['matricule-no' => 'required', 'academic-year' => 'required']);

        $matricule = $request->get('matricule-no');
        $academicYear = $request->get('academic-year');
        $sectionCode = Auth::user()->sections_section_code;

        $annual_average = 0;
        $cummulative_average = 0;

        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();

        $student = Student::getStudentByMatricule($matricule);
        if (empty($student)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.student_validity_alert'))]);

        }
        /* if (Student::isSuspended($student->matricule)) {
             return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.suspension_alert', ['mat' => $student->matricule]))]);
         }*/

        if (Session::has('report_card_year')) {
            Session::forget('report_card_year');
        }
        if (Session::has('report_card_matricule')) {
            Session::forget('report_card_matricule');
        }
        Session::put('report_card_year', $academicYear);
        Session::put('report_card_matricule', $matricule);

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);
        if (empty($classCode)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.no_class_alert', ['mat' => $student->matricule, 'year' => $academicYear]))]);
        }
        $class = AcademicLevel::getClassByCode($classCode);
        $annual_promotion_average = $class->annual_promotion_average;

        $header = View::make('student_portal.school_header');
        $report_card[0] = $header;
        $year = $academicYear;
        $sentinel = 0;
        foreach ($terms as $term) {
            $termId = $term->term_id;
            $checker = PublishStatus::termResultExistance($termId, $academicYear, $sectionCode);
            if ($checker['status'] == 1) {
                $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $termId);
                $term_result = Evaluation::calculateTermResults($sequences, $student, $academicYear);
                $header = '';/*View::make('student_portal.school_header');*/
                if ($sentinel == 0) {
                    $result_details = View::make('result_management.term_result_table_header', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
                } else {
                    $result_details = View::make('result_management.term_result_table', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));

                }
                $report_card[$termId] = $result_details;
                $term_session_key = 'term_' . $termId;
                if (Session::has($term_session_key)) {
                    $cummulative_average += Session::get($term_session_key);
                }
            } else {
                $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.term_publish_alert', ['term' => (Term::getTermNameById($termId) . ' ' . trans('general.result')), 'year' => $academicYear, 'sequence' => $checker['sequence_name']]));
                $report_card [$term->term_id] = '<br>' . $result_details;
            }
            $sentinel++;
        }

        $annual_average = round(($cummulative_average / NUMBER_OF_TERMS), ROUND_UP_PRECISION);
        $nextIndex = $term->term_id + 1;
        $promotionClass = AcademicLevel::getClassNameById(($class->class_id + 1));

        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
        $nextAcademicClass = AcademicLevel::promotionClass($matricule, $nextAcademicYear);
        if (!empty($nextAcademicClass)) {
            $nextAcademicClassName = AcademicLevel::getClassNameByCode($nextAcademicClass->classes_class_code);
        }
        $report_card[$nextIndex] = '';

        if ($annual_average < $annual_promotion_average) {
            $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';
            if (empty($nextAcademicClass)) {
                $report_card[$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_failure', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5><br></div>';
            } elseif ($nextAcademicClassName == $promotionClass) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            } elseif ($nextAcademicClassName == $class->class_name) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            }
            $report_card[$nextIndex] .= '<a href="' . trans('settings/routes.repeat_class') . '" class="btn bg-green" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.maintain_student') . '</h6></a><br><br><a href="' . trans('settings/routes.download_student_report_card') . '" class="btn c-ewangclarks"  style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.download_rp') . '</h6></a><br><br>';

        } else {
            $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';

            if (empty($nextAcademicClass)) {
                $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_success', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br>';
            } elseif ($nextAcademicClassName == $promotionClass) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            } elseif ($nextAcademicClassName == $class->class_name) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            }
            $report_card[$nextIndex] .= '<a href="' . trans('settings/routes.promote_student') . '" class="btn bg-green" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.promote_student') . '</h6></a><br><br><a href="' . trans('settings/routes.download_student_report_card') . '" class="btn c-ewangclarks" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.download_rp') . '</h6></a><br><br>';

        }
        return view('result_management.report_card', compact('report_card'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function promoteStudent()
    {
        $matricule = Session::get('report_card_matricule');
        $academicYear = Session::get('report_card_year');

        if (empty($matricule) || empty($academicYear)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.session_not_found'))]);
        }
        $student = Student::getStudentByMatricule($matricule);
        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);
        $class = AcademicLevel::getClassByCode($classCode);
        $promotionClass = AcademicLevel::getClassById(($class->class_id + 1));
        if (empty($promotionClass)) {
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/report_card.promoted_to', ['name' => $student->full_name, 'class' => trans('result_management/report_card.p_class')]))]);
        }
        AcademicLevel::updateOrCreate($matricule, $promotionClass->class_code, $nextAcademicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/report_card.promoted_to', ['name' => $student->full_name, 'class' => $promotionClass->class_name]))]);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repeatClass()
    {
        $matricule = Session::get('report_card_matricule');
        $academicYear = Session::get('report_card_year');

        if (empty($matricule) || empty($academicYear)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.session_not_found'))]);
        }
        $student = Student::getStudentByMatricule($matricule);
        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);

        AcademicLevel::updateOrCreate($matricule, $classCode, $nextAcademicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/report_card.repeat_class', ['name' => $student->full_name, 'class' => AcademicLevel::getClassNameByCode($classCode)]))]);

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadStudentReportCard()
    {
        $matricule = Session::get('report_card_matricule');
        $academicYear = Session::get('report_card_year');

        if (empty($matricule) || empty($academicYear)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.session_not_found'))]);
        }
        $student = Student::getStudentByMatricule($matricule);

        $sectionCode = Auth::user()->sections_section_code;

        $annual_average = 0;
        $cummulative_average = 0;

        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();

        $classCode = Student::getStudentClassCodePerYear($matricule, $academicYear);
        if (empty($classCode)) {
            return redirect()->back();
        }
        $class = AcademicLevel::getClassByCode($classCode);
        $annual_promotion_average = $class->annual_promotion_average;
        $header = View::make('result_management.school_header_download');
        $report_card[0] = $header;
        $year = $academicYear;
        $sentinel = 0;
        foreach ($terms as $term) {
            $termId = $term->term_id;
            $checker = PublishStatus::termResultExistance($termId, $academicYear, $sectionCode);
            if ($checker['status'] == 1) {
                $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $termId);
                $term_result = Evaluation::calculateTermResults($sequences, $student, $academicYear);
                $header = '';/*View::make('student_portal.school_header');*/
                if ($sentinel == 0) {
                    $result_details = View::make('result_management.term_result_table_header_download', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
                } else {
                    $result_details = View::make('result_management.term_result_table_download', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));

                }
                $report_card[$termId] = $result_details;
                $term_session_key = 'term_' . $termId;
                if (Session::has($term_session_key)) {
                    $cummulative_average += Session::get($term_session_key);
                }
            } else {
                $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.term_publish_alert', ['term' => (Term::getTermNameById($termId) . ' ' . trans('general.result')), 'year' => $academicYear, 'sequence' => $checker['sequence_name']]));
                $report_card [$term->term_id] = '<br>' . $result_details;
            }
            $sentinel++;
        }

        $annual_average = round(($cummulative_average / NUMBER_OF_TERMS), ROUND_UP_PRECISION);
        $nextIndex = $term->term_id + 1;
        $promotionClass = AcademicLevel::getClassNameById(($class->class_id + 1));

        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
        $nextAcademicClass = AcademicLevel::promotionClass($matricule, $nextAcademicYear);
        if (!empty($nextAcademicClass)) {
            $nextAcademicClassName = AcademicLevel::getClassNameByCode($nextAcademicClass->classes_class_code);
        }
        $report_card[$nextIndex] = '';

        if ($annual_average < $annual_promotion_average) {
            $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';
            if (empty($nextAcademicClass)) {
                $report_card[$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_failure', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><a href="' . trans('settings/routes.repeat_class') . '" class="btn bg-green" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.maintain_student') . '</h6></a><br>';
            } elseif ($nextAcademicClassName == $promotionClass) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            } elseif ($nextAcademicClassName == $class->class_name) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            }

        } else {
            $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';

            if (empty($nextAcademicClass)) {
                $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_success', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br><a href="' . trans('settings/routes.promote_student') . '" class="btn bg-green" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.promote_student') . '</h6></a><br><br>';
            } elseif ($nextAcademicClassName == $promotionClass) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            } elseif ($nextAcademicClassName == $class->class_name) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
            }
        }

        $pdf = PDFPRINT::loadView('result_management.student_report_card_download', compact('report_card'));
        $filename = str_replace(" ", "_", $student->full_name) . '_' . trans('result_management/report_card.report_card') . '.pdf';
        return $pdf->download($filename);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function printClassReportCard(Request $request)
    {

        $this->validate($request, ['academic-year' => 'required', 'class-code' => 'required']);

        $classCode = $request->get('class-code');
        $academicYear = $request->get('academic-year');
        $class = AcademicLevel::getClassByCode($classCode);
        $annual_promotion_average = $class->annual_promotion_average;

        $sectionCode = Auth::user()->sections_section_code;

        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();

        /* if (Student::isSuspended($student->matricule)) {
             return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.suspension_alert', ['mat' => $student->matricule]))]);
         }*/

        if (Session::has('report_card_year')) {
            Session::forget('report_card_year');
        }
        if (Session::has('report_card_class')) {
            Session::forget('report_card_class');
        }
        Session::put('report_card_year', $academicYear);
        Session::put('report_card_class', $classCode);

        $promotion_next_class = [];
        $repeat_class = [];

        $students = AcademicLevel::getStudentsByClassCode($classCode, $academicYear, $sectionCode);
        if ($students->isEmpty()) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.no_student_alert', ['class' => AcademicLevel::getClassNameByCode($classCode), 'year' => $academicYear]))]);
        }
        $year = $academicYear;

        foreach ($students as $student) {
            $annual_average = 0;
            $cummulative_average = 0;
            $report_card = [];
            $header = View::make('student_portal.school_header');
            $report_card[0] = $header;
            $sentinel = 0;
            foreach ($terms as $term) {
                $termId = $term->term_id;
                $checker = PublishStatus::termResultExistance($termId, $academicYear, $sectionCode);
                if ($checker['status'] == 1) {
                    $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $termId);
                    $term_result = Evaluation::calculateTermResults($sequences, $student, $academicYear);
                    $header = '';/*View::make('student_portal.school_header');*/
                    if ($sentinel == 0) {
                        $result_details = View::make('result_management.term_result_table_header', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
                    } else {
                        $result_details = View::make('result_management.term_result_table', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));

                    }
                    $report_card[$termId] = $result_details;
                    $term_session_key = 'term_' . $termId;
                    if (Session::has($term_session_key)) {
                        $cummulative_average += Session::get($term_session_key);
                    }
                } else {
                    $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.term_publish_alert', ['term' => (Term::getTermNameById($termId) . ' ' . trans('general.result')), 'year' => $academicYear, 'sequence' => $checker['sequence_name']]));
                    $report_card [$term->term_id] = '<br>' . $result_details;
                }
                $sentinel++;
            }

            Evaluation::cleanClassReportCardSession($terms);
            $annual_average = round(($cummulative_average / NUMBER_OF_TERMS), ROUND_UP_PRECISION);
            $nextIndex = $term->term_id + 1;
            $promotionClass = AcademicLevel::getClassNameById(($class->class_id + 1));

            $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
            $nextAcademicClass = AcademicLevel::promotionClass($student->matricule, $nextAcademicYear);
            if (!empty($nextAcademicClass)) {
                $nextAcademicClassName = AcademicLevel::getClassNameByCode($nextAcademicClass->classes_class_code);
            }
            $report_card[$nextIndex] = '';

            if ($annual_average < $annual_promotion_average) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';
                if (empty($nextAcademicClass)) {
                    $report_card[$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_failure', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $promotionClass) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $class->class_name) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                }
                $repeat_class[] = $student->matricule;
            } else {
                $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';

                if (empty($nextAcademicClass)) {
                    $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_success', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $promotionClass) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $class->class_name) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                }
                $promotion_next_class[] = $student->matricule;
            }
            $class_report_cards[] = $report_card;
        }

        if (Session::has('report_card_promotion')) {
            Session::forget('report_card_promotion');
        }
        if (Session::has('report_card_repeat_class')) {
            Session::forget('report_card_repeat_class');
        }
        Session::put('report_card_promotion', $promotion_next_class);
        Session::put('report_card_repeat_class', $repeat_class);

        $download_report_cards = '<a href="' . trans('settings/routes.download_class_report_card') . '" class="btn c-ewangclarks" style="width: 40%;position: relative;left: 30%;"> <h6 class="text-white"><i class="zmdi zmdi-cloud-download"></i>' . trans('result_management/report_card.download_rps') . '</h6></a><br><br>';
        return view('result_management.class_report_card', compact('class_report_cards', 'download_report_cards'));
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadClassReportCards()
    {
        $classCode = Session::get('report_card_class');
        $academicYear = Session::get('report_card_year');

        $class = AcademicLevel::getClassByCode($classCode);
        $annual_promotion_average = $class->annual_promotion_average;
        $sectionCode = Auth::user()->sections_section_code;

        $terms = Term::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $all_sequences = Sequence::where(trans('database/table.sections_section_code'), $sectionCode)->get();

        /* if (Student::isSuspended($student->matricule)) {
             return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.suspension_alert', ['mat' => $student->matricule]))]);
         }*/

        $students = AcademicLevel::getStudentsByClassCode($classCode, $academicYear, $sectionCode);
        if ($students->isEmpty()) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/report_card.no_student_alert', ['class' => AcademicLevel::getClassNameByCode($classCode), 'year' => $academicYear]))]);
        }
        $year = $academicYear;

        foreach ($students as $student) {
            $annual_average = 0;
            $cummulative_average = 0;
            $report_card = [];
            $header = View::make('result_management.school_header_download');
            $report_card[0] = $header;
            $sentinel = 0;
            foreach ($terms as $term) {
                $termId = $term->term_id;
                $checker = PublishStatus::termResultExistance($termId, $academicYear, $sectionCode);
                if ($checker['status'] == 1) {
                    $sequences = $all_sequences->where(trans('database/table.terms_term_id'), $termId);
                    $term_result = Evaluation::calculateTermResults($sequences, $student, $academicYear);
                    $header = '';/*View::make('student_portal.school_header');*/
                    if ($sentinel == 0) {
                        $result_details = View::make('result_management.term_result_table_header_download', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
                    } else {
                        $result_details = View::make('result_management.term_result_table_download', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));

                    }
                    $report_card[$termId] = $result_details;
                    $term_session_key = 'term_' . $termId;
                    if (Session::has($term_session_key)) {
                        $cummulative_average += Session::get($term_session_key);
                    }
                } else {
                    $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.term_publish_alert', ['term' => (Term::getTermNameById($termId) . ' ' . trans('general.result')), 'year' => $academicYear, 'sequence' => $checker['sequence_name']]));
                    $report_card [$term->term_id] = '<br>' . $result_details;
                }
                $sentinel++;
            }

            Evaluation::cleanClassReportCardSession($terms);
            $annual_average = round(($cummulative_average / NUMBER_OF_TERMS), ROUND_UP_PRECISION);
            $nextIndex = $term->term_id + 1;
            $promotionClass = AcademicLevel::getClassNameById(($class->class_id + 1));

            $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
            $nextAcademicClass = AcademicLevel::promotionClass($student->matricule, $nextAcademicYear);
            if (!empty($nextAcademicClass)) {
                $nextAcademicClassName = AcademicLevel::getClassNameByCode($nextAcademicClass->classes_class_code);
            }
            $report_card[$nextIndex] = '';

            if ($annual_average < $annual_promotion_average) {
                $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';
                if (empty($nextAcademicClass)) {
                    $report_card[$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_failure', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $promotionClass) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $class->class_name) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                }
            } else {
                $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.annual_average') . $annual_average . '/' . 20 . '</h5></div><br>';

                if (empty($nextAcademicClass)) {
                    $report_card[$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promotion_success', ['name' => $student->full_name, 'class' => $promotionClass == null ? trans('result_management/report_card.p_class') : $promotionClass]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $promotionClass) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-success" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.promoted_to', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                } elseif ($nextAcademicClassName == $class->class_name) {
                    $report_card [$nextIndex] .= '<br><div class="alert alert-danger" style="padding-top: 15px;padding-bottom: 15px;"><h5 style="color:white;">' . trans('result_management/report_card.repeat_class', ['class' => $nextAcademicClassName, 'year' => $nextAcademicYear, 'name' => $student->full_name]) . '</h5></div><br>';
                }
            }
            $class_report_cards[] = $report_card;
        }
        $pdf = PDFPRINT::loadView('result_management.class_report_card_download', compact('class_report_cards'));
        $filename = str_replace(" ", "_", $class->class_name) . '_' . trans('result_management/report_card.report_card') . '.pdf';
        return $pdf->download($filename);
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function autoPromoteAndRepeat()
    {
        $classCode = Session::get('report_card_class');
        $academicYear = Session::get('report_card_year');

        $promotion_matricules = Session::get('report_card_promotion');
        $repeating_matricules = Session::get('report_card_repeat_class');

        Evaluation::nextClassPromotion($promotion_matricules, $classCode, $academicYear);
        Evaluation::classRepeats($repeating_matricules, $classCode, $academicYear);
        return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('result_management/report_card.auto_p_r', ['class' => AcademicLevel::getClassNameByCode($classCode)]))]);
    }


}
