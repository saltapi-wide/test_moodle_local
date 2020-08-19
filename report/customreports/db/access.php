<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = array(




    'report/customreports:view_reports' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(

        ),

    ),

    'report/customreports:view_course_files' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(

        ),

    ),

    'report/customreports:view_category_files' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(

        ),

    ),

    'report/customreports:view_login_info' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'manager' => CAP_ALLOW,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_PROHIBIT,
            'user' => CAP_PROHIBIT
        )
    ),




);
