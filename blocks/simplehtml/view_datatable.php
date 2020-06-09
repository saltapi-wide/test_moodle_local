<?php

require_once('../../config.php');
require_once('datatable_form.php');


global $DB, $OUTPUT, $PAGE;

// Check for all required variables.
$courseid = required_param('courseid', PARAM_INT);



$blockid = required_param('blockid', PARAM_INT);
// Next look for optional variables.
$id = optional_param('id', 0, PARAM_INT);



if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_simplehtml', $courseid);
}

require_login($course);


$PAGE->set_url('/blocks/simplehtml/view_datatable.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('edithtml', 'block_simplehtml'));

$simplehtml = new datatable_form();

echo $OUTPUT->header();

$toform['blockid'] = $blockid;
$toform['courseid'] = $courseid;
$toform['displaydate'] = time();
$simplehtml->set_data($toform);



if($simplehtml->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);

} else if ($fromform = $simplehtml->get_data()) {
    // We need to add code to appropriately act on and store the submitted data
    // but for now we will just redirect back to the course main page.
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));

    if (!$DB->insert_record('block_simplehtml', $fromform)) {
        print_error('inserterror', 'block_simplehtml');
    }

    redirect($courseurl);
} else {
    // form didn't validate or this is the first display
    $site = get_site();
    //echo $OUTPUT->header();
    //$simplehtml->display();
    //echo $OUTPUT->footer();
    $settingsnode = $PAGE->settingsnav->add(get_string('simplehtmlsettings', 'block_simplehtml'));
    $editurl = new moodle_url('/blocks/simplehtml/view_datatable.php', array('id' => $id, 'courseid' => $courseid, 'blockid' => $blockid));
    $editnode = $settingsnode->add(get_string('editpage', 'block_simplehtml'), $editurl);
    $editnode->make_active();
}



$simplehtml->display();
echo $OUTPUT->footer();


//$simplehtml->display();

?>
