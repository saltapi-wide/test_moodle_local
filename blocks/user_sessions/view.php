<?php

require_once('../../config.php');
require_once('user_sessions_form.php');


global $DB, $OUTPUT, $PAGE, $COURSE;


// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT);

// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);



//if (!$course = $DB->get_record('course', array('blockid' => $blockid, 'courseid' => $courseid))) {
//    print_error('invalidcourse', 'block_user_sessions', $courseid);
//}


$PAGE->set_context(\context_system::instance());
require_login();

$course_info = $DB->get_record('course', array('id' => $courseid),'fullname');


//breadcrumb start

//$PAGE->navbar->ignore_active();
$courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
$PAGE->navbar->add($course_info->fullname, $courseurl);
$PAGE->navbar->add(get_string('settings','block_user_sessions'));

//breadcrumb end



$PAGE->set_url('/blocks/user_sessions/view.php', array('blockid' => $blockid, 'courseid' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('myformtitle', 'block_user_sessions'));

$train_sess = new block_user_sessions_form();

echo $OUTPUT->header();


$toform['courseid'] = $courseid;
$toform['blockid'] = $blockid;
$toform['date'] = time();
$train_sess->set_data($toform);

if($train_sess->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl, get_string('cancelled'), null, \core\output\notification::NOTIFY_WARNING);
    //redirect($courseurl);

} else if ($fromform = $train_sess->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
    // but for now we will just redirect back to the course main page.
    $courseurl = new moodle_url('/blocks/user_sessions/view.php', array('blockid' => $blockid, 'courseid' => $courseid));



    $sql = 'SELECT *
			FROM {user_sessions_settings} 
			WHERE courseid=?';
    $result = $DB->get_record_sql($sql,array($courseid));

    //$result = array_pop($result);


    $sql2 = "SELECT cm.id,cm.course courseid,cm.module,m.name,m.name,co.startdate
                FROM {course_modules} cm
                LEFT JOIN {modules} m ON m.id=cm.module
                LEFT JOIN {course} co ON co.id=cm.course
                WHERE NOT m.name='label' AND co.id=?
                ORDER BY cm.id ASC";
    $activities = $DB->get_records_sql($sql2,array($courseid));

    $count_activities = count($activities);

    //echo $count_activities; exit;


    if(($count_activities > 0)
        && (isset($fromform->total_duration))
        && intval($fromform->total_duration) >0 ){

        $total_duration = $fromform->total_duration;
        $min_duration_activity = round($total_duration / $count_activities,0);

    }else if((!isset($fromform->total_duration))
        || (intval($fromform->total_duration) <= 0 )){
        $total_duration = 60*10; //10 minutes
        $min_duration_activity = 60*1; //1 minute
    }




    if ($result) {

        //echo 1;
        $fromform->id = $result->id;

        $fromform->min_duration_user = $min_duration_activity;
        $fromform->max_duration = $total_duration;


        if (!$DB->update_record('user_sessions_settings', $fromform)) {
            print_error('inserterror', 'block_user_sessions');
        }

    }else{

        $record = new stdClass();
        $record->active    = $fromform->active;
        $record->courseid = $courseid;
        $record->date = $fromform->date;
        $record->min_duration_user = $min_duration_activity;
        $record->max_duration = $total_duration;
        $record->grade_pass = 7.00;

        if (!$DB->insert_record('user_sessions_settings', $record)) {
            print_error('inserterror', 'block_user_sessions');
        }

    }

    redirect($courseurl, get_string('changessaved'), null, \core\output\notification::NOTIFY_SUCCESS);


    //redirect($courseurl);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    $settingsnode = $PAGE->settingsnav->add(get_string('settings', 'block_user_sessions'));
    $editurl = new moodle_url('/blocks/user_sessions/view.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
    $editnode = $settingsnode->add(get_string('editpage', 'block_user_sessions'), $editurl);
    $editnode->make_active();
}



$train_sess->display();
echo $OUTPUT->footer();


//$simplehtml->display();

?>
