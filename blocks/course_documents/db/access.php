<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 2:53 μμ
 */


defined('MOODLE_INTERNAL') || die();

$capabilities = array(


    'block/course_documents:manage_course_documents' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_USER,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),

    ),

    'block/course_documents:view_course_documents' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_USER,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        ),

    ),

    'block/course_documents:myaddinstance' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'user' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/my:manageblocks'
    ),

    'block/course_documents:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,

        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),

        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),


);

