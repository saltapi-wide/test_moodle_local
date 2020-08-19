<?php

namespace report_customreports\output;

defined('MOODLE_INTERNAL') || die();

use renderer_base;
use moodle_url;


class course_files implements \templatable, \renderable
{


    public function export_for_template(renderer_base $output)
    {

        $course_list_info = get_courses_list_for_files();
        $course_files=get_course_files($course_list_info);

        $course_list = array();
        $item = array();

        foreach($course_list_info->course_list AS $course_item){

            $item['courseid'] = $course_item->courseid;
            $item['coursefullname'] = $course_item->coursefullname;

            if($course_item->courseid == $course_list_info->courseid) {
                $item['flag'] = true;
            }else{
                $item['flag'] = false;
            }

            $course_list[] = $item;
        }

        $data = [
            'course_list' => $course_list,
            'files_list' => $course_files,
            'courseid' => $course_list_info->courseid
        ];

        return $data;
    }
}