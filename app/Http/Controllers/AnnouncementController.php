<?php

namespace App\Http\Controllers;

use App\Encrypter;
use App\Notification;
use App\Setting;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AnnouncementController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $authorization = 'authorize:' . trans('settings/routes.announcement');
        $this->middleware('auth');
        $this->middleware('valid');
        $this->middleware($authorization);
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showAnnouncementPage()
    {
        return view('announcement.announcement');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function sendAnnouncement(Request $request)
    {
        $this->validate($request, ['subject' => 'required', 'body' => 'required', 'staff' => 'required']);

        try {
            $announcement = $request->all();
            $staff = $announcement['staff'];
            $academicYear = Setting::getAcademicYear();
            $section_code = Auth::user()->sections_section_code;
            $userId = Auth::user()->user_id;


            if (count($staff) == 1 && $staff[0] == 0) {
                $staf = User::getValidUsers($section_code);
                $staffIds = $staf->pluck(trans('database/table.user_id'))->toArray();
            } else {
                $staffIds = $staff;
            }
            $resource = collect([]);

            //create same nofications for each lecturer taking this course
            foreach ($staffIds as $staffId) {
                $resource = $resource->push([trans('database/table.notification_subject') => $announcement['subject'],
                    trans('database/table.notification_body') => $announcement['body'],
                    trans('database/table.users_user_id') => $staffId,
                    trans('database/table.notifier_id') => $userId,
                    trans('database/table.academic_year') => $academicYear,
                    trans('database/table.state') => 0
                ]);
            }

            Notification::saveNotifications($resource->toArray());
            return redirect()->back()->with(['status' => Setting::getAlertSuccess(trans('announcement/announcement.announcement_success'))]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => Setting::getAlertFailure(trans('announcement/announcement.announcement_failure'))]);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showAnnouncementList(){
        $academicYear = Setting::getAcademicYear();
        try {
            $announcements = Notification::where(trans('database/table.academic_year'),$academicYear)->withTrashed()->get();
            $read = $announcements->where(trans('database/table.state'), 1)->count();
            $unread = $announcements->where(trans('database/table.state'), 0)->count();


            $resource['announcements'] = View::make('announcement.announcement_list', compact('announcements'));
            $resource['read'] = $read;
            $resource['unread'] = $unread;
            return view('announcement.announcement_home')->with($resource);
       } catch (Exception $e) {
            return Redirect::to(trans('settings/routes.announcement'));
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showAnnouncement($id){
        $userId = Auth::user()->user_id;
        $announcementId = Encrypter::decrypt($id);
        $resource['current_announcement'] = '';
        $resource['active_announcement'] = '';
        try {
            $announcement = Notification::where(trans('database/table.notification_id'),$announcementId)->withTrashed()->first();
            $notifier = User::find($announcement->notifier_id);
            $announcement->state = 1;
            $announcement->save();

            $academicYear = Setting::getAcademicYear();
            $activeAnnouncements = Notification::where(trans('database/table.academic_year'),$academicYear)->where(trans('database/table.state'),0)->withTrashed()->get();

            $resource['current_announcement'] = View::make('announcement.focus_announcement', compact('announcement', 'notifier'));
            $resource['active_announcements'] = View::make('announcement.active_announcement', compact('activeAnnouncements', 'notifier'));

            return view('announcement.announcement_open')->with($resource);
        } catch (Exception $e) {
            return Redirect::to(trans('settings/routes.announcement_list'));
        }

    }
}
