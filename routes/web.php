<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});

Auth::routes();

/**
 * Requesting the routes from a language file
 * and storing them in vairables to be used in
 * according to the laravel convention
 */
$home = trans('settings/routes.home');
$profile = trans('settings/routes.change_profile');
$logout = trans('settings/routes.logout');
$change_locale = trans('settings/routes.change_locale');
$s_change_locale = trans('settings/routes.s_change_locale');

$id = '/{id}';

//handle notifications actions
$notifications = trans('settings/routes.notifications');
$notification = trans('settings/routes.notifications') . $id;
$delete_notification = trans('settings/routes.delete_notification') . $id;

//handles change password actions
$change_password = trans('settings/routes.change_password');
$search_user = trans('settings/routes.search_user');
/*
 * Account management lang routes
 */
$add_user = trans('settings/routes.add_user');
$reset_user = trans('settings/routes.reset_user');
$suspend_user = trans('settings/routes.suspend_user');
$reset_password = trans('settings/routes.password_reset');
$edit_user = trans('settings/routes.edit_user');
$save = trans('settings/routes.save');
$assign_role = trans('settings/routes.assign_role');
$user_list = trans('settings/routes.user_list');

/*
 * Handles manage settings
 */
$manage_role = trans('settings/routes.manage_role');
$delete = trans('settings/routes.delete');
$edit = trans('settings/routes.edit');
$manage_access = trans('settings/routes.manage_privilege');
$authorizations = trans('settings/routes.authorizations');
$matricule_setting = trans('settings/routes.matricule_setting');
$academic_setting = trans('settings/routes.academic_setting');
$manage_sequence = trans('settings/routes.manage_sequence');
$manage_term = trans('settings/routes.manage_term');
/*
 * Handles Student Management,public exams
 */

$batch_student_upload = trans('settings/routes.batch_student_upload');
$batch_student_upload_download = trans('settings/routes.download_student_list');
$add_student = trans('settings/routes.add_student');
$edit_student = trans('settings/routes.edit_student');
$search_student = trans('settings/routes.search_student');
$id_card = trans('settings/routes.id_card');



/*
 * Handles result management
 */
$public_exams_setting = trans('settings/routes.public_exams');
$download_public_exam = trans('settings/routes.download_public_exam');
$publish_result = trans('/settings/routes.publish_result');
$publish = trans('/settings/routes.publish');
$notify = trans('settings/routes.notify');

/*
 * Report card management
 */
$print_report_card = trans('settings/routes.print_report_card');
$print_class_report_card = trans('settings/routes.print_class_report_card');
$download_student_report_card = trans('settings/routes.download_student_report_card');
$download_class_report_card = trans('settings/routes.download_class_report_card');
$promote_student = trans('settings/routes.promote_student');
$repeat_class = trans('settings/routes.repeat_class');
$class_auto_promote = trans('settings/routes.auto_promote');


/*
 * CLass promotions
 */
$class_promotion = trans('settings/routes.manage_class_promotion');
$batch_promotion = trans('settings/routes.batch_promotion');
$class_promotion_average = trans('settings/routes.manage_class_promotion_average');

$student_portal_access = trans('settings/routes.manage_student_portal_access');
$suspend_student = trans('settings/routes.student_suspension');
$revert_student_suspension = trans('settings/routes.revert_student_suspension');
$generate_secret = trans('settings/routes.generate_secrete');

$manage_teacher_subjects = trans('settings/routes.manage_teacher_subject');
$manage_teacher_subjects_a = trans('settings/routes.manage_teacher_subject_a');
$load_subjects = trans('settings/routes.load_subjects');


/*
 * Handles series management
 */
$manage_series = trans('settings/routes.manage_series');
$manage_class = trans('settings/routes.manage_class');
$manage_student_series = trans('settings/routes.manage_student_series');
$manage_subject_series = trans('settings/routes.manage_subject_series');
$get_student = trans('settings/routes.get_student');
$get_subject = trans('settings/routes.get_subject');
$download_student_id_card = trans('settings/routes.download_id_card');
/*
 * Handles Subject Management,public exams
 */

