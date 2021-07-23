<?php

namespace App\Http\Controllers;

use App\Setting;
use App\Sequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class AuditManageSequenceActionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.audit_sequence_actions');
        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAuditSequenceActionPage()
    {

        $activity_list = '';
        return view('auditing_management.audit_sequence_actions', compact('activity_list'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function getAuditActivities(Request $request)
    {
        $this->validate($request, ['academic-year' => 'required', 'action' => 'required']);
        $data = $request->all();
        $academic_year = $data['academic-year'];
        try {

            $action = $data['action'];

            if ($data['action'] == 0) {
                $date = Setting::getToAndFromAcademicYearDate($academic_year);
                $activities = Sequence::whereBetween(trans('database/table.created_at'),[$date['from'],$date['to']])->where(trans('database/table.sections_section_code'),Auth::user()->sections_section_code)->get();
            } else {
                $activities = Sequence::getSequenceActionsForAuditing($data['action'], $academic_year);
            }

            $sn = 1;
            $activity_list = View::make('auditing_management/audit_sequence_actions_table', compact('academic_year', 'activities', 'sn', 'action'));
            return view('auditing_management.audit_sequence_actions', compact('activity_list'));
        } catch (\Exception $exception) {

            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('auditing_management/audit_sequence_actions.server_error'))]);
        }
    }
}
