<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Notification;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('valid');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        //make a user inactive just before loging him out
        $user = User::find(Auth::user()->user_id);
        $user->active = 0;
        $user->save();
        //stores the last user visited url
        $lastUrl = 'last_url_' . $user->user_id;
        Session::forget($lastUrl);
        Session::put([$lastUrl => URL::previous()]);
        Auth::logout();
        return Redirect::to(trans('settings/routes.login'));
    }

    /*
     * start announcements actions
     */

    /**
     * Display informations about the notifiactions been read to the user
     *  alongside a list of all non read notifications
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showNotificationPage($id)
    {
        $userId = Auth::user()->user_id;
        $notificationId = Encrypter::decrypt($id);
        $resource['current_notification'] = '';
        $resource['active_notifications'] = '';
        try {
            $notification = Notification::find($notificationId);
            $notifier = User::find($notification->notifier_id);
            $notification->state = 1;
            $notification->save();

            $activeNotifications = Notification::getActiveNotifications($userId);

            $resource['current_notification'] = View::make('notifications.current_notification', compact('notification', 'notifier'));
            $resource['active_notifications'] = View::make('notifications.active_notifications', compact('activeNotifications', 'notifier'));

            return view('notifications.notification_open')->with($resource);
        } catch (Exception $e) {
            return Redirect::to(trans('settings/routes.home'));
        }

    }

    /**
     * Get all user notifications
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showAllNotifications()
    {
        $resource['notifications'] = '';
        $userId = Auth::user()->user_id;
        try {
            $notifications = Notification::getAllNotifications($userId);
            $read = $notifications->where(trans('database/table.state'), 1)->count();
            $unread = $notifications->where(trans('database/table.state'), 0)->count();


            $resource['notifications'] = View::make('notifications.notification_list', compact('notifications'));
            $resource['read'] = $read;
            $resource['unread'] = $unread;
            return view('notifications.notifications')->with($resource);
        } catch (Exception $e) {
            return Redirect::to(trans('settings/routes.home'));
        }
    }

    /**
     * Delete an announcement / notification
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteNotification($id)
    {
        $notificationId = Encrypter::decrypt($id);
        try {
            $notification = Notification::find($notificationId);
            $notification->delete();
        } catch (Exception $e) {
            return Redirect::to(trans('settings/routes.notifications'));
        }
        return Redirect::to(trans('settings/routes.notifications'))->with(['status' => trans('notifications/notification.deleted_success', ['subject' => $notification->notification_subject])]);
    }
    /*
     *  end of notificatications logic
     */

    /*
     * start change password actions
     */

    /**
     * load the change password view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showChangePasswordPage()
    {
        return view('auth.passwords.change_password');
    }

    /**
     * effect password change
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changePassword(Request $request)
    {
        //define change password validation conditions
        $this->validate($request,
            ['old-password' => 'required',
                'new-password' => 'required'
            ]);
        //request the data from the user
        $passwords = $request->all();
        $failure_alert = '<div class="alert alert-danger alert-dismissable">' . trans('passwords.change_password_failure') . '</div>';
        try {

            $user = User::find(Auth::user()->user_id);
            Auth::logout();
            $credentials = ['email' => $user->email, 'password' => $passwords['old-password']];
            //check if the old password matches the current user password and if it does perform change of password else return error message
            if (Auth::attempt($credentials)) {
                $user->password = (Hash::make($passwords['new-password']));
                $user->save();
                $success_alert = '<div class="alert alert-success alert-dismissable">' . trans('passwords.change_password_success') . '</div>';
                return redirect()->back()->with(['status' => $success_alert]);
            } else {
                Auth::login($user);
                return redirect()->back()->with(['status' => $failure_alert]);
            }

        } catch (Exception $e) {
            return redirect()->back()->with(['status' => $failure_alert]);
        }
    }
    /*
     * stop change password actions
     */


    /*
     * Change user profile
     */

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function changeProfile(Request $request)
    {

        $this->validate($request, ['profile-picture' => 'required']);
        $userId = Auth::user()->user_id;
        $current_profile = Auth::user()->profile;

        if ($request->file('profile-picture')->isValid()) {
           try {
                $file = $request->file('profile-picture');
                $file_extension = $file->getClientOriginalExtension();
                if ($file_extension == 'png' || $file_extension == 'jpg' || $file_extension == 'jpeg') {

                    $profile = $userId . '_' . time() . '.' . $file_extension;
                    $request->file('profile-picture')->move(public_path('images/avatars'), $profile);
                    $profile_path = 'images/avatars/' . $profile;

                    $user = User::find($userId);
                    $user->profile = $profile_path;
                    $user->save();

                    if ($current_profile != trans('img/img.default_profile')) {
                        unlink(public_path($current_profile));
                    }
                    return \redirect()->back();
                }
           } catch (Illuminate\Filesystem\FileNotFoundException $e) {
                return \redirect()->back();
            }
        } else {
           return \redirect()->back();
        }
    }
}