$batch_subject_upload = trans('settings/routes.batch_subject_upload');
$add_subject = trans('settings/routes.add_subject');
$edit_subject = trans('settings/routes.edit_subject');
$save_subject = trans('settings/routes.save_subject');

$get_class_list = trans('settings/routes.get_class_list');
$series_data_upload = trans('settings/routes.series_data_upload');

$view_subject = trans('settings/routes.view_subject');
$delete_subject = trans('settings/routes.delete_subject');

$search_subject = trans('settings/routes.search_subject');

/*
 * Handles Student Portal
 */

$result_portal = trans('settings/routes.result_portal');
$student_info = trans('settings/routes.student_info');
$student_info_phone = trans('settings/routes.student_info_phone');;


/*
 * Handles Subject Management
 */
$manage_test = trans('settings/routes.manage_subject_test');
$create = trans('settings/routes.create');
$mark_entry = trans('settings/routes.mark_entry');
$csv_mark_entry = trans('settings/routes.csv_mark_entry');
$generate_student_list = trans('settings/routes.generate_student_list');
$submit_marks = trans('settings/routes.submit_mark');
$view_subject_result = trans('settings/routes.result_list');
$download_result_t = trans('settings/routes.download_result_t');
$download_result_s = trans('settings/routes.download_result_s');


/*
 * Announcement
 */
$announcement = trans('settings/routes.announcement');
$announcement_list = trans('settings/routes.announcement_list');

/*
 * SMS notifications
 */
$sms_notifications = trans('settings/routes.sms_notifications');
$student_sms_notifications = trans('settings/routes.s_sms_notifications');
$class_sms_notifications = trans('settings/routes.c_sms_notifications');
$general_sms_notifications = trans('settings/routes.g_sms_notifications');


/*
 * Help Management
 */

$help = trans('settings/routes.help');

/*
 *
 * Manage Auditing
 */
$audit_user_actions = trans('settings/routes.audit_user_actions');
$audit_subject_actions = trans('settings/routes.audit_subject_actions');
$audit_student_actions = trans('settings/routes.audit_student_actions');
$audit_sequence_actions = trans('settings/routes.audit_sequence_actions');
$audit_term_actions = trans('settings/routes.audit_term_actions');
$audit_class_actions = trans('settings/routes.audit_class_actions');
$audit_setting_actions = trans('settings/routes.audit_setting_actions');

/**
 * Using the requested routes in the laravel
 * get,post,touch,put methods
 */
/*
 * Manage Announcement and  default home actions
 */
Route::get($home, 'HomeController@index')->name('home');
Route::post($home, 'HomeController@changeProfile')->name('profile');
Route::get($logout, 'HomeController@logout')->name('logout');

Route::get('/', 'Auth\LoginController@index');
Route::get($change_locale, 'Auth\LoginController@setLocale');

//handle notifications actions
Route::get($notifications, 'HomeController@showAllNotifications');
Route::get($notification, 'HomeController@showNotificationPage');
Route::get($notification, 'HomeController@showNotificationPage');
Route::get($delete_notification, 'HomeController@deleteNotification');

//handle change password actions
Route::get($change_password, 'HomeController@showChangePasswordPage');
Route::post($change_password, 'HomeController@changePassword');

Route::get($assign_role, 'AccountSettingController@showAccountSettingPage');
Route::get($assign_role . $authorizations, 'AccountSettingController@getRoleUsers');
Route::post($assign_role, 'AccountSettingController@updateUsersRole');

/**
 * Account management routes
 */

Route::get($add_user, 'AddUserController@showAddUserPage');
Route::post($add_user, 'AddUserController@addUser');

Route::get($reset_user, 'ResetUserController@showResetUserPage');
Route::post($reset_user, 'ResetUserController@searchUser');
Route::get($reset_user . '/{userId}', 'ResetUserController@resetUser');

Route::get($suspend_user, 'SuspendUserController@showSuspendUserPage');
Route::post($suspend_user, 'SuspendUserController@searchUser');
Route::get($suspend_user . '/{userId}', 'SuspendUserController@suspendUser');

Route::get($reset_password, 'ResetPassWordController@showPasswordResetPage');
Route::post($reset_password, 'ResetPassWordController@searchUser');
Route::get($reset_password . '/{userId}', 'ResetPassWordController@resetUserPassword');

