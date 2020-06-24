<?php

class block_user_sessions extends block_base {




    public function init() {
        $this->title = get_string('user_sessions', 'block_user_sessions');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }

    global $DB;
    global $COURSE;
    global $USER;
    global $PAGE;


        $dbman = $DB->get_manager();

        // Define table block_trainingsessions to be created.
        $table = new xmldb_table('user_sessions_settings');

        // Adding fields to table block_trainingsessions.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('active', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('min_duration_user', XMLDB_TYPE_INTEGER, '5', null, null, null, '3600');
        $table->add_field('max_duration', XMLDB_TYPE_INTEGER, '5', null, null, null, '3600');
        $table->add_field('grade_pass', XMLDB_TYPE_FLOAT, '5,2', null, null, null, '5.01');
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('date', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');



        // Adding keys to table training_sessions_settings
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_trainingsessions.
        if (!$dbman->table_exists($table)) {

            $dbman->create_table($table);

        }

 
        $this->content         =  new stdClass;

        if (is_siteadmin() && (has_capability('block/user_sessions:addinstance',$PAGE->context))) {

        $css = '<style>
    
        .block_user_sessions .card-title{
            color: blue;
            font-weight: bold;
        }
        .block_user_sessions{
            border-radius: 30px;
            max-height: 400px;
        }
        </style>';

        $this->content->text = $css.'This is the '.$COURSE->fullname.' course.';

        //anti gia footer vazw link



        $url1 = new moodle_url('/blocks/user_sessions/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
        //$url2 = new moodle_url('/blocks/simplehtml/view_datatable.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));

        //$this->content->footer = html_writer::link($url1, get_string('addpage', 'block_trainingsessions'),array('taget'=>'_blank'));
        //$this->content->footer.= html_writer::link($url2, get_string('datatable', 'block_trainingsessions'),array('taget'=>'_blank'));


        $this->content->footer = "<a class='btn btn-primary' href='{$url1}' target='_blank'>Settings</a>";


        //if (!has_capability('block/user_sessions:addinstance', $PAGE->context)) {


            //$this->title = false;
            //$this->content = false;
            //$this->content->text.= '<style> .block_user_sessions{display: none!important;} </style>';
        }else{
            $this->title = false;
            $this->content->text.= '<style> .block_user_sessions{display: none!important;} </style>';
        }


        return $this->content;


//    function _self_test() {
//        return true;
//    }

}


    public function specialization() {

        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaultitle', 'block_user_sessions');
            } else {
                $this->title = $this->config->title;
            }

        }
    }







}


