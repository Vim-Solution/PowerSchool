<?php

namespace App\Http\Controllers;

use App\AcademicLevel;
use App\Encrypter;
use App\Notifications\SMSNotification;
use App\Setting;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Nexmo\Laravel\Facade\Nexmo;

class SMSNotificationController extends Controller
{
    /**
     * ManageSubjectSeriesController constructor.
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.sms_notifications');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSMSNotificationPage()
    {
        if (!empty(Session::get("sms_classCode"))) {
            $classCode = Session::get('sms_classCode');
            $academicYear = Setting::getAcademicYear();
            $sectionCode = Auth::user()->sections_section_code;
            $sms_notification_records = AcademicLevel::getStudentsByClassCode($classCode, $academicYear, $sectionCode);
            $className = AcademicLevel::getClassNameByCode($classCode);
            $sms_notification_list = View::make('/result_management/sms_notification_table', compact('academicYear', 'sms_notification_records', 'className', 'classCode'));
        } else {
            $sms_notification_list = '';
        }
        return view('result_management.sms_notification', compact('sms_notification_list'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getClassNotificationPage(Request $request)
    {
        $this->validate($request, ['class-code' => 'required']);

        $classCode = $request->get('class-code');
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();

        $sms_notification_records = AcademicLevel::getStudentsByClassCode($classCode, $academicYear, $sectionCode);
        $className = AcademicLevel::getClassNameByCode($classCode);
        $sms_notification_list = View::make('/result_management/sms_notification_table', compact('academicYear', 'sms_notification_records', 'className', 'classCode'));
        return view('result_management.sms_notification', compact('sms_notification_list'));
    }

    /**
     * @param $sid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyStudent($mat)
    {

        $matricule = Encrypter::decrypt($mat);
        $student = Student::getStudentByMatricule($matricule);
        if (empty($student)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.not_a_student'))]);
        }
        if (Student::isSuspended($matricule)) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.suspension', ['name' => $student->full_name]))]);
        }
        try {
            $academicYear = Setting::getAcademicYear();
            $sequence = Setting::getSequence();
            $class = AcademicLevel::getClassByCode(Student::getStudentClassCodeByMatricule($student->matricule));
            $secreteCode = Student::getPortalSecretCodeByMatricule($student->matricule);
            $annualAvarage = 10;
            $sequenceAverage = 10;

            if (Session::has('sms_classCode')) {
                Session::forget('sms_classCode');
            }
            Session::put('sms_classCode', $class->class_code);

            if (Student::isSMSDelivered($student->student_id, $sequence->sequence_id, $academicYear)) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.notification_sent'))]);
            }
            $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
            $promotionSentinel = 0;
            if (!empty($class)) {
                $nextClass = Student::getStudentNextClassCodeByMatricule($student->matricule, $nextAcademicYear);
                if (!empty($nextClass)) {
                    if ($nextClass->class_id == ($class->class_id + 1)) {
                        $promotionSentinel = 1;
                    } else {
                        $promotionSentinel = 2;
                    }
                }
            } else {
                $nextClassName = trans('result_management/auto_promotion.university');
            }

            if (empty($nextClass)) {
                $nextClassName = trans('result_management/sms_notification.university');
            } else {
                $nextClassName = $nextClass->class_name;
            }

            $status = $this->nexmoSMS($student, $sequence, $academicYear, $class, $secreteCode, $annualAvarage, $sequenceAverage, $nextAcademicYear, $promotionSentinel, $nextClassName);

            if ($status == 0) {
                return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.notification_sent_failure', ['name' => $student->full_name]))]);
            } else {
                $smsCount = Student::getSMSDeliveryRecordCount($student->student_id, $sequence->sequence_id, $academicYear);
                $smsRecord = [
                    trans('database/table.students_student_id') => $student->student_id,
                    trans('database/table.sequences_sequence_id') => $sequence->sequence_id,
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.sms_count') => ($smsCount + 1)
                ];
                Student::saveSMSRecord($smsRecord);
                return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertSuccess(trans('result_management/sms_notification.notification_sent_success', ['name' => $student->full_name]))]);
            }
       } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.notification_sent_failure', ['name' => $student->full_name]))]);
        }

    }

    public function classSMSNotification($cCode)
    {
        $classCode = Encrypter::decrypt($cCode);
        $class = AcademicLevel::getClassByCode($classCode);
        if (empty($class)) {
            return \redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.empty_class_alert'))]);
        }

        if (Session::has('sms_classCode')) {
            Session::forget('sms_classCode');
        }
        Session::put('sms_classCode', Encrypter::decrypt($cCode));

        try {
            $academicYear = Setting::getAcademicYear();
            $sequence = Setting::getSequence();
            $sectionCode = Auth::user()->sections_section_code;
            $students = AcademicLevel::getStudentsByClassCode($classCode, $academicYear, $sectionCode);

            if ($students->isEmpty()) {
                return \redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.no_student_alert'))]);
            }
            $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
            $classes = AcademicLevel::where(trans('database/table.sections_section_code'), $sectionCode)->get();
            $secrete_codes = Student::getPortalSecretCodes();
            $class_has_students = AcademicLevel::getClassStudentsPivot($sectionCode);

            $smsRecords = collect([]);
            $sms_failure = 0;
            $sms_success = 0;

            foreach ($students as $student) {
                $promotionSentinel = 0;
                $secreteCode = $this->getPortalSecretCodeByMatricule($secrete_codes, $student->matricule);
                $annualAvarage = 10;
                $sequenceAverage = 10;

                if (!empty($class)) {
                    $nextClass = $this->getStudentNextClassCodeByMatricule($class_has_students, $classes, $student->matricule, $nextAcademicYear);
                    if (!empty($nextClass)) {
                        if ($nextClass->class_id == ($class->class_id + 1)) {
                            $promotionSentinel = 1;
                        } else {
                            $promotionSentinel = 2;
                        }
                    }
                } else {
                    $nextClassName = trans('result_management/auto_promotion.university');
                }

                if (empty($nextClass)) {
                    $nextClassName = trans('result_management/sms_notification.university');
                } else {
                    $nextClassName = $nextClass->class_name;
                }

                $status = $this->nexmoSMS($student, $sequence, $academicYear, $class, $secreteCode, $annualAvarage, $sequenceAverage, $nextAcademicYear, $promotionSentinel, $nextClassName);
                if ($status == 0) {
                    $sms_failure++;
                } else {
                    $smsCount = Student::getSMSDeliveryRecordCount($student->student_id, $sequence->sequence_id, $academicYear);
                    $smsRecords = $smsRecords->push([
                        trans('database/table.students_student_id') => $student->student_id,
                        trans('database/table.sequences_sequence_id') => $sequence->sequence_id,
                        trans('database/table.academic_year') => $academicYear,
                        trans('database/table.sms_count') => ($smsCount + 1)
                    ]);
                    $sms_success++;
                }
            }
            Student::saveSMSRecord($smsRecords->toArray());

            if ($sms_success == 0) {
                return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.bulk_notification_failure', ['class' => AcademicLevel::getClassNameByCode(Encrypter::decrypt($cCode)), 'success' => $sms_success, 'failure' => $sms_failure]))]);
            } elseif ($sms_failure == 0) {
                return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertSuccess(trans('result_management/sms_notification.bulk_notification_success', ['class' => AcademicLevel::getClassNameByCode(Encrypter::decrypt($cCode)), 'success' => $sms_success, 'failure' => $sms_failure]))]);

            } else {
                return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertInfo(trans('result_management/sms_notification.bulk_notification_success_p', ['class' => AcademicLevel::getClassNameByCode(Encrypter::decrypt($cCode)), 'success' => $sms_success, 'failure' => $sms_failure]))]);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.bulk_notification_error'))]);
        }
    }

    /**
     * @param $class_has_students
     * @param $classes
     * @param $matricule
     * @param $academic_year
     * @return mixed
     */
    private function getStudentNextClassCodeByMatricule($class_has_students, $classes, $matricule, $academic_year)
    {
        $res = $class_has_students->where(trans('database/table.academic_year'), $academic_year)
            ->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($res)) {
            return $res;
        }

