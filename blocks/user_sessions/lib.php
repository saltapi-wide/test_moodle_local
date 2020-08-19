<?php


function get_form_users($courseid)
{


    $users = get_enrolled_users(context_course::instance($courseid));
    $ar = array();
    $ar[0] = 'Select a user';
    foreach ($users as $user) {
        $ar[$user->id] = $user->firstname . ' ' . $user->lastname . '(' . $user->email . ')';
    }
    return $ar;
}

function get_form_activities($courseid,$userid)
{
    global $DB;
    $modulessql = "SELECT cm.id,m.name,cm.instance,cm.module FROM {course_modules} cm INNER JOIN {modules} m ON cm.module=m.id WHERE cm.course={$courseid} AND cm.visible=1";
    $modules = $DB->get_records_sql($modulessql);
    $ar = array();
    foreach ($modules as $module) {
        //$activityname = $module->name;
        $activityname = $DB->get_prefix().$module->name;
        //echo "ACTIVITY NAME: ".$activityname."<br>";


        //$activity = $DB->get_record($activityname, array('id' => $module->instance, 'course' => $courseid));

        $activity_sql = "SELECT a.id,a.name,cm.id AS course_module_id
                         FROM {$activityname} a
                         INNER JOIN {course_modules} cm ON a.id=cm.instance
                         INNER JOIN {modules} m ON cm.module=m.id
                         WHERE a.id={$module->instance} AND a.course={$courseid} AND cm.module={$module->module}";

        $activity = $DB->get_record_sql($activity_sql);

        //var_dump($activity);

        $data=array('name' => '[' . $module->name . '] ' . $activity->name, 'id' => $activity->id);

        $data['course_module_id'] = $activity->course_module_id;

        if($setting=$DB->get_record('us_user_settings',array('cmid'=>$activity->course_module_id,'courseid'=>$courseid,'userid'=>$userid))){
          $data['duration']=$setting->duration;
          //$data['course_module_id'] = $activity->course_module_id;
        }
        $ar[] = $data;

        //var_dump($data);exit;

    }
    return $ar;
}

function set_specific_user_data($object)
{
    global $DB;

    //var_dump($object);
    //exit;

    $userid = $object->userid;
    unset($object->userid);
    unset($object->users);
    unset($object->submitbutton);
    $courseid = $object->courseid;

    $from_user = (int)$object->from_user;
    unset($object->from_user);


    unset($object->courseid);
    if ($object->user_active && $object->user_active == 1) {
        $active = 1;
    } else {
        $active = 0;
    }
    unset($object->user_active);
    //echo '<pre>';
    //print_r($object);
    //echo '</pre>';

    //var_dump($object);

    foreach ($object as $key => $data) {

        //echo $key; echo "<br>";

        //var_dump($data);

        $insertrecord = new stdClass();
        $insertrecord->active = $active;
        $insertrecord->userid = $userid;
        $insertrecord->courseid = $courseid;
        //$insertrecord->cmid = (int)$key;
        $insertrecord->cmid = (int)$key;

        //echo $data;

        $cm = $DB->get_record('course_modules', array('course' => $courseid, 'id' => $key));

        var_dump($data);

        //exit;

        $mod = $DB->get_record('modules', array('id' => $cm->module));
        $insertrecord->modtype = $mod->name;

        $insertrecord->from_user = $from_user;

        //echo "DATE: ".$object->from_user;

        $insertrecord->duration = $data;
        $insertrecord->timemodified = time();
        if ($record = $DB->get_record('us_user_settings', array('userid' => $insertrecord->userid, 'courseid' => $insertrecord->courseid, 'cmid' => $key))) {

            $insertrecord->id = $record->id;
            $DB->update_record('us_user_settings', $insertrecord);
        } else {
            $insertrecord->timecreated = time();
            $DB->insert_record('us_user_settings', $insertrecord);
        }

    }
}

function set_general_settings($object)
{
    global $DB;

    $insertrecord = new stdClass();
    $insertrecord->courseid = $object->courseid;
    if($object->general_active){
        $active=1;
    }else{
        $active=0;
    }
    $insertrecord->active = $active;
    $insertrecord->duration = $object->duration;
    $insertrecord->timemodified = time();

    $insertrecord->from_general = (int)$object->from_general;

    //var_dump($insertrecord); exit;

    if ($record = $DB->get_record('us_general_settings', array('courseid' => $object->courseid))) {
        $insertrecord->id = $record->id;
        $DB->update_record('us_general_settings', $insertrecord);
    } else {
        $insertrecord->timecreated = time();
        $DB->insert_record('us_general_settings', $insertrecord);
    }
}

