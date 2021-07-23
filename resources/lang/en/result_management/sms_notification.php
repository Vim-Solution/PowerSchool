<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Notification Language Lines
    |--------------------------------------------------------------------------
    |
    */
    'sms_notification_header' => 'SMS Notifications',
    'sms_notification_title' => 'Send Result Notifications to parents via SMS',
    'select_class' => 'Select a class',
    'no_student_alert' => 'Hello!Please we are sorry,there are no records of students  in the academic year :year',
    'empty_class_alert' => 'Hello!Please we are sorry,there are no records of this class in our system',
    'bulk_notification' => 'Notify Class Students\' Parents',
    'general_notification' => 'Notify Entire School Students\' Parents',
    'matricule' => 'Matricule N<sup>o</sup>',
    'full_name' => 'Full Names',
    'tel' => 'Notification Number',
    'action' => 'Click me to send sms notification',
    'notify' => 'Notify Parent',
    'student_list_title' => 'List of :class students for the academic year :year',
    'class' => 'Academic Level',
    'not_a_student' => 'Hello! please we\'re sorry. we could not find such a student in our records',
    'suspension' => 'Hello! please we\'re sorry.The student with name :name has been suspended as such it\'s not possible to send a notification to the parent.Please visit the administration to resolve this. ',
    'notification_sent' => 'Hello! a result\'s alert has already been sent to the parent of the student with name:name for the :sequence of the academic year :year.SMS could be sent again because the sms funds for this student for this sequence has expired',
    'notification_sent_success' => 'Congratulation! the a result alert message was successfully sent to the parent of the student with name:name',
    'notification_sent_failure' => 'Hello!Please we\'re sorry,the result alert message could not be sent to the parent of the student with name :name. This may be because of the following. <ul><li>There is not enough funds to send sms</li><li>An unexpected server error occurred</li></ul>',
    'annual_result_sms' => 'Greetings from :school,the :sequence result of :name has been published.You may access it online through the link :link.<ul><li>sequence Average : :s_average</li><li>Annual Average: :a_average</li></ul>Please you may access your child\'s result by using the credentials below on the portal indicated be the link above<ul><li>Matricule Number : :matricule</li><li>Secrete Code: :secret</li></ul> :promotion',
    'sequence_result_sms' => 'Greetings from :school,the :sequence result of :name has been published.You may access it online through the link :link.Please you may access your child\'s result by using the credentials below on the portal indicated be the link above<ul><li>Matricule Number : :matricule</li><li>Secrete Code: :secret</li></ul> :promotion',
    'class_repeat' => "This student will repeat :class in the academic year :year",
    'class_promotion' => "This student will is promoted to  :class in the academic year :year",
    'bulk_notification_failure' => 'Hello! please we\'re sorry.These sms notifications could not be sent to the parents of the students in  :class.This may be because of the following reasons.<ul><li>There is not enough funds to send sms</li><li>An unexpected server error occurred</li></ul>SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',
    'bulk_notification_success' => 'Congratulation.These sms notifications has been sent sent to the parents of the students in  :class.SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',
    'bulk_notification_success_p' => 'Hello,some SMS notifications could not be sent to the parents of the students in  :class.This may be because of the following reasons.<ul><li>There is not enough funds to send sms</li><li>An unexpected server error occurred</li></ul>SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',
    'bulk_notification_error' => 'Hello! these sms notifications could not be sent.An unexpected error occurred while accessing the server',
    'bulk_notification_failure_g' => 'Hello! please we\'re sorry.These sms notifications could not be sent to the parents of the students in  :class.This may be because of the following reasons.<ul><li>There is not enough funds to send sms</li><li>An unexpected server error occurred</li></ul>SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',
    'bulk_notification_success_g' => 'Congratulation.These sms notifications has been sent sent to the students\' parents.SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',
    'bulk_notification_success_p_g' => 'Hello,some SMS notifications could not be sent to the students\' parents.This may be because of the following reasons.<ul><li>There is not enough funds to send sms</li><li>An unexpected server error occurred</li></ul>SMS Notification Statistics.<ul><li>Total number of SMS sent : :success</li><li>Total number of SMS not sent : :failure</li></ul>',

];
