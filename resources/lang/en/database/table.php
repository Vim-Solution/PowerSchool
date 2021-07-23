<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Database Language Lines
    |--------------------------------------------------------------------------
    | The following lines are used for maintenance purpose and records the databases' tables
    | names and  attributes that the application uses
    |
    */

    /*
     * attributes of the users Table excluding those that are not unique to the database
     */

    'users' => 'users',

    'user_id' => 'user_id',
    'email' => 'email',
    'user_name' => 'user_name',
    'address' => 'address',
    'office_address' => 'office_address',
    'phone_number' => 'phone_number',
    'profile' => 'profile',
    'position' => 'position',
    'type' => 'type',
    'active' => 'active',
    'password' => 'password',
    'remember_me' => 'remember_me',
    'lang' => 'lang',
    /*
     * attributes of the  users_suspension Table excluding those that are not unique to the database
     */

    'audit_user_actions' => 'audit_user_actions',
    'audit_subject_actions' => 'audit_subject_actions',
    'audit_student_actions' => 'audit_student_actions',
    'audit_academic_level_actions' => 'audit_manage_academic_level_actions',
    'audit_manage_sequence_actions' => 'audit_manage_sequence_actions',
    'audit_manage_term_actions' => 'audit_manage_term_actions',
    'audit_academic_setting_actions' => 'audit_academic_setting_actions',


    'suspensionee_id' => 'suspensionee_id',
    'suspensionee_name' => 'suspensionee_name',

    'a_year' => 'a_year',

    'term_averages' => 'term_averages',
    'sequence_averages' => 'sequence_averages',
    /*
     * pivot tables with tables whose keys are foreign
     */

    'roles_has_privileges' => 'roles_has_privileges',
    'students_suspension' => 'students_suspension',
    'classes_has_students' => 'classes_has_students',
    'subjects_has_masters' => 'subjects_has_masters',
    'student_series_changes' => 'student_series_changes',
    'teacher_teaches_subject' => 'teacher_teaches_subject',
    'academic_years' => 'academic_years',
    'series_has_subjects' => 'series_has_subjects',
    'tests_has_scores' => 'tests_has_scores',
    'subjects_has_scores' => 'subjects_has_scores',
    'subject_masters_suspension' => 'subject_masters_suspension',
    'series_has_students' => 'series_has_students',
    'sms_notifications' => 'sms_notifications',
    'sms_count' => 'sms_count',

    /*
       * attributes of the tests Table excluding those that are not unique to the database
       */
    'tests' => 'tests',

    'test_id' => 'test_id',
    'test_name' => 'test_name',
    'test_code' => 'test_code',


    /*
     * attributes of the students Table excluding those that are not unique to the database
     */
    'students' => 'students',

    'student_id' => 'student_id',
    'matricule' => 'matricule',
    'place_of_birth' => 'place_of_birth',
    'region_of_origin' => 'region_of_origin',
    'date_of_birth' => 'date_of_birth',
    'father_address' => 'father_address',
    'mother_address' => 'mother_address',
    'tutor_name' => 'tutor_name',
    'tutor_address' => 'tutor_address',
    'admission_date' => 'admission_date',
    'assignee_id' => 'assignee_id',
    /*
     * attributes of the programs Table excluding those that are not unique to the database
     */
    'programs' => 'programs',

    'program_id' => 'program_id',
    'program_name' => 'program_name',
    'program_code' => 'program_code',
    /*
     * attributes of the sections Table excluding those that are not unique to the database
     */
    'sections' => 'sections',

    'section_id' => 'section_id',
    'section_name' => 'section_name',
    'section_code' => 'section_code',

    /*
     * attributes of the sections Table excluding those that are not unique to the database
     */
    'classes' => 'classes',

    'class_id' => 'class_id',
    'class_name' => 'class_name',
    'class_code' => 'class_code',
    'annual_promotion_average' => 'annual_promotion_average',
    'next_promotion_class' =>'next_promotion_class',

    /*
     * attributes of the roles Table excluding those that are not unique to the database
     */
    'roles' => 'roles',

    'role_id' => 'role_id',
    'role_name' => 'role_name',

    /*
     * attributes of the privileges Table excluding those that are not unique to the database
     */
    'privileges' => 'privileges',

    'privilege_id' => 'privilege_id',
    'privilege_url' => 'privilege_url',
    'privilege_icon' => 'privilege_icon',
    'privilege_name' => 'privilege_name',

    /*
     * attributes of the categories Table excluding those that are not unique to the database
     */

    'categories' => 'categories_id',

    'category_id' => 'category_id',
    'category_name' => 'category_name',
    'category_icon' => 'category_icon',


    /*
   * attributes of the matricule_settings Table excluding those that are not unique to the database
   */

    'matricule_settings' => 'matricule_settings',

    'matricule_initial' => 'matricule_initial',

    'final_marks' => 'final_marks',

    'publish_status' => 'publish_status',

    /*
  * attributes of the exam_settings Table excluding those that are not unique to the database
  */

    'exam_settings' => 'exam_settings',

    'center_no' => 'center_no',
    'exam_file_path' => 'exam_file_path',


    /*
     * attributes of the departments Table excluding those that are not unique to the database
     */

    'departments' => 'departments',

    'department_id' => 'department_id',
    'department_name' => 'category_name',
    'department_location' => 'department_location',


    /*
     * attributes of the settings Table excluding those that are not unique to the database
     */

    'settings' => 'settings',

    'setting_id' => 'setting_id',
    'publish_date' => 'publish_date',

    /*
   * attributes of the series Table excluding those that are not unique to the database
   */

    'series' => 'series',

    'series_id' => 'series_id',
    'series_name' => 'series_name',
    'series_code' => 'series_code',

    /*
     * attributes of the  terms Table excluding those that are not unique to the database
     */

    'terms' => 'terms',

    'term_id' => 'term_id',
    'term_name' => 'term_name',
    'term_code' => 'term_code',

    /*
     * attributes of the student_accounts Table excluding those that are not unique to the database
     */

    'student_accounts' => 'student_accounts',

    'secret_code' => 'secret_code',


    /*
     * attributes of the sequence Table excluding those that are not unique to the database
     */

    'sequences' => 'sequences',

    'sequence_id' => 'sequence_id',
    'sequence_name' => 'sequence_name',
    'sequence_code' => 'sequence_code',

    /*
     * attributes of the sequence Table excluding those that are not unique to the database
     */

    'submission_states' => 'submission_states',

    'submission_state_id' => 'submission_state_id',
    'submission_state' => 'submission_state',

    /*
   * attributes of the notifications Table excluding those that are not unique to the database
   */

    'notifications' => 'notifications',

    'notification_id' => 'notification_id',
    'notification_subject' => 'notification_subject',
    'notification_body' => 'notification_body',
    'notifier_id' => 'notifier_id',


    /*
     * attributes of the  subjects Table excluding those that are not unique to the database
     */

    'subjects' => 'subjects',

    'subject_id' => 'subject_id',
    'subject_code' => 'subject_code',
    'subject_title' => 'subject_title',
    'coefficient' => 'coefficient',
    'subject_weight' => 'subject_weight',

    'subject_series_changes' => 'subject_series_changes',

    /*
     * non database unique attributes
     */

    'full_name' => 'full_name',
    'academic_state' => 'academic_state',
    'state' => 'state',
    'description' => 'description',
    'academic_year' => 'academic_year',
    'student_matricule' => 'student_matricule',
    'test_score' => 'test_score',
    'publish_state' => 'publish_state',
    'subject_score' => 'subject_score',
    'promotion_state' =>  'promotion_state',


    /*
     * foreign attributes
     */
    'id' => 'id',
    'series_series_id' => 'series_series_id',
    'students_student_id' => 'students_student_id',
    'departments_department_id' => 'departments_department_id',
    'programs_program_code' => 'programs_program_code',
    'sections_section_code' => 'sections_section_code',
    'users_user_id' => 'users_user_id',
    'roles_role_id' => 'roles_role_id',
    'privileges_privilege_id' => 'privileges_privilege_id',
    'categories_category_id' => 'categories_category_id',
    'sequences_sequence_id' => 'sequences_sequence_id',
    'terms_term_id' => 'terms_term_id',
    'classes_class_id' => 'classes_class_id',
    'classes_class_code' => 'classes_class_code',
    'subjects_subject_id' => 'subjects_subject_id',
    'tests_test_id' => 'tests_test_id',
    'subject_master_id' => 'subject_master_id',
    'academic_year_id' => 'academic_year_id',
    'classes_has_subjects' => 'classes_has_subjects',
    'series_series_code' =>'series_series_code',
    'subjects_subject_code' => 'subjects_subject_code',
    'average' => 'average',
    'sequences_sequence_name' => 'sequences_sequence_name',
    'subjects_subject_name' => 'subjects_subject_name',
    'students_student_name' => 'students_student_name',
    'terms_term_name' => 'terms_term_name',
    'classes_class_name' => 'classes_class_name',


    /*
     * special non database unique attributes
     */
    'created_at' => 'created_at',
    'updated_at' => 'updated_at',
    'deleted_at' => 'deleted_at',

    /* dual privilege lang files*/
    'bilingual' => 'dl'


];
