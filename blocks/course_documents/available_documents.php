<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 29/5/2019
 * Time: 1:05 μμ
 */

require_once(__DIR__ . '/../../config.php');

$courseid = required_param('cid', PARAM_INT);

$url = new moodle_url("/blocks/course_documents/available_documents.php?cid=$courseid");
$PAGE->set_url($url);

global $DB, $CFG, $USER;

require_login($courseid);
$userid = $USER->id;
$context = context_user::instance($userid);
require_capability('block/course_documents:view_course_documents', $context);

$PAGE->set_title(get_string('available_documents', 'block_course_documents'));
$PAGE->set_heading(get_string('available_documents', 'block_course_documents'));

$outputpage = new \block_course_documents\output\documents_data($userid, $courseid);

$output = $PAGE->get_renderer('block_course_documents');
$PAGE->requires->css('/blocks/course_documents/style/userdocuments.css');

echo $output->header();
echo $output->render($outputpage);
echo $output->footer();