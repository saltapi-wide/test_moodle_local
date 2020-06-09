<?php

require_once("{$CFG->libdir}/formslib.php");

class datatable_form extends moodleform {

    function definition() {

        global $DB,$PAGE,$USER;

        //$PAGE->set_context(context_system::instance());

        if (has_capability('block/simplehtml:addinstance', $PAGE->context)) {
            //do nothing
        }else{
            $url = '/moodle/';
            redirect($url, 'No Access! Redirected to Dashboard...', 10);
        }

        $title = 'Datatable Form';
        $PAGE->set_title($title);


        $courseid = required_param('courseid', PARAM_INT);



        $mform =& $this->_form;

        //$mform->title = 'Your Block';

        //global $OUTPUT, $COURSE;
        //$display = $OUTPUT->heading('123 bla');
        //$display.= $OUTPUT->box_start();
        //$OUTPUT->box_start();

        $mform->addElement('header','displayinfo','Ένα τυχαίο όνομα');




        // hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->addElement('hidden', 'courseid');
        $mform->addElement('hidden', 'displaydate');

        $mform->setType('blockid', PARAM_RAW);
        $mform->setType('courseid', PARAM_RAW);
        $mform->setType('displaydate', PARAM_RAW);
        //$mform->addRule('blockid', null, 'required', null, 'client');
        //$mform->addRule('courseid', null, 'required', null, 'client');


        // add page title element.
        //$mform->addElement('text', 'pagetitle', get_string('pagetitle', 'block_simplehtml'));
        //$mform->setType('pagetitle', PARAM_RAW);
        //$mform->addRule('pagetitle', null, 'required', null, 'client');


        //test

        $html_users_table = '<div class="col-md-12 row">';

//        $sql = 'SELECT *
//			FROM {user}
//			WHERE NOT id=1 LIMIT 20;';

        $swsta = "Πέρασε";
        $lathos = "Κόπηκε";

        /*$sql = 'SELECT u.id id_student,u.username,u.firstname,u.lastname, question.name, CASE WHEN qas.state = "gradedright" OR qas.fraction = qatt.maxmark THEN 1 ELSE 0 END correct
	    FROM mdl_quiz AS q
		JOIN mdl_course_modules AS cm ON cm.instance = q.id
		JOIN mdl_quiz_attempts qa ON q.id = qa.quiz
			and qa.id = (
				select id
				from mdl_quiz_attempts
				where quiz = q.id
					and userid = u.id
				ORDER BY sumgrades DESC
				LIMIT 1
			)
		JOIN mdl_user AS u ON u.id = qa.userid
		JOIN mdl_question_usages AS qu ON qu.id = qa.uniqueid
		JOIN mdl_question_attempts AS qatt ON qatt.questionusageid = qu.id
		JOIN mdl_question AS question ON question.id = qatt.questionid
		JOIN mdl_question_attempt_steps AS qas ON qas.questionattemptid = qatt.id
			and qas.id = (
			    SELECT sortqas.id
			    FROM mdl_question_attempt_steps sortqas
			    WHERE sortqas.questionattemptid = qatt.id
				ORDER BY sortqas.timecreated DESC
				LIMIT 1
		    )
	    JOIN mdl_course crs ON crs.id = cm.course';
        //ORDER BY qas.sequencenumber LIMIT 20';
        */


        /*
        $sql = 'SELECT u.id id_student,u.username,u.firstname,u.lastname, question.name, qas.state, CASE WHEN qas.state = "gradedright" OR qas.fraction = qatt.maxmark THEN 1 ELSE 0 END correct
	    FROM mdl_quiz AS q
		JOIN mdl_course_modules AS cm ON cm.instance = q.id
		JOIN mdl_quiz_attempts qa ON q.id = qa.quiz
		JOIN mdl_user AS u ON u.id = qa.userid
		JOIN mdl_question_usages AS qu ON qu.id = qa.uniqueid
		JOIN mdl_question_attempts AS qatt ON qatt.questionusageid = qu.id
		JOIN mdl_question AS question ON question.id = qatt.questionid
		JOIN mdl_question_attempt_steps AS qas ON qas.questionattemptid = qatt.id
	    JOIN mdl_course crs ON crs.id = cm.course
        WHERE crs.id = 4';

        */

//        $sql = "SELECT u.id student_id,u.firstname,u.lastname
//                FROM mdl_user u
//                LEFT JOIN mdl_question_attempt_steps att ON u.id=att.userid
//                WHERE att.state='gradedright'";
        //echo $sql; exit;


        //ola ta grades gia olous tous xristes kai ola ta courses
        $sql = "SELECT gra.id, u.id student_id, c.id course_id, cc.id course_category_id, 
                u.firstname,u.lastname ,u.username, grai.gradepass, grai.grademin ,grai.grademax, gra.finalgrade, c.fullname
                FROM {grade_grades} gra
                INNER JOIN {grade_items} grai ON grai.id=gra.itemid
                INNER JOIN {user} u ON u.id=gra.userid
                INNER JOIN {role_assignments} ra ON ra.userid = u.id
                
                INNER JOIN {course} c ON c.id = grai.courseid
                INNER JOIN {role} r ON r.id = ra.roleid
                INNER JOIN {course_categories} cc ON cc.id = c.category
                WHERE c.id={$courseid}
                GROUP BY gra.id";
               // WHERE c.id='.$courseid;
                //GROUP BY  course_id,student_id';

        /*
         $sql='SELECT u.id student_id, c.id course_id, cc.id course_category_id, u.firstname,u.lastname , grai.gradepass, grai.grademin ,grai.grademax, max(gra.finalgrade), c.fullname
                FROM mdl_user u
                INNER JOIN mdl_grade_grades gra ON gra.userid=u.id
                INNER JOIN mdl_grade_items grai ON grai.id=gra.itemid AND grai.courseid = 3
                INNER JOIN mdl_course c ON grai.courseid=c.id
                INNER JOIN mdl_course_categories cc ON cc.id=c.category
                GROUP BY  course_id,student_id';
         */

        $users_info = $DB->get_records_sql($sql);


        //$correct = 'swsta';
        foreach ($users_info AS $user ){

            if ( intval($user->finalgrade) >= intval($user->gradepass)){
                $correct = $swsta;
            }else{
                $correct = $lathos;
            }



            $html_users_table.= '<div class="col-md-3"><ul>
            <l>ID:'.$user->id.'</l>
            <li>result: '.$user->finalgrade.' pass: '.$user->gradepass.'</li>
    		<li><strong>Ονοματεπώνυμο </strong> : '. $user->firstname.' '.$user->lastname.' και ID : '.$user->student_id.'</li>
    		<li>'.$correct.'</li>
    		</ul></div><br>';
        }

        $html_users_table.= '</div>';

        $mform->addElement('html', $html_users_table);


        //end test

        // add display text field
        //$mform->addElement('editor', 'displaytext', get_string('displaytext', 'block_simplehtml'));
        //$mform->setType('displaytext', PARAM_RAW);
        //$mform->addRule('displaytext', null, 'required', null, 'client');

        //description
        //$mform->addElement('text', 'description', get_string('description', 'block_simplehtml'));
        //$mform->setType('description', PARAM_RAW);
        //$mform->addRule('description', null, '', null, 'client');

        $this->add_action_buttons();


    }
}
