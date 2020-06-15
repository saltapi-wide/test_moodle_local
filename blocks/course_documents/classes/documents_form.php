<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 4:09 μμ
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class block_course_documents_form extends moodleform
{

    public function definition()
    {
        $mform = $this->_form;

        $data = $this->_customdata['data'];
        $options = $this->_customdata['options'];

        $mform->addElement('filemanager', 'files_filemanager', get_string('files'), null, $options);
        $mform->addElement('hidden', 'returnurl', $data->returnurl);


        $mform->setType('returnurl', PARAM_LOCALURL);

        $this->add_action_buttons(true, get_string('savechanges'));

        $this->set_data($data);

    }

    /**
     * Form validation
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files)
    {

        $errors = array();
        $draftitemid = $data['files_filemanager'];
        if (file_is_draft_area_limit_reached($draftitemid, $this->_customdata['options']['areamaxbytes'])) {
            $errors['files_filemanager'] = get_string('userquotalimit', 'error');
        }

        return $errors;


    }


}