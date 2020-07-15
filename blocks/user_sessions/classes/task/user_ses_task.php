<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


namespace block_user_sessions\task;

class user_ses_task extends \core\task\scheduled_task {

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('user_ses_task_title', 'block_user_sessions');
    }

    /**
     * Run users sync.
     */
    public function execute() {


        error_reporting(0);

        global $CFG,$DB;

        $sql = "SELECT name,value
			FROM {config}
			WHERE name='sessiontimeout'";
        $configs = $DB->get_record_sql($sql);

        if(isset($configs) && ($configs->value != '')){
            echo 'Config SessionTimeout: '.$configs->value;
            $sessiontimeout = $configs->value;
            echo '<br>';
        }else{
            echo 'Config SessionTimeout: NOT SET';
            $sessiontimeout = 7200;
            echo '<br>';
        }

        $sql = "SELECT log.id logid,u.id userid,u.firstname,u.lastname,u.username,log.eventname,log.timecreated,log.courseid courseid, 
                co.shortname course_shortname,log.component, log.eventname,co.startdate
                FROM {logstore_standard_log} log
                LEFT JOIN {user} u ON u.id=log.userid
                LEFT JOIN {course} co ON log.courseid=co.id
                WHERE u.id > 2
                GROUP BY log.id,co.id 
                ORDER BY userid,logid ASC";
        $sessions_all = $DB->get_records_sql($sql);

        //AND courseid > 1


        $sql2 = 'SELECT uen.id, uen.enrolid enrolid, uen.userid userid,en.courseid,se.active,se.min_duration_user,se.max_duration,
                uen.timestart,uen.timeend,uen.timecreated,la.timeaccess,la.id lastaccessid,
                c.startdate,c.enddate
                FROM {user_enrolments} uen
                LEFT JOIN {enrol} en ON en.id=uen.enrolid
                LEFT JOIN {user_sessions_settings} se ON se.courseid=en.courseid
                LEFT JOIN {user_lastaccess} la ON la.userid=uen.userid AND la.courseid=en.courseid
                LEFT JOIN {course} c ON c.id=en.courseid
                WHERE (uen.timeend=0 OR uen.timeend > NOW()) AND se.max_duration > 0
                ORDER BY uen.id ASC';
        $user_enrolments = $DB->get_records_sql($sql2);

        //var_dump($user_enrolments); exit;


//        $sql3 = "SELECT cm.id,cm.course courseid,cm.module,m.name,se.active,se.min_duration_user,se.max_duration,m.name,co.startdate,cm.visible
//                FROM {course_modules} cm
//                LEFT JOIN {user_sessions_settings} se ON se.courseid=cm.course
//                LEFT JOIN {modules} m ON m.id=cm.module
//                LEFT JOIN {course} co ON co.id=se.courseid
//                WHERE NOT m.name='label' AND se.active=1
//                ORDER BY cm.id ASC";
//        $activities = $DB->get_records_sql($sql3);

        // userid,
        // courseid,
        // total

        $totals = array (
            array()
        );

        $prev_key = 0;

        foreach($sessions_all AS $key=>$session){

            if ((isset($totals[$session->userid][$session->courseid]))&&( ($session->courseid > 1))) {
                //echo "Array Key exists...";

                if( (intval($sessions_all[$prev_key]->timecreated) > 0)
                    && ( abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated)) < $sessiontimeout)
                    && (intval($session->timecreated) > intval($sessions_all[$prev_key]->timecreated) )
                ) {
                    $result_helper = abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated));
