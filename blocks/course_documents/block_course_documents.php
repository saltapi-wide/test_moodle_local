<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 10/6/2020
 * Time: 5:05 μμ
 */


class block_course_documents extends block_base
{

    function init()
    {
        $this->title = get_string('pluginname', 'block_course_documents');
    }

    function specialization()
    {
    }

    function applicable_formats()
    {
        return array('all' => true, 'mod' => false, 'tag' => false, 'my' => false);
    }

    function instance_allow_multiple()
    {
        return false;
    }

    function get_content()
    {
        global $CFG, $USER, $PAGE, $OUTPUT;

        if ($this->content !== NULL) {
            return $this->content;
        }
        if (empty($this->instance)) {
            return null;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';
        if (isloggedin() && !isguestuser()) {   // Show the block
            $this->content = new stdClass();


            $courseid = $PAGE->course->id;

            $actionurl = new moodle_url('/blocks/course_documents/documents.php', array('cid' => $courseid));

            $this->content->text .= html_writer::start_div('managecd', array("style" => "margin-top:3.5px; margin-bottom:3.5px;"));
            $this->content->text .= html_writer::link(new moodle_url($actionurl), get_string('managecd', 'block_course_documents'));
            $this->content->text .= html_writer::end_div();


            $actionurl2 = new moodle_url('/blocks/course_documents/available_documents.php', array('cid' => $courseid));

            $this->content->text .= html_writer::start_div('viewcd', array("style" => "margin-top:3.5px; margin-bottom:3.5px;"));
            $this->content->text .= html_writer::link(new moodle_url($actionurl2), get_string('viewcd', 'block_course_documents'));
            $this->content->text .= html_writer::end_div();


        }
        return $this->content;
    }
}

