<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'notification_id';


    /**
     * get all active notifications of the user
     * @param $userid
     * @return string
     */
    public static function getActiveNotificationsList($userId)
    {
        $res = '';
        $acadmic_year = Setting::getAcademicYear();
        $activeNotifications = self::where(trans('database/table.users_user_id'), $userId)->where(trans('database/table.state'), 0)->where(trans('database/table.academic_year'), $acadmic_year)->get();

        if ($activeNotifications->isEmpty()) {
            $res .= '<a href="" data-toggle="dropdown"><i class="zmdi zmdi-notifications"></i></a>'
                . '<div class="dropdown-menu dropdown-menu-right dropdown-menu--block">'
                . ' <div class="listview listview--hover"><div class="listview__header">' . trans('general.notifications') . '<div class="actions">'
                . '<a href="" class="actions__item zmdi zmdi-check-all"  data-ma-action="notifications-clear"></a>  </div> </div>';

        } else {
            $res .= '<a href="" data-toggle="dropdown" class="top-nav__notify"><i class="zmdi zmdi-notifications"></i></a>'
                . '<div class="dropdown-menu dropdown-menu-right dropdown-menu--block">'
                . ' <div class="listview listview--hover"><div class="listview__header">' . trans('general.notifications') . '<div class="actions">'
                . '<a href="" class="actions__item zmdi zmdi-check-all"  data-ma-action="notifications-clear"></a>  </div> </div>';
        }

        $res .= '<div class="listview__scroll scrollbar-inner" >';
        foreach ($activeNotifications as $activeNotification) {
            $user = User::find($activeNotification->notifier_id);
            $res .= '<a href = "' . trans('settings/routes.notifications') . '/' . Encrypter::encrypt($activeNotification->notification_id) . '" class="listview__item" >'
                . '<div class="c-ewangclarks" style="content:\'\';width:10px;height:10px;color:#FFF;border-radius:50%;position: relative;top: 15px;-webkit-animation-name:flash;animation-name:flash;-webkit-animation-duration:2s;animation-duration:2s;-webkit-animation-fill-mode:both;animation-fill-mode:both;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite"></div><img src = "' . asset($user->profile) . '" class="listview__img" style="padding-left: 5px;" alt = "" >'
                . '<div class="listview__content" ><div class="listview__heading" >'
                . ucwords(strtolower($user->full_name)) . '</div ><p >' . $activeNotification->notification_subject . '</p ></div >'
                . '</a >';
        }
        $res .= '</div ><div class="p-1" ></div ></div ></div>';

        return $res;
    }

    /**
     * Get all user active notifications
     * @param $userId
     * @return mixed
     */
    public static function getActiveNotifications($userId)
    {
        $acadmic_year = Setting::getAcademicYear();
        $activeNotifications = self::where(trans('database/table.users_user_id'), $userId)->where(trans('database/table.state'), 0)->where(trans('database/table.academic_year'), $acadmic_year)->get();
        return $activeNotifications;
    }

    /**
     * Get all user  notifications
     * @param $userId
     * @return mixed
     */
    public static function getAllNotifications($userId)
    {
        $acadmic_year = Setting::getAcademicYear();
        $activeNotifications = self::where(trans('database/table.users_user_id'), $userId)->where(trans('database/table.academic_year'), $acadmic_year)->get();
        return $activeNotifications;
    }


    /**
     * @param $notifications
     */
  public static function saveNotifications($notifications){
        DB::table(trans('database/table.notifications'))
            ->insert($notifications);
  }
}
