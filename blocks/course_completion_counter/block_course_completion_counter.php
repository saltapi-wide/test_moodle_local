<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * User Sessions Block
 *
 * @package    block_coursecompletioncounter
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class block_course_completion_counter extends block_base {




    public function init() {
        $this->title = get_string('course_completion_counter', 'block_course_completion_counter');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }

        global $DB,$THEME;
        global $COURSE;
        global $USER;
        global $PAGE,$CFG;


        require_once("{$CFG->libdir}/completionlib.php");

        $userid = $USER->id;

        require_login();


        $courses = enrol_get_users_courses($USER->id, true);

        //echo "<pre>";
        //var_dump($courses);
        //echo "</pre>";

        $max = count($courses);

        $res1=0;
        $res2=0;

        if($max== 0){
            $res1=1;
            $res2=1;
            $res3=1;
        }else{

            foreach($courses AS $course) {

                $cinfo = new completion_info($course);
                $iscomplete = $cinfo->is_course_complete($USER->id);

                if($iscomplete){
                    $res1++;
                }


            }


            $sql = "SELECT COUNT(cc.id) num_of_active_courses
                    FROM {course_completions} cc
                    WHERE cc.userid={$userid}
                    AND cc.timestarted != 0
                    AND cc.timecompleted IS NULL
                    ";

            $user_completions = $DB->get_record_sql($sql);

            $res3= $user_completions->num_of_active_courses;


            $res2=$max-$res1-$res3;


        }

        //$res3=3;


        //var_dump($courses);



        $this->content         =  new stdClass;



        //if (is_siteadmin() && (has_capability('block/course_completion_counter:addinstance',$PAGE->context))) {

        $PAGE->requires->css('/blocks/course_completion_counter/styles/style.css');

        $div = "
        <div class='container_counters container-fluid p-0'>
        <div class='col-md-3 counter' id='counter1'></div>
        <div class='col-md-3 counter' id='counter2'></div>
        <div class='col-md-3 counter' id='counter3'></div>
        <div class='col-md-3 counter' id='counter4'></div>
        
        </div>
        
        ";

        $CFG->cachejs = false;

        $this->content->text = $div;

        $PAGE->requires->jquery();

        //$PAGE->requires->js('/blocks/course_completion_counter/amd/build/progressbar.min.js');

        $title1=get_string('total_courses','block_course_completion_counter');
        $title2=get_string('todo_courses','block_course_completion_counter');
        $title3=get_string('completed_courses','block_course_completion_counter');

        $title4='Σε πρόοδο';


        $PAGE->requires->js_call_amd('block_course_completion_counter/init', 'init', array($max, $res2, $res1,$res3, $title1, $title2, $title3, $title4));



    return $this->content;

}


    public function specialization() {

        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaultitle', 'block_course_completion_counter');
            } else {
                $this->title = $this->config->title;
            }

        }
    }







}

