<?php

namespace report_customreports\output;

defined('MOODLE_INTERNAL') || die();

use renderer_base;

class main implements \templatable, \renderable
{
    public function export_for_template(renderer_base $output)
    {
        global $USER, $OUTPUT, $DB;
        require_once(__DIR__ . '/../../lib.php');

        $context = \context_system::instance();
        //repeat *15

        if (has_capability('report/customreports:view_course_files', $context)) {
            $data['course_files'] = true;
        }


        if (has_capability('report/customreports:view_login_info', $context)) {
            $data['login_info'] = true;
        }


        //repeat *15
        return $data;
    }
}