//                    $result_helper = $result_helper + $result_helper*3/10;
                }else{
                    $result_helper = 1000;
                }
                $totals[$session->userid][$session->courseid] = intval($totals[$session->userid][$session->courseid]) + intval($result_helper);
            } else if((isset($totals[$session->userid][$session->courseid])) && ($session->courseid <= 1)){
                //logout or other
                $totals[$session->userid][$session->courseid] = intval($totals[$session->userid][$session->courseid]) + abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated));
                //$totals[$session->userid][$session->courseid]= $totals[$session->userid][$session->courseid] + abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated))*3/10;
            }else if ((!isset($totals[$session->userid][$session->courseid]))&&( ($session->courseid > 1))) {
                //echo "Array Key does not exist...";
                $totals[$session->userid][$session->courseid] = 20;
            }
            $prev_key = $key;
        }

        //var_dump($user_enrolments);

        unset($totals[0]);
        var_dump($totals);



        foreach ($user_enrolments AS $enrolment){


            if ($enrolment->timeaccess == NULL) {
                $last_time = intval($enrolment->timestart);
            }else{
                $last_time = $enrolment->timeaccess;
            }
            $start_session_time = $last_time;


            //orismos xronou gia kathe working session start

            //user_enroloment
            // time

            //course
            // date

            if (intval($enrolment->timestart) <= intval($enrolment->startdate)){
                $start_course = intval($enrolment->startdate);
            }else{
                $start_course = intval($enrolment->timestart);
            }

            $end_course = intval($enrolment->enddate);

            //echo "start: ".$start_course." end: ".$end_course;
            //mtrace("");

            if(($end_course > 0) && ($end_course > time())) {
                $time_per_course = intval($end_course - $start_course);
            }else{
                $time_per_course = $end_course - time();
            }

            //echo "time_per_course: ".$time_per_course;
            //mtrace("");

            $random_time_total = 0;


            //$count_activities = count($activities);


            if(  (intval($totals[$enrolment->userid][$enrolment->courseid]) < intval($enrolment->max_duration))){

                //echo 'MIKROTERO';
                //var_dump($enrolment);


                $sql3 = "SELECT cm.id,cm.course courseid,cm.module,m.name,se.active,se.min_duration_user,se.max_duration,m.name,co.startdate,cm.visible
                FROM {course_modules} cm
                LEFT JOIN {user_sessions_settings} se ON se.courseid=cm.course
                LEFT JOIN {modules} m ON m.id=cm.module
                LEFT JOIN {course} co ON co.id=se.courseid
                WHERE NOT m.name='label' AND se.active=1 AND co.id={$enrolment->courseid}
                ORDER BY cm.id ASC";
                $activities = $DB->get_records_sql($sql3);

                $loop = 0;
                $flag_big_activity = 0;
                $day_change = 0;

                $count_activities = count($activities);

                //working sessions start

                $time_per_working_session =3600;
                $session_now = 0;
                //$time_per_working_session = ($enrolment->max_duration / $time_per_course)*60;


//                if($time_per_working_session < 1800){
//                    $time_per_working_session = 1800;
//                }

                //echo "limit per session: ".$time_per_working_session;
                //mtrace(" ");

                //working sessions end


                //split activity time
                $split_activity_loops = round($enrolment->min_duration_user / 1800);

                if($split_activity_loops >= 1){
                    $enrolment->min_duration_user = $enrolment->min_duration_user / $split_activity_loops;
                    echo "min_duration: ".$enrolment->min_duration_user;
                    mtrace(" ");
                }else{
                    $split_activity_loops = 1;
                }

                foreach($activities AS $key=>$activity){

                    $loop = $loop + 1;
                    $start_session_time = $last_time;

                    //echo 'count '.$count_activities.' loop '.$loop;

                    if(($activity->active == '1')
                        && ($activity->courseid == $enrolment->courseid)
                        && (intval($totals[$enrolment->userid][$enrolment->courseid]) < intval($enrolment->max_duration))
                        && ($activity->visible == '1')
                        //&& ($random_time_total <= intval($totals[$enrolment->userid][$enrolment->courseid]))
                    ){

                        $context = \context_module::instance($activity->id);
                        $contextid = $context->id;

                        for ($i = 1; $i <= ($split_activity_loops+3*$flag_big_activity); $i++) {

                            $min = 1;
                            $max = $enrolment->min_duration_user;

                            $random_int = intval(rand($min, $max));
                            //$time_inserted = $max + $random_int + intval($last_time) + $day_change + $flag_big_activity*$random_int;
                            $time_inserted = $random_int + intval($last_time) + $day_change;
                            $last_time = $time_inserted;


                            if(intval($last_time-$start_session_time) <= $sessiontimeout) {
                                $session_now = $session_now + intval($last_time-$start_session_time);
                                $random_time_total = $session_now;
                            }



                            mtrace($last_time);
                            mtrace($start_session_time);
                            mtrace($session_now);
                            $day_change = 0;

                            $insertion_log = new \stdClass();
                            $insertion_log->eventname = '\mod_' . $activity->name . '\event\course_module_viewed';
                            $insertion_log->component = 'mod_' . $activity->name;
                            $insertion_log->action = 'viewed';
                            $insertion_log->target = 'course_module';
                            $insertion_log->objecttable = $activity->name;
                            $insertion_log->objectid = '1';
                            $insertion_log->crud = 'r';
                            $insertion_log->edulevel = '2';
                            $insertion_log->contextid = $contextid;
                            $insertion_log->contextinstanceid = $activity->id;
                            $insertion_log->contextlevel = '70';
                            $insertion_log->userid = $enrolment->userid;
                            $insertion_log->courseid = $activity->courseid;
                            $insertion_log->relateduserid = NULL;
                            $insertion_log->anonymous = '0';
                            $insertion_log->other = 'N;';
                            //$insertion_log->timecreated = time() - $random_int;
                            $insertion_log->timecreated = $time_inserted;
                            $insertion_log->origin = 'web';
                            $insertion_log->ip = '127.0.0.1';
                            $insertion_log->realuserid = NULL;

                            //echo '<h2>'.$time_inserted.'</h2><br>';

                            $insert_id = $DB->insert_record('logstore_standard_log', $insertion_log);



                            //check if activity is quiz or scorm
                            if(($activity->name == 'quiz') || ($activity->name == 'scorm')){
                                $flag_big_activity = 1;
                            }else{
                                $flag_big_activity = 0;
                            }
                            //end check

                            $random_time_total = $random_time_total+$session_now;

                            if($session_now > $time_per_working_session){
                                $random_int_day = intval(rand(10800,108000));
                                $day_change = $random_int_day;
                                $session_now = 0;
                                $start_session_time = $last_time;
                                mtrace("ALLAKSE H MERA");
                            }

                        }


                        $random_int = intval(rand($min,$max));

                        if (($count_activities-$loop) == 0){

                            $context = \context_user::instance($enrolment->userid);
                            $contextid_gen = $context->id;

                            $insertion_log->eventname = '\core\event\user_loggedout';
                            $insertion_log->component = 'core';
                            $insertion_log->action = 'loggedout';
                            $insertion_log->target = 'user';
                            $insertion_log->objecttable= 'user';
                            $insertion_log->objectid= NULL;
                            $insertion_log->crud= 'r';
                            $insertion_log->edulevel= '2';
                            $insertion_log->contextid= $contextid_gen;
                            $insertion_log->contextinstanceid= '0';
                            $insertion_log->contextlevel= '10';
                            $insertion_log->userid= $enrolment->userid;
                            $insertion_log->courseid= '0';
                            $insertion_log->relateduserid= NULL;
                            $insertion_log->anonymous= '0';
                            $insertion_log->other= 'N;';
                            //$insertion_log->timecreated = time() - $random_int;
                            $insertion_log->timecreated = $last_time + $random_int + $random_int * $flag_big_activity;
                            $insertion_log->origin= 'web';
                            $insertion_log->ip= '127.0.0.1';
                            $insertion_log->realuserid= NULL;

                            //echo '<h1>'.intval($last_time + $random_int).'</h1><br>';

                            //var_dump($insertion_log);

                            $insert_id_last = $DB->insert_record('logstore_standard_log', $insertion_log);



                            if ($enrolment->timeaccess == NULL) {


                                $insert_access = new \stdClass();
                                $insert_access->userid = $enrolment->userid;
                                $insert_access->courseid = $enrolment->courseid;
                                $insert_access->timeaccess = $last_time + $random_int + 10 + $random_int * $flag_big_activity;

                                $insert_report = $DB->insert_record('user_lastaccess', $insert_access);

                            }else{

                                $insert_access = new \stdClass();

                                $insert_access->id = $enrolment->lastaccessid;
                                $insert_access->userid = $enrolment->userid;
                                $insert_access->courseid = $enrolment->courseid;
                                $insert_access->timeaccess = $last_time + $random_int + 10 + $random_int * $flag_big_activity;

                                //$insert_report = $DB->insert_record('user_lastaccess', $insert_access);
                                $DB->update_record('user_lastaccess', $insert_access);

                            }

                            //echo '<h1> LAST INSERTION: ' . $insert_report . '</h1><br>';


                        }

                        //$duration_total = $last_time + $random_int;


//                        echo 'random duration courseid: '.$activity->courseid.' userid: '.$enrolment->userid;
//                        echo '<br>';


                    }
                }

                echo 'random duration: '.$random_time_total.' courseid: '.$activity->courseid.' userid: '.$enrolment->userid;
                mtrace(" ");


            }

        }





    }


}