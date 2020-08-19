<?php

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->libdir.'/enrollib.php');
require_once(__DIR__.'/lib.php');
class block_user_sessions_form_for_user extends moodleform
{

    function definition()
    {

        global $PAGE, $COURSE, $DB;

        $mform = $this->_form; // Don't forget the underscore!
        $data=$this->_customdata;

        $title = get_string('settings','block_user_sessions');
        $PAGE->set_title($title);

        $courseid=$data['courseid'];
        $userid=$data['userid'];

        if (is_siteadmin() && (has_capability('block/user_sessions:addinstance',$PAGE->context))) {
            //do nothing
        }else{
            $url = '/my/';
            redirect($url, 'No Access! Redirected to Dashboard...', 10);
        }

        //$from_user=$data['from_user'];


        $record = $DB->get_record('us_user_settings', array('userid' => $userid, 'courseid' => $courseid));

        if($record) {
            $active = $record->active;
        }

        //$active=$data['user_active'];

        $urloptions=array('courseid'=>$courseid);
        if($userid!=0){
            $urloptions['userid']=$userid;
            $mform->addElement('hidden','userid',$userid);
        }

        $dateparms = array(
            'startyear' => 2020,
            'stopyear'  => 2025,
            'timezone'  => 99,
            'applydst'  => true,
            'optional'  => false,
            'step' => 1
        );

        $url=new moodle_url('/blocks/user_sessions/view2.php',$urloptions);
//        echo '<pre>';
//        var_dump(get_enrolled_users(context_course::instance($courseid)));
//        echo '</pre';


        $mform->addElement('hidden','courseid',$courseid);
        $mform->addElement('html','<h4>'.get_string('specificoptions','block_user_sessions').'</h4>');
        $onchangeevent='/blocks/user_sessions/view2.php?courseid='.$courseid.'&userid=';
        $mform->addElement('select', 'users', get_string('users','block_user_sessions'), get_form_users($courseid),array('onchange' => "$(location).attr('href', '".$onchangeevent."'+$('#id_users option:selected').val())"));

        $mform->addElement('date_time_selector', 'from_user', get_string('from','block_user_sessions'), $dateparms);

        if($record){
            $mform->setDefault('from_user', (int)$record->from_user);
        }

        $mform->addElement('checkbox', 'user_active', get_string('data_generation_user','block_user_sessions'),null);

        if($record && $active && $active==1){
            $mform->setDefault('user_active', 1);
        }

         if($userid!=0) {

            $mform->getElement('users')->setSelected($userid);
            $activities=get_form_activities($courseid,$userid);

            foreach ($activities as $activity){

                //var_dump($activity);

                $mform->addElement('duration', $activity['course_module_id'], $activity['name'],array('value'=>$activity['course_module_id']));
                if(isset($activity['duration'])){
                    $mform->setDefault($activity['course_module_id'], $activity['duration']);
                    //var_dump($activity['duration']);
                }
            }
            $this->add_action_buttons();
         }

        $mform->setType('userid', PARAM_RAW);
        $mform->setType('courseid', PARAM_RAW);



//
    }
    //Custom validation should be added here


}
class block_user_sessions_form_for_general extends moodleform
{

    function definition()
    {

        global $PAGE, $COURSE, $DB;

        $mform = $this->_form; // Don't forget the underscore!
        $data=$this->_customdata;
        $courseid=$data['courseid'];
        $userid=$data['userid'];
        $duration=$data['duration'];
        //$from_general=$data['from_general'];

        if (is_siteadmin() && (has_capability('block/user_sessions:addinstance',$PAGE->context))) {
            //do nothing
        }else{
            $url = '/my/';
            redirect($url, 'No Access! Redirected to Dashboard...', 10);
        }

        $dateparms = array(
            'startyear' => 2020,
            'stopyear'  => 2025,
            'timezone'  => 99,
            'applydst'  => true,
            'optional'  => false,
            'step' => 1
        );

        $record = $DB->get_record('us_general_settings', array('courseid' => $courseid));

        if($record) {
            $active = $record->active;
        }

        //$active=$data['general_active'];


        $urloptions=array('courseid'=>$courseid);
        if($userid!=0){
            $urloptions['userid']=$userid;

        }
        $url=new moodle_url('/blocks/user_sessions/view2.php',$urloptions);
//        echo '<pre>';
//        var_dump(get_enrolled_users(context_course::instance($courseid)));
//        echo '</pre';
        $mform->addElement('hidden','courseid',$courseid);
        $mform->addElement('html','<h4>'.get_string('generaloptions','block_user_sessions').'</h4>');
        $mform->addElement('checkbox', 'general_active', get_string('data_generation','block_user_sessions'),null);

        $mform->addElement('date_time_selector', 'from_general', get_string('from','block_user_sessions'), $dateparms);


        //echo $record->from_general;

        if($record){

            $mform->setDefault('from_general', (int)$record->from_general);
        }


        if($record && $active && $active==1){
            $mform->setDefault('general_active', 1);
        }
        $mform->addElement('duration', 'duration', get_string('total_duration', 'block_user_sessions'));
        if($duration){
            $mform->setDefault('duration',$duration);

        }

        $mform->setType('userid', PARAM_RAW);
        $mform->setType('courseid', PARAM_RAW);

        $this->add_action_buttons();




//
    }
    //Custom validation should be added here


}
