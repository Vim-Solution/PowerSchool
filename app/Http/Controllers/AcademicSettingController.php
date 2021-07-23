<?php

namespace App\Http\Controllers;

use App\Sequence;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AcademicSettingController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.academic_setting');

        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * show the page for where the exam parameters are set
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAcademicSettingPage()
    {
        $sectionCode = Auth::user()->sections_section_code;
        $academicYear = Setting::getAcademicYear();

        $academicSettings = Setting::getAcademicSettingBySectionCode($sectionCode, $academicYear);
        if ($academicSettings->isEmpty()) {
            $academic_settings = '';
        } else {
            $academic_settings = View::make('academic_setting.academic_setting_table', compact('academicSettings'));
        }
        return view('academic_setting.academic_setting', compact('academic_settings'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setAcademicParameters(Request $request)
    {
        $this->validate($request, ['sequence-code' => 'required',
            'academic-year' => 'required', 'mark-submission-date' => 'required',
        ]);
        //get user section code
        $sectionCode = Auth::user()->sections_section_code;

        try {
            $academicYear = $request->get('academic-year');
            $sequenceId = Sequence::getSequenceIdByCode($request->get('sequence-code'));
            $mark_submission_date = $request->get('mark-submission-date');

            //update the academic setting if it exist else create it
            if (Setting::checkAcademicSettingExistance($sectionCode)) {
                Setting::updateAcademicSetting($sectionCode, $academicYear, $sequenceId, $mark_submission_date);
            } else {
                Setting::createAcademicSetting($sectionCode, $academicYear, $sequenceId, $mark_submission_date);
            }

            if (!Setting::academicYearExist($sectionCode, $academicYear)) {
                Setting::saveAcademicYear($sectionCode, $academicYear);
            }

            Setting::recordAcademicSettingActions(Sequence::getSequenceNameById($sequenceId), $academicYear);
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('settings/setting.ac_success'))]);

        } catch (Illuminate\Filesystem\FileNotFoundException $e) {

            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('settings/setting.ac_failure'))]);
        }

    }

}
