<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 2:59 μμ
 */

require('../../config.php');
require_once("$CFG->dirroot/blocks/course_documents/classes/documents_form.php");
require_once("$CFG->dirroot/repository/lib.php");


$courseid = required_param('cid', PARAM_INT);
global $USER;


$userid = $USER->id;
$user = $DB->get_record('user', ['id' => $userid]);

require_login();
if (isguestuser()) {
    die();
}

$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);

if (empty($returnurl)) {
    $returnurl = new moodle_url("/blocks/course_documents/documents.php?cid=$courseid");

}

$context = context_user::instance($userid);
$contextcourse = context_course::instance($courseid);
$contextid = $contextcourse->id;

require_capability('block/course_documents:manage_course_documents', $context);


$title = get_string('pluginname', 'block_course_documents');
$struser = get_string('user', 'block_course_documents');

$PAGE->set_url($returnurl);
$PAGE->set_context($contextcourse);
$PAGE->set_title($title);
$PAGE->set_heading(fullname($user));


$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('user-files');

$maxbytes = $CFG->userquota;
$maxareabytes = $CFG->userquota;

if (has_capability('moodle/user:ignoreuserquota', $context)) {
    $maxbytes = USER_CAN_IGNORE_FILE_SIZE_LIMITS;
    $maxareabytes = FILE_AREA_MAX_BYTES_UNLIMITED;
}

$data = new stdClass();
$data->returnurl = $returnurl;
$options = array('subdirs' => 1, 'maxbytes' => $maxbytes, 'maxfiles' => -1, 'accepted_types' => '*',
    'areamaxbytes' => $maxareabytes);
file_prepare_standard_filemanager($data, 'files', $options, $contextcourse, 'block_course_documents', 'files', "$userid");

$mform = new block_course_documents_form($returnurl, array('data' => $data, 'options' => $options));

if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($formdata = $mform->get_data()) {
    $formdata = file_postupdate_standard_filemanager($formdata, 'files', $options, $contextcourse, 'block_course_documents', 'files', "$userid");
    redirect($returnurl);
}

echo $OUTPUT->header();
$href_url = new moodle_url('/course/view.php?', ['id' => $courseid]);
$backtocourse = get_string('btcourse','block_course_documents');
echo "<a href=$href_url><button class='btn btn-warning back_to_course'>$backtocourse</button></a>";
echo $OUTPUT->box_start('generalbox');

// Show file area space usage.
if ($maxareabytes != FILE_AREA_MAX_BYTES_UNLIMITED) {
    $fileareainfo = file_get_file_area_info($contextcourse->id, 'block_course_documents', 'files');
    // Display message only if we have files.
    if ($fileareainfo['filecount']) {
        $a = (object)[
            'used' => display_size($fileareainfo['filesize_without_references']),
            'total' => display_size($maxareabytes)
        ];
        $quotamsg = get_string('quotausage', 'moodle', $a);
        $notification = new \core\output\notification($quotamsg, \core\output\notification::NOTIFY_INFO);
        echo $OUTPUT->render($notification);
    }
}


$mform->display();


echo $OUTPUT->box_end();
echo $OUTPUT->footer();