Route::get($edit_user, 'EditUserController@showEditUserPage');
Route::post($edit_user, 'EditUserController@searchUser');

Route::get($edit_user . $save, 'EditUserController@editUser');
Route::get($user_list, 'ViewUserListController@showUserListPage');

/**
 * Settings
 */

Route::get($manage_role, 'ManageRoleController@showManageRolePage');
Route::post($manage_role, 'ManageRoleController@createRole');
Route::get($manage_role . $delete . $id, 'ManageRoleController@deleteRole');
Route::get($manage_role . $edit . $id, 'ManageRoleController@showEditRolePage');
Route::post($manage_role . $edit . $id, 'ManageRoleController@editRole');

Route::get($manage_access, 'ManageAccessController@showManageAccessPage');
Route::get($manage_access . $authorizations, 'ManageAccessController@getFunctionalitiesByRoleId');
Route::post($manage_access, 'ManageAccessController@updateAuthorization');


Route::get($manage_class, 'ManageClassController@showAddClassPage');
Route::post($manage_class, 'ManageClassController@addClass');
Route::get($manage_class . $delete . $id, 'ManageClassController@deleteClass');
Route::get($manage_class . $edit . $id, 'ManageClassController@editClass');


Route::get($academic_setting, 'AcademicSettingController@showAcademicSettingPage');
Route::post($academic_setting, 'AcademicSettingController@setAcademicParameters');
Route::get($matricule_setting, 'MatriculeSettingController@showMatriculeSettingPage');
Route::post($matricule_setting, 'MatriculeSettingController@setMatriculeParameters');


Route::get($manage_sequence, 'ManageSequenceController@showAddSequencePage');
Route::post($manage_sequence, 'ManageSequenceController@addSequence');
Route::get($manage_sequence . $delete . $id, 'ManageSequenceController@deleteSequence');
Route::get($manage_sequence . $edit . $id, 'ManageSequenceController@editSequence');

Route::get($manage_term, 'ManageTermController@showAddTermPage');
Route::post($manage_term, 'ManageTermController@addTerm');
Route::get($manage_term . $delete . $id, 'ManageTermController@deleteTerm');
Route::get($manage_term . $edit . $id, 'ManageTermController@editTerm');


/**
 * Handles series management
 */
Route::get($manage_series, 'ManageSeriesController@showAddSeriesPage');
Route::post($manage_series, 'ManageSeriesController@addSeries');
Route::get($manage_series . $delete . $id, 'ManageSeriesController@deleteSeries');
Route::get($manage_series . $edit . $id, 'ManageSeriesController@editSeries');

Route::get($manage_student_series, 'ManageStudentSeriesController@showManageStudentSeriesPage');
Route::get($manage_student_series . $get_student, 'ManageStudentSeriesController@getStudentInformation');
Route::post($manage_student_series . $get_student, 'ManageStudentSeriesController@changeSeries');

Route::get($manage_subject_series, 'ManageSubjectSeriesController@showManageSubjectSeriesPage');
Route::get($manage_subject_series . $get_subject, 'ManageSubjectSeriesController@getSubjectInformation');
Route::post($manage_subject_series . $get_subject, 'ManageSubjectSeriesController@changeSubjectSeries');

/**
 *  Student Management
 */


Route::get($batch_student_upload, 'BatchStudentUploadController@showBatchStudentUploadPage');
Route::post($batch_student_upload, 'BatchStudentUploadController@batchStudentUpload');
Route::get($batch_student_upload_download, 'BatchStudentUploadController@downloadStudentList');

Route::get($add_student, 'AddStudentController@showAddStudentPage');
Route::post($add_student, 'AddStudentController@addStudent');

Route::get($search_student, 'EditStudentController@showSearchStudentPage');
Route::post($search_student, 'EditStudentController@searchStudents');

Route::get($edit_student . $id, 'EditStudentController@showEditStudentPage');
Route::post($edit_student . $id, 'EditStudentController@editStudent');


Route::get($id_card, 'studentIdController@showStudentIdPage');
Route::post($id_card, 'studentIdController@generateStudentId');
Route::get($id_card .$id, 'studentIdController@getStudentId');
Route::get($download_student_id_card . $id, 'studentIdController@downloadIDCard');


