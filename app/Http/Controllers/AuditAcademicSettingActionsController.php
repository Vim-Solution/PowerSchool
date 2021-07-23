<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class AuditAcademicSettingActionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.audit_setting_actions');
        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAuditAcademicSettingActionPage()
    {

        $activity_list = '';
        return view('auditing_management.audit_setting_actions', compact('activity_list'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAuditActivities(Request $request)
    {
        $this->validate($request, ['academic-year' => 'required']);
        $data = $request->all();
        $academic_year = $data['academic-year'];
        try {
            $activities = Setting::getAcademicSettingActionsForAuditing($academic_year);
            $sn = 1;
            $activity_list = View::make('auditing_management/audit_setting_actions_table', compact('academic_year', 'activities', 'sn'));
            return view('auditing_management.audit_setting_actions', compact('activity_list'));
        } catch (\Exception $exception) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('auditing_management/audit_setting_actions.server_error'))]);
        }
    }
}