        $class = $classes->where(trans('database/table.class_code'), $res->classes_class_code)->first();
        return $class;
    }


    /**
     * @param $student_accounts
     * @param $matricule
     * @return string
     */
    private function getPortalSecretCodeByMatricule($student_accounts, $matricule)
    {
        $secret_code = $student_accounts->where(trans('database/table.matricule'), $matricule)
            ->first();
        if (empty($secret_code)) {
            return null;
        }
        return Encrypter::decrypt($secret_code->secret_code);
    }

    public function sendGeneralSMSNotification()
    {
        try {
        $academicYear = Setting::getAcademicYear();
        $sequence = Setting::getSequence();
        $sectionCode = Auth::user()->sections_section_code;
        $students = AcademicLevel::getSchoolClassStudentRecords($sectionCode, $academicYear);

        if ($students->isEmpty()) {
            return \redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.no_student_alert'))]);
        }
        $nextAcademicYear = Setting::getNextAcademicYear($academicYear);
        $classes = AcademicLevel::where(trans('database/table.sections_section_code'), $sectionCode)->get();
        $secrete_codes = Student::getPortalSecretCodes();
        $class_has_students = AcademicLevel::getClassStudentsPivot($sectionCode);

        $smsRecords = collect([]);
        $sms_failure = 0;
        $sms_success = 0;

        foreach ($students as $student) {
            $promotionSentinel = 0;
            $secreteCode = $this->getPortalSecretCodeByMatricule($secrete_codes, $student->matricule);
            $annualAvarage = 10;
            $sequenceAverage = 10;
            $class_has_student = $class_has_students->where(trans('database/table.academic_year'), $academicYear)
                ->first();
            $class_has_students->where(trans('database/table.academic_year'), $academicYear)
                ->where(trans('database/table.matricule'), $student->matricule)
                ->first();
            if (empty($class_has_student)) {
                $class = null;
            } else {
                $class = $classes->where(trans('database/table.class_code'), $class_has_student->classes_class_code)->first();
            }

            if (!empty($class)) {
                $nextClass = $this->getStudentNextClassCodeByMatricule($class_has_students, $classes, $student->matricule, $nextAcademicYear);
                if (!empty($nextClass)) {
                    if ($nextClass->class_id == ($class->class_id + 1)) {
                        $promotionSentinel = 1;
                    } else {
                        $promotionSentinel = 2;
                    }
                }
            } else {
                $nextClassName = trans('result_management/auto_promotion.university');
            }

            if (empty($nextClass)) {
                $nextClassName = trans('result_management/sms_notification.university');
            } else {
                $nextClassName = $nextClass->class_name;
            }
            $status = $this->nexmoSMS($student, $sequence, $academicYear, $class, $secreteCode, $annualAvarage, $sequenceAverage, $nextAcademicYear, $promotionSentinel, $nextClassName);
            if ($status == 0) {
                $sms_failure++;
            } else {
                $smsCount = Student::getSMSDeliveryRecordCount($student->student_id, $sequence->sequence_id, $academicYear);
                $smsRecords = $smsRecords->push([
                    trans('database/table.students_student_id') => $student->student_id,
                    trans('database/table.sequences_sequence_id') => $sequence->sequence_id,
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.sms_count') => ($smsCount + 1)
                ]);
                $sms_success++;
            }
        }
        Student::saveSMSRecord($smsRecords->toArray());

        if ($sms_success == 0) {
            return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.bulk_notification_failure_g', ['success' => $sms_success, 'failure' => $sms_failure]))]);
        } elseif ($sms_failure == 0) {
            return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertSuccess(trans('result_management/sms_notification.bulk_notification_success_g', ['success' => $sms_success, 'failure' => $sms_failure]))]);

        } else {
            return Redirect::to(trans('settings/routes.sms_notifications'))->with(['status' => Setting::getAlertInfo(trans('result_management/sms_notification.bulk_notification_success_p_g', ['success' => $sms_success, 'failure' => $sms_failure]))]);
        }

        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('result_management/sms_notification.bulk_notification_error'))]);
        }
    }

    /**
     * @param $student
     * @param $sequence
     * @param $academicYear
     * @param $class
     * @param $secreteCode
     * @param $annualAvarage
     * @param $sequenceAverage
     * @param $nextAcademicYear
     * @param $promotionSentinel
     * @param $nextClassName
     * @return int
     */
    private function nexmoSMS($student, $sequence, $academicYear, $class, $secreteCode, $annualAvarage, $sequenceAverage, $nextAcademicYear, $promotionSentinel, $nextClassName)
    {
       // try {
            if ($promotionSentinel == 1) {
                $promotionMessage = trans('result_management/sms_notification.class_promotion', ['class' => $nextClassName, 'year' => $nextAcademicYear]);
            } elseif ($promotionSentinel == 2) {
                $promotionMessage = trans('result_management/sms_notification.class_repeat', ['class' => $class->class_name, 'year' => $nextAcademicYear]);
            } elseif (($annualAvarage < $class->annual_promotion_average)) {
                $promotionMessage = trans('result_management/sms_notification.class_promotion', ['class' => $nextClassName, 'year' => $nextAcademicYear]);
            } else {
                $promotionMessage = trans('result_management/sms_notification.class_repeat', ['class' => $class->class_name, 'year' => $nextAcademicYear]);
            }

            if (($sequence->seqeunce_id % 6) == 0) {
                $message = trans('result_management/sms_notification.annual_result_alert', ['sequence' => $sequence->sequence_name, 'year' => $academicYear,
                    'name' => $student->full_name, 'matricule' => $student->matricule, 'secret' => $secreteCode,
                    'promotion' => $promotionMessage,
                    's_average' => $sequenceAverage, 'a_average' => $annualAvarage, 'link' => trans('settings/setting.power_school_link'),
                    'school' => trans('settings/setting.school_name')]);
            } else {
                $message = trans('result_management/sms_notification.annual_result_alert', ['sequence' => $sequence->sequence_name, 'year' => $academicYear,
                    'name' => $student->full_name, 'matricule' => $student->matricule, 'secret' => $secreteCode,
                    'promotion' => $promotionMessage,
                    's_average' => $sequenceAverage, 'link' => trans('settings/setting.power_school_link'), 'school' => trans('settings/setting.school_name')]);
            }
            Notification::send($student, new SMSNotification($message));
//        } catch (\Exception $e) {
//            return 0;
//        }
        return 1;
    }

}
