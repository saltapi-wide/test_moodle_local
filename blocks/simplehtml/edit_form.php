<?php
 
class block_simplehtml_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
 
        // Section header title according to language file.
        $mform->addElement('header', 'config_header', get_string('blocksettings', 'block_simplehtml'));

        //var_dump(get_string('blocksettings', 'block')); exit;
 
        // A sample string variable with a default value.
        // $mform->addElement('text', 'config_text', get_string('blockstring', 'block_simplehtml'));
        // $mform->setDefault('config_text', 'default value');
        // $mform->setType('config_text', PARAM_RAW); 

        //title

        $mform->addElement('text', 'config_title', get_string('title', 'block_simplehtml'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);

//        $mform->addElement('text', 'config_text', get_string('text', 'block_simplehtml'));
//        $mform->setDefault('config_text', 'default value');
//        $mform->setType('config_text', PARAM_TEXT);

    

 
    }
}