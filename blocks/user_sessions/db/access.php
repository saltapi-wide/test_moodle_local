<?php

    $capabilities = array(
 
    'block/user_sessions:addinstance' => array(
        'riskbitmask' => RISK_SPAM | RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_BLOCK,
        'archetypes' => array(
            'manager' => CAP_PROHIBIT,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_PROHIBIT,
            'user' => CAP_PROHIBIT,
            'coursecreator' => CAP_PROHIBIT
        ),
 
        'clonepermissionsfrom' => 'moodle/site:manageblocks'
    ),

    'block/user_sessions:managefiles' => array(
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'manager' => CAP_PROHIBIT,
            'student' => CAP_PROHIBIT,
            'teacher' => CAP_PROHIBIT,
            'editingteacher' => CAP_PROHIBIT,
            'user' => CAP_PROHIBIT,
            'coursecreator' => CAP_PROHIBIT
        )
    ),



);