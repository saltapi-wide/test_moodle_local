<?php

require_once("{$CFG->libdir}/formslib.php");

class block_user_sessions_form extends moodleform {

    function definition() {

        global $PAGE,$COURSE;

        //var_dump($COURSE); exit;

        $mform =& $this->_form;

        $title = get_string('settings','block_user_sessions');
        $PAGE->set_title($title);

        $courseid = required_param('courseid', PARAM_INT);



        //$PAGE->set_context(context_system::instance());

        //if (has_capability('block/user_sessions:addinstance', $PAGE->context)) {
        if (is_siteadmin() && (has_capability('block/user_sessions:addinstance',$PAGE->context))) {
            //do nothing
        }else{
            $url = '/my/';
            redirect($url, 'No Access! Redirected to Dashboard...', 10);
        }



        //$mform->addElement('header','displayinfo', get_string('textfields', 'block_user_sessions'));


        // hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden', 'date');

        $mform->setType('blockid', PARAM_RAW);
        $mform->setType('courseid', PARAM_RAW);
        $mform->setType('date', PARAM_INT);

        $savedAutoGenValue = $this->getAutoGenerateValue($courseid);

        $options = array(0 => get_string('no'), 1 => get_string('yes'));
        $elemenet_select = $mform->addElement('select', 'active', get_string('auto_generate', 'block_user_sessions'), $options);
        $elemenet_select->setSelected($savedAutoGenValue->active);
        $mform->setType('auto_generate', PARAM_BOOL);

        //$mform->addElement('time_selector', 'mytime', get_string('total_duration', 'block_user_sessions'));

        $duration = $savedAutoGenValue->total_duration;
        $dur = $mform->addElement('duration', 'total_duration', get_string('total_duration', 'block_user_sessions'));
        //$dur->setValue(intval($duration));
        $mform->setDefault('total_duration', $duration);

//        $num_of_sessions = $savedAutoGenValue->num_of_sessions;
//
//        $mform->addElement('text', 'num_of_sessions', get_string('num_of_sessions', 'block_user_sessions'));
//        $mform->addRule('num_of_sessions', 'Numeric', 'numeric', null, 'client');
//        $mform->setDefault('num_of_sessions', $num_of_sessions);



        $this->add_action_buttons();
    }

    public function getAutoGenerateValue($courseid){

        global $DB;

        $sql = 'SELECT *
			FROM {user_sessions_settings} 
			WHERE courseid=?';

        $result = $DB->get_record_sql($sql,array($courseid));

        //$result = array_pop($result);

        $return = new stdClass();

        if ($result) {
            $return->active = $result->active;
            $return->total_duration = $result->max_duration;
            //$return->num_of_sessions = $result->num_of_sessions;
        }else{
            $return->active = 0;
            $return->total_duration = 0;
            //$return->num_of_sessions = 0;
        }


        return $return;
    }
}