/**
 *  Student Portal
 */


Route::get($result_portal, 'StudentPortalController@showResultPortalPage');
Route::post($result_portal, 'StudentPortalController@getStudentResult');
Route::get($student_info, 'StudentPortalController@showStudentInformationPage');
Route::get($student_info_phone, 'StudentPortalController@getStudentInformationByPhone');
Route::post($student_info, 'StudentPortalController@getStudentInformation');

Route::get($download_result_t . $id . '/{termId}/{academicYear}', 'StudentPortalController@downloadTermResult');
Route::get($download_result_s . $id . '/{sequenceId}/{academicYear}', 'StudentPortalController@downloadSequenceResult');

Route::get($s_change_locale, 'StudentPortalController@setLocale');


/**
 * Result management
 */

Route::get($public_exams_setting, 'PublicExamSettingController@showManagePublicExamsSettingPage');
Route::post($public_exams_setting, 'PublicExamSettingController@setPublicExamsParameters');

Route::get($download_public_exam . $id, 'StudentPortalController@downloadPublicExam');

Route::get($publish_result, 'PublishResultController@showPublishResultPage');
Route::get($publish_result . $publish, 'PublishResultController@publishResults');
Route::get($publish_result . $notify . $id, 'PublishResultController@showNotificationPage');
Route::post($publish_result . $notify . $id, 'PublishResultController@notify');

Route::get($print_report_card, 'PrintReportCardController@showPrintReportCardPage');
Route::post($print_report_card, 'PrintReportCardController@printReportCard');
Route::get($print_class_report_card, 'PrintReportCardController@printClassReportCard');
Route::get($download_student_report_card, 'PrintReportCardController@downloadStudentReportCard');
Route::get($class_auto_promote, 'PrintReportCardController@autoPromoteAndRepeat');
Route::get($repeat_class, 'PrintReportCardController@repeatClass');
Route::get($promote_student, 'PrintReportCardController@promoteStudent');
Route::get($download_class_report_card, 'PrintReportCardController@downloadClassReportCards');

/**
 * Subject Management
 */

Route::get($manage_test, 'ManageTestEchoSystemController@showTeacherSubjectListPage');
Route::get($manage_test . $id, 'ManageTestEchoSystemController@showManageTestPage');
Route::get($manage_test . $id . $create, 'ManageTestEchoSystemController@createTest');

Route::get($manage_test . $mark_entry . $id, 'ManageTestEchoSystemController@showMarkEntryPage');
Route::post($manage_test . $mark_entry . $id, 'ManageTestEchoSystemController@saveMarks');

Route::get($manage_test . $csv_mark_entry . $id, 'ManageTestEchoSystemController@showCSVMarkEntryPage');
Route::post($manage_test . $csv_mark_entry . $id, 'ManageTestEchoSystemController@uploadCSVMarks');
Route::get($generate_student_list, 'ManageTestEchoSystemController@generateStudentList');

Route::get($submit_marks, 'ManageTestEchoSystemController@submitMarks');
Route::get($view_subject_result, 'ManageTestEchoSystemController@showViewFinalResultPage');
Route::post($view_subject_result, 'ManageTestEchoSystemController@getFinalResult');
Route::get($batch_subject_upload, 'BatchSubjectUploadController@showBatchSubjectUploadPage');
Route::post($batch_subject_upload, 'BatchSubjectUploadController@batchSubjectUpload');

Route::get($add_subject, 'AddSubjectController@showAddSubjectPage');
Route::post($add_subject, 'AddSubjectController@addSubject');

Route::get($edit_subject, 'EditSubjectController@showEditSubjectPage');
Route::post($edit_subject, 'EditSubjectController@searchSubject');

Route::get($edit_subject . $save_subject, 'EditSubjectController@editSubject');


Route::get($get_class_list, 'ClassListController@showClassListRequestPage');
Route::post($get_class_list, 'ClassListController@getClassListFromClassCode');

Route::get($series_data_upload, 'SeriesUploadStudentController@showSeriesDataUploadPage');
Route::post($series_data_upload, 'SeriesUploadStudentController@seriesDataUpload');

