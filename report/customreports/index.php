<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 12/6/2019
 * Time: 9:41 πμ
 */

require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');


$url = new moodle_url('/report/customreports/index.php');
$PAGE->set_url($url);

global $DB, $CFG, $USER;
$CFG->cachejs = true;
get_custom_reports_css($PAGE);

$context = context_system::instance();
$PAGE->set_context($context);
require_capability('report/customreports:view_reports', $context);

$PAGE->set_title(get_string('pluginname','report_customreports'));
$PAGE->set_heading(get_string('pluginname','report_customreports'));


//breadcrumb start

//$PAGE->navbar->ignore_active();

$PAGE->navbar->add(get_string('main_page','report_customreports'));

//breadcrumb end


$outputpage = new \report_customreports\output\main();

$output = $PAGE->get_renderer('report_customreports');

echo $output->header();
echo $output->render($outputpage);
echo $output->footer();
