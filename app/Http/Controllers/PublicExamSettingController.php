<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class PublicExamSettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.public_exams');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showManagePublicExamsSettingPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();

        $examSettings = Setting::getPublicExamSettingBySectionCode($sectionCode, $academicYear);
        if ($examSettings->isEmpty()) {
            $exam_settings = '';
        } else {
            $exam_settings = View::make('result_management.public_es_table', compact('examSettings'));
        }
        return view('result_management.public_exams_setting', compact('exam_settings'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setPublicExamsParameters(Request $request)
    {
        $this->validate($request, ['center-no' => 'required',
            'exam-pdf' => 'required',
            'program-code' => 'required'
        ]);
        //get user section code
        $sectionCode = Auth::user()->sections_section_code;

        if ($request->file('exam-pdf')->isValid()) {
            try {
                $file = $request->file('exam-pdf');
                if ($file->getClientOriginalExtension() != 'pdf') {
                    $pdf_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('result_management/manage_public_exams.pdf_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                    return redirect()->back()->with(['status' => $pdf_validity_alert]);
                }

                $programCode = $request->get('program-code');
                $centerNo = $request->get('center-no');
                $academicYear = Setting::getAcademicYear();

                $exam_name = strtok(Setting::getAcademicYear(),'/') . '_' . $programCode . '_' . time() . '.' . $file->getClientOriginalExtension();
                $request->file('exam-pdf')->move(public_path('public_exam'), $exam_name);
                $exam_file_path = 'public_exam/' . $exam_name;

                //update the exam setting if it exist else create it
                if (Setting::checkPublicExamExistance($programCode, $sectionCode, $academicYear)) {
                    $currentSetting = Setting::getPublicExamSetting($programCode, $sectionCode, $academicYear);
                    //delete old exam file
                    unlink(public_path($currentSetting->exam_file_path));
                    Setting::updatePublicExamSetting($programCode, $sectionCode, $academicYear, $centerNo, $exam_file_path);

                } else {
                    Setting::createPublicExamSetting($programCode, $sectionCode, $academicYear, $centerNo, $exam_file_path);
                }

            } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                $pdf_validity_alert = '<div class="alert alert-dismissible alert-danger">' . trans('result_management/manage_public_exams.pdf_validity_text') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

                return redirect()->back()->with(['status' => $pdf_validity_alert]);

            }
        }
        $success_alert = '<div class="alert alert-dismissible alert-success">' . trans('result_management/manage_public_exams.success_alert') . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                   <span aria-hidden="true">&times;</span>
                                </button></div>';

        return redirect()->back()->with(['status' => $success_alert]);

    }
}
