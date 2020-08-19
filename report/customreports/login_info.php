<?php


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');


$url = new moodle_url('/report/customreports/login_info.php');
$PAGE->set_url($url);
//$users=optional_param('users','all',PARAM_TEXT);
global $DB, $CFG, $USER;
$CFG->cachejs = true;
get_custom_reports_css($PAGE);

//breadcrumb start


$PAGE->navbar->add(get_string('main_page','report_customreports'),'/report/customreports/index.php');
$PAGE->navbar->add(get_string('login_info_title','report_customreports'));

//breadcrumb end


$context = context_system::instance();
$PAGE->set_context($context);
require_capability('report/customreports:view_login_info', $context);

$PAGE->set_title(get_string('login_info_title','report_customreports'));
$PAGE->set_heading(get_string('login_info_title','report_customreports'));

$outputpage = new \report_customreports\output\login_info();

$output = $PAGE->get_renderer('report_customreports');

echo $output->header();
echo $output->render($outputpage);
echo $output->footer();