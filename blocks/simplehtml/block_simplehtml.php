<?php

class block_simplehtml extends block_base {

	
    public function init() {
        $this->title = get_string('simplehtml', 'block_simplehtml');
    }
    // The PHP tag and the curly bracket for the class definition 
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }

    global $DB; global $COURSE;  global $USER;

        $dbman = $DB->get_manager();

        // Define table block_simplehtml to be created.
        $table = new xmldb_table('block_simplehtml');

        // Adding fields to table block_simplehtml.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('blockid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('pagetitle', XMLDB_TYPE_CHAR, '25', null, XMLDB_NOTNULL, null, null);
        $table->add_field('displaytext', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('format', XMLDB_TYPE_INTEGER, '3', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('filename', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('picture', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('displaypicture', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('displaydate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table block_simplehtml.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for block_simplehtml.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);

        }else if ( $dbman->table_exists($table) && !$dbman->field_exists($table,'pagetitle') ){
            $dbman->drop_table($table);
            $dbman->create_table($table);

        }


        //echo "bla bla";exit;

        // Simplehtml savepoint reached.
        //upgrade_block_savepoint(true, 2011062801, 'simplehtml');





        //$user = $DB->get_record('user', ['id' => '2']);
        //$user = $DB->get_record_sql('SELECT COUNT(*) FROM {user};');

 
        $this->content         =  new stdClass;



        //style of block_simplehtml block

        $css = '<style>
    
        .block_simplehtml .card-title{
            color: blue;
            font-weight: bold;
        }
        .block_simplehtml{
            border-radius: 30px;
            max-height: 400px;
        }
        </style>';


        $this->content->text = $css.'This is the '.$COURSE->fullname.' course.';

        //anti gia footer vazw link


//      $this->content->more = '<p>'.$this->config->text.'</p>';

        $time = new DateTime("now", core_date::get_user_timezone_object());
        $time->add(new DateInterval("P1D"));
        $time->setTime(15, 0, 0);

        $timestamp = $time->getTimestamp();

        $this->content->text.= '<h1>'.$timestamp.'</h1>';
        $this->content->text.= '<h1>'.userdate($timestamp).'</h1>';



        $url1 = new moodle_url('/blocks/simplehtml/view.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));
        $url2 = new moodle_url('/blocks/simplehtml/view_datatable.php', array('blockid' => $this->instance->id, 'courseid' => $COURSE->id));

        //$this->content->footer = html_writer::link($url1, get_string('addpage', 'block_simplehtml'),array('taget'=>'_blank'));
        //$this->content->footer.= html_writer::link($url2, get_string('datatable', 'block_simplehtml'),array('taget'=>'_blank'));

        $this->content->footer = "<a class='btn btn-primary' href='{$url1}' target='_blank'>Settings</a><a class='btn btn-danger' href='{$url2}' target='_blank'>Sessions </a>";




        //var_dump($this->content); exit;


    return $this->content;
}


public function specialization() {

    if (isset($this->config)) {
        if (empty($this->config->title)) {
            $this->title = get_string('defaultitle', 'block_simplehtml');
        } else {
            $this->title = $this->config->title;
        }

//        if (empty($this->config->text)) {
//            $this->text = get_string('defaultext', 'block_simplehtml');
//        } else {
//            $this->text = $this->config->text;
//        }

//        if (empty($this->config->more)) {
//            $this->more = get_string('text', 'block_simplehtml');
//        } else {
//            $this->more = $this->config->more;
//        }


        /*var_dump($this->config);
        echo get_string('defaultitle', 'block_simplehtml');
        echo "<br>";
        echo get_string('defaultext', 'block_simplehtml');
        exit;
        */
		   
    }
}





}