Route::get($view_subject, 'viewSubjectController@viewSubjectRequestPage');
Route::post($view_subject, 'viewSubjectController@getSubjectListFromClassCode');

Route::any($search_subject, 'EditSubjectController@showSubjectQueryPage');

Route::get($edit_subject . $id, 'EditSubjectController@editSubjectById');
Route::get($delete_subject . $id, 'EditSubjectController@deleteSubjectById');


//Route::get($manage_test . $get_subject, 'ManageTestEchoSystemController@getTeacherSubjectList');


/**
 * Announcement redirection routes
 */

Route::get($announcement, 'AnnouncementController@showAnnouncementPage');
Route::post($announcement, 'AnnouncementController@sendAnnouncement');

Route::get($announcement . $id, 'AnnouncementController@showAnnouncement');
Route::get($announcement_list, 'AnnouncementController@showAnnouncementList');

/**
 * Manage promotions/student access/teacher subjects
 */

Route::get($class_promotion, 'ManagePromotionController@showClassPromotionPage');
Route::post($class_promotion, 'ManagePromotionController@getPromotionList');
Route::get($repeat_class . $id . '/{year}', 'ManagePromotionController@repeatClass');
Route::get($promote_student . $id . '/{year}', 'ManagePromotionController@promoteStudent');
Route::get($batch_promotion . $id, 'ManagePromotionController@batchSchoolStudentAutoPromote');


Route::get($student_portal_access, 'ManagePortalAccessController@showManagePortalAccessPage');
Route::post($student_portal_access, 'ManagePortalAccessController@getStudentList');
Route::get($suspend_student . $id, 'ManagePortalAccessController@studentSuspension');
Route::get($revert_student_suspension . $id, 'ManagePortalAccessController@revertStudentSuspension');
Route::get($generate_secret . $id, 'ManagePortalAccessController@generateResultPortalAccessCode');


Route::get($manage_teacher_subjects, 'AssignTeacherSubjectController@showAssignSubjectPage');
Route::get($load_subjects . $id . "/{classCode}/{state}", 'AssignTeacherSubjectController@loadSubjects');
Route::post($load_subjects . $id . "/{classCode}/{state}", 'AssignTeacherSubjectController@assignSubjects');


/**
 * SMS Notifications
 */
Route::get($sms_notifications, 'SMSNotificationController@showSMSNotificationPage');
Route::post($sms_notifications, 'SMSNotificationController@getClassNotificationPage');
Route::get($student_sms_notifications . $id , 'SMSNotificationController@notifyStudent');
Route::get($class_sms_notifications . $id, 'SMSNotificationController@classSMSNotification');
Route::get($general_sms_notifications, 'SMSNotificationController@sendGeneralSMSNotification');


/**
 * help
 */
Route::get($help, 'HelpController@showHelpPage');
Route::post($help, 'HelpController@sendEmail');

/**
 * Auditing Management
 */
Route::get($audit_user_actions, 'AuditAccountManagementActionsController@showAuditUserActionPage');
Route::post($audit_user_actions, 'AuditAccountManagementActionsController@getAuditActivities');

Route::get($audit_student_actions, 'AuditStudentActionsController@showAuditStudentActionPage');
Route::post($audit_student_actions, 'AuditStudentActionsController@getAuditActivities');

Route::get($audit_subject_actions, 'AuditSubjectActionsController@showAuditSubjectActionPage');
Route::post($audit_subject_actions, 'AuditSubjectActionsController@getAuditActivities');

Route::get($audit_term_actions, 'AuditManageTermActionsController@showAuditTermActionPage');
Route::post($audit_term_actions, 'AuditManageTermActionsController@getAuditActivities');

Route::get($audit_sequence_actions, 'AuditManageSequenceActionsController@showAuditSequenceActionPage');
Route::post($audit_sequence_actions, 'AuditManageSequenceActionsController@getAuditActivities');

Route::get($audit_class_actions, 'AuditManageClassActionsController@showAuditClassActionPage');
Route::post($audit_class_actions, 'AuditManageClassActionsController@getAuditActivities');

Route::get($audit_setting_actions, 'AuditAcademicSettingActionsController@showAuditAcademicSettingActionPage');
Route::post($audit_setting_actions, 'AuditAcademicSettingActionsController@getAuditActivities');
