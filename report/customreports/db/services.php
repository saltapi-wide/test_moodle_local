<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(



    'report_customreports_get_no_of_attendees' => array(
        'classpath' => 'report/customreports/classes/external.php',
        'classname'   => 'report_customreports_external',
        'methodname'  => 'get_no_of_attendees',
        'description' => 'Get number of attendees data for categories/subcategories from-to dates',
        'type'        => 'read',
        'ajax'        => true,
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
    )


);





