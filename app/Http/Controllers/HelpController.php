<?php

namespace App\Http\Controllers;

use App\Setting;
use Illuminate\Http\Request;
use Exception;
use App\Mail\MailSupport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class HelpController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showHelpPage()
    {
        return view('help.help');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendEmail(Request $request)
    {

        $this->validate($request,
            ['title' => 'required', 'message' => 'required','contact' => 'required']
        );
        try {
            $mailContent = $request->all();
            if(Auth::check()){
                $mailContent['sender'] = trans('help/help.admin');
            }else{
                $mailContent['sender'] = trans('help/help.student');
            }

            Mail::to(trans('settings/setting.vims_email'))->send(new MailSupport($mailContent));
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('help/help.mail_success'))]);

        } catch (Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('help/help.mail_failure'))]);
        }
    }
}
