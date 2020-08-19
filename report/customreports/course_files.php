<?php


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');


$url = new moodle_url('/report/customreports/course_files.php');
$PAGE->set_url($url);
//$users=optional_param('users','all',PARAM_TEXT);
global $DB, $CFG, $USER;
$CFG->cachejs = true;
get_custom_reports_css($PAGE);

$context = context_system::instance();
$PAGE->set_context($context);
require_capability('report/customreports:view_course_files', $context);

$PAGE->set_title('Course Files Report');
$PAGE->set_heading('Course Files Report');

$outputpage = new \report_customreports\output\course_files();

$output = $PAGE->get_renderer('report_customreports');

echo $output->header();
echo $output->render($outputpage);
echo $output->footer();