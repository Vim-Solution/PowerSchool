<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Evaluation;
use App\Program;
use App\PublishStatus;
use App\Sequence;
use App\Setting;
use App\Student;
use App\Subject;
use App\Term;
use Barryvdh\Snappy\Facades\SnappyPdf as PDFPRINT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class StudentPortalController extends Controller
{

    public function __construct()
    {
        if (cache()->has('n_language')) {
            App::setLocale(cache()->get('n_language'));
        }
        $this->middleware('guest');
    }

    /**
     * Show student result page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResultPortalPage()
    {

        $result_details = '';
        $date = '';
        $full_name = '';
        return view('student_portal.result_portal', compact('result_details', 'date', 'full_name'));

    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStudentResult(Request $request)
    {
        $this->validate($request, ['result-type' => 'required',
            'academic-year' => 'required',
            'matricule-no' => 'required',
            'secret-code' => 'required'
        ]);

        $data = $request->all();
        $result_details = '';
        
        if (trans('settings/setting.school_type') == trans('settings/setting.anglophone')) {
            $sectionCode = trans('general.english');
        } elseif (trans('settings/setting.school_type') == trans('settings/setting.francophone')) {
            $sectionCode = trans('general.fr');
        } else {
            $sectionCode = App::getLocale();
        }

        $student = Student::getStudentByMatricule($data['matricule-no']);
        if (empty($student)) {
            return redirect()->back();
        }

        if (Student::credentialChecker($data['matricule-no'], $data['secret-code'])) {
            //check whether the student is suspended
            if (Student::isSuspended($data['matricule-no'])) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_portal/result_portal.suspension_alert', ['mat' => $data['matricule-no']]))]);
            }

            $result_type = strtok($data['result-type'], '-');
            $result_id = strtok('-');
            if ($result_type == "s") {
                $year = $data['academic-year'];
                $sequenceId = $result_id;
                if (PublishStatus::sequenceResultExistance($result_id, $data['academic-year'], $sectionCode)) {
                    $student_scores = Subject::getFinalMarks($result_id, $student->student_id, $data['academic-year']);
                    $classCode = Student::getStudentClassCodeByMatricule($student->matricule);
                    $sequence_result = Evaluation::calculateSequenceResults($student_scores, $sequenceId, $classCode, $year, $student->student_id);
                    $header = '';/*View::make('student_portal.school_header');*/
                    $result_details = View::make('student_portal.sequence_result_table', compact('sequence_result', 'year', 'sequenceId', 'header', 'student'));
                } else {
                    $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.sequence_publish_alert', ['sequence' => (Sequence::getSequenceNameById($result_id) . ' ' . trans('general.result')), 'year' => $data['academic-year']]));
                }
            } elseif ($result_type == "t") {
                $year = $data['academic-year'];
                $termId = $result_id;
                $checker = PublishStatus::termResultExistance($termId, $data['academic-year'], $sectionCode);
                if ($checker['status'] == 1) {
                    $sequences = Sequence::where(trans('database/table.terms_term_id'), $result_id)->get();
                    $term_result = Evaluation::calculateTermResults($sequences, $student, $data['academic-year']);
                    $header = '';/*View::make('student_portal.school_header');*/
                    $result_details = View::make('student_portal.term_result_table', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
                } else {
                    $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.term_publish_alert', ['term' => (Term::getTermNameById($result_id) . ' ' . trans('general.result')), 'year' => $data['academic-year'], 'sequence' => $checker['sequence_name']]));
                }
            } elseif ($result_type == "p") {
                $programCode = Program::getProgramCodeById($result_id);
                if (Setting::publicExamExist($programCode, $data['academic-year'])) {
                    $examSetting = Setting::getPublicExamSettingRecord($programCode, $data['academic-year']);
                    $result_details = View::make('student_portal.public_exam_result_table', compact('examSetting', 'student'));
                } else {
                    $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.exam_not_publish', ['gce' => Program::getCycleNameByCode($programCode) . ' ' . trans('general.result'), 'year' => $data['academic-year']]));
                }
            }
        } else {
            $result_details = Setting::getAlertFailure(trans('student_portal/result_portal.wrong_credentials'));
        }

        $date = $student->date_of_birth;
        $full_name = $student->full_name;
        return view('student_portal.result_portal', compact('result_details', 'date', 'full_name'));
    }

    /**
     * @param $student_id
     * @param $term_id
     * @param $academicYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadTermResult($student_id, $term_id, $academicYear)
    {
        $studentId = Encrypter::decrypt($student_id);
        $termId = Encrypter::decrypt($term_id);
        $year = Encrypter::decrypt($academicYear);

        $student = Student::find($studentId);
        $sequence = Sequence::find($termId);

        if (empty($student) || empty($sequence)) {
            return redirect()->back();
        }

        $sequences = Sequence::where(trans('database/table.terms_term_id'), $termId)->get();
        $term_result = Evaluation::calculateTermResults($sequences, $student, $year);
        $header = View::make('result_management.school_header_download');
        $result_details = View::make('student_portal.term_result_table_download', compact('term_result', 'year', 'termId', 'header', 'student', 'sequences'));
        $pdf = PDFPRINT::loadView('student_portal.result_download', compact('result_details'));
        $filename = str_replace(" ", "_", $sequence->sequence_name) . '_' . trans('general.result') . '.pdf';
        return $pdf->download($filename);
    }


    /**
     * @param $student_id
     * @param $sequence_id
     * @param $academicYear
     * @return \Illuminate\Http\RedirectResponse
     */
    public function downloadSequenceResult($student_id, $sequence_id, $academicYear)
    {
        $studentId = Encrypter::decrypt($student_id);
        $sequenceId = Encrypter::decrypt($sequence_id);
        $year = Encrypter::decrypt($academicYear);

        $student = Student::find($studentId);
        $sequence = Sequence::find($sequenceId);
        $classCode = Student::getStudentClassCodeByMatricule($student->matricule);

        if (empty($student) || empty($sequence)) {
            return redirect()->back();
        }
        $student_scores = Subject::getFinalMarks($sequenceId, $student->student_id, $year);
        $sequence_result = Evaluation::calculateSequenceResults($student_scores, $sequenceId, $classCode, $academicYear, $studentId);
        $header = View::make('result_management.school_header_download');
        $result_details = View::make('student_portal.sequence_result_table_download', compact('sequence_result', 'year', 'sequenceId', 'header', 'student'));

        $pdf = PDFPRINT::loadView('student_portal.result_download', compact('result_details'));
        $filename = str_replace(" ", "_", $sequence->sequence_name) . '_' . trans('general.result') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Download public exam
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadPublicExam($id)
    {
        $eid = Encrypter::decrypt($id);
        try {
            $examSetting = Setting::getPublicExamSettinById($eid);
            if (empty($examSetting)) {
                return redirect()->back();
            }
            return response()->download(public_path($examSetting->exam_file_path));
        } catch (\Exception $e) {
            return redirect()->back();
        }
    }

    /**
     * show student information page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */

    public function showStudentInformationPage()
    {

        $student_details = '';
        $success_alert = '';
        return view('student_portal.student_info', compact('student_details', 'success_alert'));

    }

    /**
     * Get student Academic Information
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStudentInformation(Request $request)
    {
        $this->validate($request,
            ['student-name' => 'required']);

        $student_details = '';

        $name = $request->get('student-name');

        $students = Student::getStudentsByName($name);

        if ($students->isEmpty()) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('student_portal/student_info.empty_student', ['name' => $name]))]);
        }

        if ($students->count() == 1) {
            $student = $students->last();
            $student_details = View::make('/student_portal/student_profile', compact('student'));
            $success_alert = Setting::getAlertSuccess(trans('student_portal/student_info.success_alert', ['name' => $name]));
        } else {
            $student = $students->first();
            $student_details = View::make('/student_portal/student_checker', compact('student'));
            $success_alert = Setting::getAlertSuccess(trans('student_portal/student_info.student_checker', ['name' => $name]));

        }

        return view('student_portal.student_info', compact('student_details', 'success_alert'));


    }

    /**
     * Get a student information by his father's phone number
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getStudentInformationByPhone(Request $request)
    {
        $this->validate($request, ['father-phone' => 'required', 'name' => 'required', 'program' => 'required']);

        $phone = $request->get('father-phone');
        $name = $request->get('name');
        $programCode = $request->get('program');
        $student = Student::getStudentByParentPhone($name, $phone, $programCode);

        if (empty($student)) {
            $failure_alert = '<div class="alert alert-warning">' . trans('student_portal/student_info.empty_student_p', ['phone' => $phone]) . '</div>';
            return redirect()->back()->with(['status' => $failure_alert]);
        }
        $student_details = View::make('/student_portal/student_profile', compact('student'));
        $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('student_portal/student_info.success_alert', ['name' => $name]) . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button></div>';

        return view('student_portal.student_info', compact('student_details', 'success_alert'));

    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function setLocale()
    {
        $locale = Input::get('change-locale');
        $result_portal = trans('settings/routes.result_portal');


        cache()->set('n_language', $locale);
        return redirect()->back();
    }
}
