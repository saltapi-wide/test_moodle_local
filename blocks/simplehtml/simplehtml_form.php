<?php

require_once("{$CFG->libdir}/formslib.php");

class simplehtml_form extends moodleform {

    function definition() {

        global $PAGE;

        $mform =& $this->_form;

        $title = 'Settings';
        $PAGE->set_title($title);


        //$PAGE->set_context(context_system::instance());

        if (has_capability('block/simplehtml:addinstance', $PAGE->context)) {
            //do nothing
        }else{
            $url = '/moodle/';
            redirect($url, 'No Access! Redirected to Dashboard...', 10);
        }



        $mform->addElement('header','displayinfo', get_string('textfields', 'block_simplehtml'));


        // hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden', 'displaydate');

        $mform->setType('blockid', PARAM_RAW);
        $mform->setType('courseid', PARAM_RAW);
        $mform->setType('displaydate', PARAM_INT);
        //$mform->addRule('blockid', null, 'required', null, 'client');
        //$mform->addRule('courseid', null, 'required', null, 'client');


        // add page title element.
        $mform->addElement('text', 'pagetitle', get_string('pagetitle', 'block_simplehtml'));
        $mform->setType('pagetitle', PARAM_RAW);
        $mform->addRule('pagetitle', null, 'required', null, 'client');

        // add display text field
        $mform->addElement('editor', 'displaytext', get_string('displaytext', 'block_simplehtml'));
        $mform->setType('displaytext', PARAM_RAW);
        $mform->addRule('displaytext', null, 'required', null, 'client');

        //description
        $mform->addElement('text', 'description', get_string('description', 'block_simplehtml'));
        $mform->setType('description', PARAM_RAW);
        //$mform->addRule('text', null, '', null, 'client');

        $this->add_action_buttons();


    }
}
