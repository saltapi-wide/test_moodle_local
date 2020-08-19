<?php

require_once('../../config.php');
require_once('user_sessions_form2.php');


global $DB, $OUTPUT, $PAGE, $COURSE;


// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
//$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.
$userid = optional_param('userid', 0, PARAM_INT);


$PAGE->set_context(\context_system::instance());
require_login();

$course_info = $DB->get_record('course', array('id' => $courseid), 'fullname');
//breadcrumb start

//$PAGE->navbar->ignore_active();
$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
$PAGE->navbar->add($course_info->fullname, $courseurl);
$PAGE->navbar->add(get_string('settings', 'block_user_sessions'));

//breadcrumb end


$urlarray = array('courseid' => $courseid);
if ($userid != 0) {
    $urlarray['userid'] = $userid;
}

$PAGE->set_url('/blocks/user_sessions/view2.php', $urlarray);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('myformtitle', 'block_user_sessions'));
$toform = array('userid' => $userid, 'courseid' => $courseid);

$mform_general = new block_user_sessions_form_for_general(null, $toform);

$mform_user = new block_user_sessions_form_for_user(null, $toform);

echo $OUTPUT->header();

if ($mform_general->is_cancelled()) {

} else if ($fromform = $mform_general->get_data()) {

    set_general_settings($fromform);
    redirect(new moodle_url('/blocks/user_sessions/view2.php', $urlarray));

} else {
    if($data=$DB->get_record('us_general_settings',array('courseid'=>$courseid))){
        if($data->active==1){
            $toform['general_active']=1;
        }else{
            $toform['general_active']=0;
        }
        $toform['duration']=$data->duration;
    }
    $mform_general->set_data($toform);

    $mform_general->display();
}


if ($mform_user->is_cancelled()) {

} else if ($fromform = $mform_user->get_data()) {
    //echo '<pre>';
    //print_r($fromform);
    //echo '</pre>';die();

    //var_dump($fromform);

    set_specific_user_data($fromform);
    redirect(new moodle_url('/blocks/user_sessions/view2.php', $urlarray));


} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    //Set default data (if any)

    $mform_user->set_data($toform);
    //displays the form
    $mform_user->display();
}

echo $OUTPUT->footer();

