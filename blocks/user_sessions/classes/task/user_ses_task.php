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
                WHERE u.id > 2 AND courseid > 1
                GROUP BY log.id,co.id 
                ORDER BY log.id,userid ASC";
        $sessions_all = $DB->get_records_sql($sql);

        $sql2 = 'SELECT uen.id, uen.enrolid enrolid, uen.userid userid,en.courseid,se.active,se.min_duration_user,se.max_duration,
                uen.timestart,uen.timecreated,la.timeaccess,la.id lastaccessid
                FROM {user_enrolments} uen
                LEFT JOIN {enrol} en ON en.id=uen.enrolid
                LEFT JOIN {user_sessions_settings} se ON se.courseid=en.courseid
                LEFT JOIN {user_lastaccess} la ON la.userid=uen.userid AND la.courseid=en.courseid
                WHERE uen.timeend=0 OR uen.timeend > NOW()
                ORDER BY uen.id ASC';
        $user_enrolments = $DB->get_records_sql($sql2);

        //var_dump($user_enrolments); exit;


        $sql3 = "SELECT cm.id,cm.course courseid,cm.module,m.name,se.active,se.min_duration_user,se.max_duration,m.name,co.startdate
                FROM {course_modules} cm
                LEFT JOIN {user_sessions_settings} se ON se.courseid=cm.course
                LEFT JOIN {modules} m ON m.id=cm.module
                LEFT JOIN {course} co ON co.id=se.courseid
                WHERE NOT m.name='label' AND se.active=1
                ORDER BY cm.id ASC";
        $activities = $DB->get_records_sql($sql3);

        // userid,
        // courseid,
        // total

        $totals = array (
            array()
        );

        $prev_key = 0;

        foreach($sessions_all AS $key=>$session){

            if (array_key_exists($session->userid, $totals)) {
                //echo "Array Key exists...";
                if( (intval($sessions_all[$prev_key]->timecreated) > 0)
                    && ( abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated)) < $sessiontimeout)
                    && (intval($session->timecreated) > intval($sessions_all[$prev_key]->timecreated) )
                ) {
                    $result_helper = abs(intval($session->timecreated) - intval($sessions_all[$prev_key]->timecreated));
                }else{
                    $result_helper = 0;
                }

                $totals[$session->userid][$session->courseid] = intval($totals[$session->userid][$session->courseid]) + intval($result_helper);

            } else {
                //echo "Array Key does not exist...";
                $totals[$session->userid][$session->courseid] = 0;
            }

            $prev_key = $key;


        }

        unset($totals[0]);
        //var_dump($totals);



        foreach ($user_enrolments AS $enrolment){


            if ($enrolment->timeaccess == NULL) {
                $last_time = intval($enrolment->timecreated);
            }else{
                $last_time = $enrolment->timeaccess;
            }

            $count_activities = count($activities);


            if( (intval($totals[$enrolment->userid][$enrolment->courseid]) < intval($enrolment->max_duration))){

                //echo 'MIKROTERO';
                //var_dump($enrolment);

                $loop = 0;
                $flag_big_activity = 0;

                foreach($activities AS $key=>$activity){

                    $loop = $loop + 1;

                    //echo 'count '.$count_activities.' loop '.$loop;
                    //echo '<br>';

                    if(($activity->active == '1')
                        && ($activity->courseid == $enrolment->courseid)
                        && (intval($totals[$enrolment->userid][$enrolment->courseid]) < intval($enrolment->max_duration))
                        //&& ($activity->name != 'label')
                    ){

                        $context = \context_module::instance($activity->id);
                        $contextid = $context->id;

                        $count_activities = count($activities);
                        $min=1;
                        $max=$enrolment->min_duration_user;

                        $random_int = intval($enrolment->min_duration_user) + intval(rand($min,$max));
                        //$random_int = $max - $key  ;
                        //$last_time = intval($random_int + $last_time);
                        $time_inserted = $random_int + intval($last_time) + $random_int * $flag_big_activity;
                        $last_time = $time_inserted;

                        $insertion_log = new \stdClass();
                        $insertion_log->eventname = '\mod_'.$activity->name.'\event\course_module_viewed';
                        $insertion_log->component = 'mod_'.$activity->name;
                        $insertion_log->action = 'viewed';
                        $insertion_log->target = 'course_module';
                        $insertion_log->objecttable= $activity->name;
                        $insertion_log->objectid='1';
                        $insertion_log->crud= 'r';
                        $insertion_log->edulevel= '2';
                        $insertion_log->contextid= $contextid;
                        $insertion_log->contextinstanceid= $activity->id;
                        $insertion_log->contextlevel= '70';
                        $insertion_log->userid= $enrolment->userid;
                        $insertion_log->courseid= $activity->courseid;
                        $insertion_log->relateduserid= NULL;
                        $insertion_log->anonymous= '0';
                        $insertion_log->other= 'N;';
                        //$insertion_log->timecreated = time() - $random_int;
                        $insertion_log->timecreated = $time_inserted;
                        $insertion_log->origin= 'web';
                        $insertion_log->ip= '127.0.0.1';
                        $insertion_log->realuserid= NULL;

                        //echo '<h2>'.$time_inserted.'</h2><br>';

                        $insert_id = $DB->insert_record('logstore_standard_log', $insertion_log);


                        //check if activity is quiz or scorm

                        if(($activity->name == 'quiz') || ($activity->name == 'scorm')){
                            $flag_big_activity = 1;
                        }else{
                            $flag_big_activity = 0;
                        }

                        //end check


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

                        $duration_total = $last_time + $random_int;


                        echo 'random duration courseid: '.$activity->courseid.' userid: '.$enrolment->userid;
                        echo '<br>';


                    }
                }

            }

        }





    }


}