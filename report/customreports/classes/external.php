<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

class report_customreports_external extends external_api
{

    public static function get_courses_types_parameters()
    {
        return new external_function_parameters(
            array(
                'startdate' => new external_value(PARAM_RAW, 'Date of the start of set dates', VALUE_REQUIRED),
                'enddate' => new external_value(PARAM_RAW, 'Date of the end of set dates', VALUE_REQUIRED)
            )
        );
    }

//returns the cnus that have a nominating list(meaning they have nominated users for an event)
    public static function get_courses_types($startdate, $enddate)
    {
        global $DB;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_courses_types_parameters(), [
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
        $startdate = $params['startdate'];
        $enddate = $params['enddate'];
        global $DB, $USER;
        $period = new DatePeriod(
            new DateTime($startdate),
            new DateInterval('P1D'),
            new DateTime($enddate . '+1 day')
        );
        $dates = array();
        foreach ($period as $date) {
            $dates[] = $date->format('d-m-Y ');
        }

        $courses_content = array();
        $courses_list = array();


        foreach ($dates as $date) {
            $ndate = dts_check($date);
            $nextdate = (int)$ndate + 86399;
            $coursessql = 'Select * from {course} c WHERE startdate>"' . $ndate . '" AND startdate<"' . $nextdate . '" AND visible=1';
            $courses = $DB->get_records_sql($coursessql);
            $courses_content['timestamp'] = $ndate;
            $courses_content['date'] = date('d/m/Y', $ndate);
            $coursescount = 0;
            $conferencescount = 0;
            $meetingscount = 0;
            $missioncount = 0;
            $exchangecount = 0;
            $ejmpcount = 0;
            $omcount = 0;
            $occount = 0;
            $seminarcount = 0;
            $webseriescount = 0;
            $recordedwebcount = 0;
            $livewebcount = 0;
            $cyberbitescount = 0;
            $vtccount = 0;
            $residentialcount = 0;
            $platformcount = 0;

            foreach ($courses as $course) {
                $field = get_course_fields($course->id, array('event_type'));
                if ($field['event_type'] == 'Course') {
                    $coursescount++;
                } else if ($field['event_type'] == 'Conference') {
                    $conferencescount++;
                } else if ($field['event_type'] == 'Meeting') {
                    $meetingscount++;
                } else if ($field['event_type'] == 'Mission') {
                    $missioncount++;
                } else if ($field['event_type'] == 'Exchange (CEP)') {
                    $exchangecount++;
                } else if ($field['event_type'] == 'European Joint Master Programme (EJMP)') {
                    $ejmpcount++;
                } else if ($field['event_type'] == 'Online Module') {
                    $omcount++;
                } else if ($field['event_type'] == 'Online Course') {
                    $occount++;
                } else if ($field['event_type'] == 'Seminar') {
                    $seminarcount++;
                } else if ($field['event_type'] == 'Webinar Series') {
                    $webseriescount++;
                } else if ($field['event_type'] == 'Recorded Webinar') {
                    $recordedwebcount++;
                } else if ($field['event_type'] == 'Live Webinar') {
                    $livewebcount++;
                } else if ($field['event_type'] == 'Cyber bites') {
                    $cyberbitescount++;
                } else if ($field['event_type'] == 'VTC') {
                    $vtccount++;
                } else if ($field['event_type'] == 'Residential Course') {
                    $residentialcount++;
                } else if ($field['event_type'] == 'Platform') {
                    $platformcount++;
                }
            }
            $courses_content['coursescount'] = $coursescount;
            $courses_content['conferencescount'] = $conferencescount;
            $courses_content['meetingscount'] = $meetingscount;
            $courses_content['missionscount'] = $missioncount;
            $courses_content['exchangecount'] = $exchangecount;
            $courses_content['ejmpcount'] = $ejmpcount;
            $courses_content['omcount'] = $omcount;
            $courses_content['occount'] = $occount;
            $courses_content['seminarcount'] = $seminarcount;
            $courses_content['webseriescount'] = $webseriescount;
            $courses_content['recordedwebcount'] = $recordedwebcount;
            $courses_content['livewebcount'] = $livewebcount;
            $courses_content['cyberbitescount'] = $cyberbitescount;
            $courses_content['vtccount'] = $vtccount;
            $courses_content['residentialcount'] = $residentialcount;
            $courses_content['platformcount'] = $platformcount;
            $courses_list[] = $courses_content;
        }
        return $courses_list;
    }

    public static function get_courses_types_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'timestamp' => new external_value(PARAM_RAW, 'Timestamp ( sorting reasons)'),
                    'date' => new external_value(PARAM_RAW, 'Date'),
                    'coursescount' => new external_value(PARAM_RAW, 'Courses counter'),
                    'conferencescount' => new external_value(PARAM_RAW, 'Conferences counter'),
                    'meetingscount' => new external_value(PARAM_RAW, 'Meetings counter'),
                    'missionscount' => new external_value(PARAM_RAW, 'Missions counter'),
                    'exchangecount' => new external_value(PARAM_RAW, 'Exchange counters'),
                    'ejmpcount' => new external_value(PARAM_RAW, 'EJMP counter'),
                    'omcount' => new external_value(PARAM_RAW, 'online module counter'),
                    'occount' => new external_value(PARAM_RAW, 'online course counter'),
                    'seminarcount' => new external_value(PARAM_RAW, 'Seminar counter'),
                    'webseriescount' => new external_value(PARAM_RAW, 'Webinar series counter'),
                    'recordedwebcount' => new external_value(PARAM_RAW, 'Recorded webinar counter'),
                    'livewebcount' => new external_value(PARAM_RAW, 'Live webinar counter'),
                    'cyberbitescount' => new external_value(PARAM_RAW, 'Cyber bites  counter'),
                    'vtccount' => new external_value(PARAM_RAW, 'VTC  counter'),
                    'residentialcount' => new external_value(PARAM_RAW, 'Residential courses  counter'),
                    'platformcount' => new external_value(PARAM_RAW, 'Platform  counter'),
                )
            )
        );
    }

    public static function get_general_gotowebinar_parameters()
    {
        return new external_function_parameters(
            array(
                'num' => new external_value(PARAM_RAW, 'Dummy number', VALUE_REQUIRED)
            )
        );
    }

//returns the cnus that have a nominating list(meaning they have nominated users for an event)
    public static function get_general_gotowebinar($num)
    {
        global $DB;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_general_gotowebinar_parameters(), [
            'num' => $num
        ]);

        global $DB, $USER;

        global $DB;
        $webinars = get_webinars('general_gotowebinar', true, 0, false);

        $report_array = array();
        $array_data = array();

        foreach ($webinars as $webinar) {

            $webinar_users = get_webinar_attendees($webinar);

            $registrands = count($webinar_users);
            $attendees = 0;
            $early_leavers = 0;
            foreach ($webinar_users as $user) {
                //if user is in webinar for 20 minutes or more then count them.
                if ($user / 60 >= 20) {
                    $attendees++;
                } else if ($user / 60 < 20) {
                    $early_leavers++;
                }
            }

            $organizer = get_webinar($webinar, false, array('organizerName'));


            $panelists_users = get_webinar_panelists($webinar, false, array('name', 'lastName'));


            $panelists = '';
            if (sizeof($panelists_users) > 0) {
                foreach ($panelists_users as $user) {
                    $panelists .= $user['name'] . ' ' . $user['lastName'] . '<br>';
                }
            } else {
                $panelists = 'No panelists';
            }

            $array_data['name'] = $webinar['subject'];
            $array_data['panelists'] = $panelists;
            $array_data['organizer'] = $organizer['organizerName'];
            $array_data['registrands'] = $registrands;
            $array_data['early_leavers'] = $early_leavers;
            $array_data['attendees'] = $attendees;
            $report_array[] = $array_data;


        }

        return $report_array;
    }

    public static function get_general_gotowebinar_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'name' => new external_value(PARAM_RAW, 'Timestamp ( sorting reasons)'),
                    'panelists' => new external_value(PARAM_RAW, 'Date'),
                    'organizer' => new external_value(PARAM_RAW, 'Courses counter'),
                    'registrands' => new external_value(PARAM_RAW, 'Conferences counter'),
                    'early_leavers' => new external_value(PARAM_RAW, 'Webinars counter'),
                    'attendees' => new external_value(PARAM_RAW, 'Meetings counter')

                )
            )
        );
    }

    public static function get_trained_officers_cats_parameters()
    {
        return new external_function_parameters(
            array(
                'parentcat' => new external_value(PARAM_RAW, 'Parent category', VALUE_REQUIRED),
                'startdate' => new external_value(PARAM_RAW, 'Start date of query', VALUE_REQUIRED),
                'enddate' => new external_value(PARAM_RAW, 'End date of query', VALUE_REQUIRED)
            )
        );
    }

//returns the thematic areas data for the trained_officers report
    public static function get_trained_officers_cats($parentcat, $startdate, $enddate)
    {
        global $DB;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_trained_officers_cats_parameters(), [
            'parentcat' => $parentcat,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
        $parentcat = $params['parentcat'];
        $startdate = $params['startdate'];
        $enddate = $params['enddate'];

        global $DB, $USER;

        global $DB;
        $webinarssql = 'SELECT * FROM {gotowebinar_data} gtw WHERE gtw.timestart>=' . $startdate . ' AND gtw.timestart<=' . $startdate;
        $webinar_data = $DB->get_records_sql($webinarssql);
        $gotowebinartype = $DB->get_record('lti_types', array('name' => 'Go2Webinar'));
        $cats = $DB->get_records('course_categories', array('visible' => 1, 'parent' => $parentcat));
        $report_array = array();
        $array_data = array();

        foreach ($cats as $cat) {
            $cat_data = array();
            //get courses that are in the thematic area(category)
            $cat_courses = $DB->get_records('course', array('category' => $cat->id));
            $cat_course_webs = 0;
            $cat_course_attendees = 0;
            foreach ($cat_courses as $course) {
                //if it does contain gotowebinar lts
                if ($ltis = $DB->get_records('lti', array('course' => $course->id, 'typeid' => $gotowebinartype->id))) {
                    foreach ($ltis as $lti) {
                        foreach ($webinar_data as $webinar) {
                            if ($lti->name == $webinar->name) {
                                $cat_course_webs++;
                                $cat_course_attendees += $webinar->attendees;
                                unset($webinar);
                            }
                        }
                    }

                } else {
                    continue;
                }
            }


            //get the subthematics in that category
            $subcats = $DB->get_records('course_categories', array('visible' => 1, 'parent' => $cat->id));
            foreach ($subcats as $subcat) {
                //get the subthematics courses
                $subcat_courses = $DB->get_records('course', array('category' => $cat->id));
                $subcat_course_webs = 0;
                $subcat_course_attendees = 0;
                foreach ($subcat_courses as $course) {
                    //if it does contain gotowebinar lts
                    if ($ltis = $DB->get_records('lti', array('course' => $course->id, 'type' => $gotowebinartype->id))) {
                        foreach ($ltis as $lti) {
                            foreach ($webinar_data as $webinar) {
                                if ($lti->name == $webinar->name) {
                                    $subcat_course_webs++;
                                    $subcat_course_attendees += $webinar->attendees;
                                    unset($webinar);
                                }
                            }
                        }

                    } else {
                        continue;
                    }
                }
            }
            $array_data['categoryname'] = $cat->name;
            $array_data['webinarscount'] = $cat_course_webs + $subcat_course_webs;
            $array_data['attendeescount'] = $cat_course_attendees + $subcat_course_attendees;
            $report_array[] = $array_data;
        }

        return $report_array;
    }

    public static function get_trained_officers_cats_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'categoryname' => new external_value(PARAM_RAW, 'Category name'),
                    'webinarscount' => new external_value(PARAM_RAW, 'Webinars counter'),
                    'attendeescount' => new external_value(PARAM_RAW, 'Attendees counter')


                )
            )
        );
    }

    public static function get_trained_officers_courses_parameters()
    {
        return new external_function_parameters(
            array(
                'parentcat' => new external_value(PARAM_RAW, 'Parent category', VALUE_REQUIRED),
                'startdate' => new external_value(PARAM_RAW, 'Start date of query', VALUE_REQUIRED),
                'enddate' => new external_value(PARAM_RAW, 'End date of query', VALUE_REQUIRED)
            )
        );
    }

//returns the thematic areas data for the trained_officers report
    public static function get_trained_officers_courses($parentcat, $startdate, $enddate)
    {
        global $DB;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_trained_officers_courses_parameters(), [
            'parentcat' => $parentcat,
            'startdate' => $startdate,
            'enddate' => $enddate
        ]);
        $parentcat = $params['parentcat'];
        $startdate = $params['startdate'];
        $enddate = $params['enddate'];

        global $DB, $USER;

        global $DB;
        $webinarssql = 'SELECT * FROM {gotowebinar_data} gtw WHERE gtw.startdate>=' . $startdate . ' AND gtw.startdate<=' . $startdate;
        $webinar_data = $DB->get_records_sql($webinarssql);
        $gotowebinartype = $DB->get_record('lti_types', array('name' => 'Go2Webinar'));

        $report_array = array();
        $array_data = array();


        $cat_data = array();
        //get courses that are in the thematic area(category)
        $cat_courses = $DB->get_records('course', array('category' => $parentcat));

        foreach ($cat_courses as $course) {
            $course_attendees = 0;
            //if it does contain gotowebinar lts
            if ($ltis = $DB->get_records('lti', array('course' => $course->id, 'type' => $gotowebinartype->id))) {
                foreach ($ltis as $lti) {
                    foreach ($webinar_data as $webinar) {
                        if ($lti->name == $webinar->name) {

                            $course_attendees += $webinar->attendees;
                            unset($webinar);

                        }
                    }
                }
                $array_data['coursename'] = $course->fullname;
                $array_data['attendeescount'] = $course_attendees;
                $report_array[] = $array_data;
            } else {
                continue;
            }


        }

        return $report_array;
    }

    public static function get_trained_officers_courses_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'coursename' => new external_value(PARAM_RAW, 'Category name'),
                    'attendeescount' => new external_value(PARAM_RAW, 'Attendees counter')


                )
            )
        );
    }


    //get number of attendees start


    public static function get_no_of_attendees_parameters()
    {
        return new external_function_parameters(
            array(
                'startdate' => new external_value(PARAM_RAW, 'Date of the start of set dates', VALUE_REQUIRED),
                'enddate' => new external_value(PARAM_RAW, 'Date of the end of set dates', VALUE_REQUIRED),
                'thematic' => new external_value(PARAM_RAW, 'Date of the end of set dates', VALUE_REQUIRED),
                'subthematic' => new external_value(PARAM_RAW, 'Date of the end of set dates', VALUE_REQUIRED),
                'table_class' => new external_value(PARAM_RAW, 'Table Class of Report Table', VALUE_REQUIRED),
            )
        );
    }


    public static function get_no_of_attendees($startdate, $enddate, $thematic_id, $subthematic_id, $table_class)
    {
        global $DB, $USER;

        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_no_of_attendees_parameters(), [
            'startdate' => $startdate,
            'enddate' => $enddate,
            'thematic' => $thematic_id,
            'subthematic' => $subthematic_id,
            'table_class' => $table_class
        ]);

        $thematic_id = intval($thematic_id);
        $subthematic_id = intval($subthematic_id);


        $startdate = $params['startdate'];
        $enddate = $params['enddate'];

        $startdate = strtotime($startdate);
        $enddate = strtotime($enddate);

        if ($startdate == '') {
            $startdate = time();
        }

        if ($enddate == '') {
            $enddate = time();
        }
        $enddate = intval($enddate) + 86399;


        $return = array();

        $thematic_content = array();
        $subthematic_content = array();
        $course_content = array();

        $thematic_list = array();
        $subthematic_list = array();
        $course_list = array();

        if ($table_class == 'thematic_area') {

            $thematic_sql = "SELECT cc.id, cc.name,gtw.attendees,cc.coursecount,gtw.webinarKey,gtw.organizer,cc.parent AS main_parent,cat.id AS sec_id,
                                CASE
                                    WHEN cc.parent = 0 THEN cc.name
                                    WHEN cc.parent <> 0 THEN cat.name
                                END AS main_cat_name
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           INNER JOIN {course_categories} cat ON cat.id=cc.parent OR cc.parent = 0
                           WHERE c.startdate >= {$startdate} AND gtw.timestart >= {$startdate} AND gtw.timestart < {$enddate}
                           AND (c.enddate > {$enddate} || c.enddate='0')
                           GROUP BY cc.id";

            //AND gtw.timestart <= {$startdate} AND gtw.timestart >= {$enddate}

            $thematic_all = $DB->get_records_sql($thematic_sql);

            //$return[0]['all'] = json_encode($webinars);

            $webinar_metr = 0;
            $arrendees_metr = 0;

            foreach ($thematic_all as $thematic) {

                $thematic_content['id'] = $thematic->id;

                $webinar_count_sql = "SELECT COUNT(gtw.id) AS webinarcount,SUM(gtw.attendees) AS attendees_sum
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           WHERE cc.id=" . $thematic->id;
                $webinar_count = $DB->get_record_sql($webinar_count_sql);

                //$thematic_content['name'] = "<a href='?thematicid=".$thematic->id."'>".$thematic->name."</a>";

                if ($thematic->main_parent != 0) {
                    $thematic_content['name'] = "<button class='thematic_btn btn btn-secondary' id='" . $thematic->sec_id . "'>" . $thematic->main_cat_name . "</button>";
                } else {
                    $thematic_content['name'] = "<button class='subthematic_btn btn btn-secondary' id='" . $thematic->id . "'>" . $thematic->main_cat_name . "</button>";
                }

                $thematic_content['webinarcount'] = $webinar_count->webinarcount;
                $thematic_content['attendees'] = $webinar_count->attendees_sum;

                $webinar_metr = $webinar_metr + intval($webinar_count->webinarcount);
                $arrendees_metr = $arrendees_metr + intval($webinar_count->attendees_sum);

                $thematic_list[] = $thematic_content;

            }

            $return['values'] = $thematic_list;
            $return['table'] = "<table style='width:400px;margin: 30px;'>
                              <tr>
                                <th>Number of total Webinars:</th>
                                <td>" . $webinar_metr . "</td>
                              </tr>
                              <tr>
                                  <th>Total Number of attendees:</th>
                                <td>" . $arrendees_metr . "</td>
                              </tr>
                            </table>";

            $return['title'] = 'Thematic Area';

        } else if ($table_class == 'subthematic_area') {

            $subthematic_sql = "SELECT cc.id, cc.name,gtw.attendees,cc.coursecount,gtw.webinarKey,gtw.organizer
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           WHERE c.startdate >= {$startdate} AND gtw.timestart >= {$startdate} AND gtw.timestart < {$enddate}
                           AND (c.enddate > {$enddate} || c.enddate='0')
                           AND cc.parent={$thematic_id}
                           GROUP BY cc.id";

            // WHERE c.startdate >= {$startdate} AND gtw.timestart >= {$startdate} AND gtw.timestart < {$enddate}
            // AND (c.enddate <= {$enddate} || c.enddate='0')

            $subthematic_all = $DB->get_records_sql($subthematic_sql);

            //$return[0]['all'] = json_encode($webinars);

            $webinar_metr = 0;
            $arrendees_metr = 0;

            foreach ($subthematic_all as $subthematic) {

                $subthematic_content['id'] = $subthematic->id;

                $webinar_count_sql = "SELECT COUNT(gtw.id) AS webinarcount, SUM(gtw.attendees) AS attendees_sum
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           
                           WHERE cc.id={$subthematic->id}";
                $webinar_count = $DB->get_record_sql($webinar_count_sql);

                //$thematic_content['name'] = "<a href='?thematicid=".$thematic->id."'>".$thematic->name."</a>";
                $subthematic_content['name'] = "<button class='subthematic_btn btn btn-secondary' id='" . $subthematic->id . "'>" . $subthematic->name . "</button>";
                $subthematic_content['webinarcount'] = $webinar_count->webinarcount;
                $subthematic_content['attendees'] = $webinar_count->attendees_sum;

                $webinar_metr = $webinar_metr + intval($webinar_count->webinarcount);
                $arrendees_metr = $arrendees_metr + intval($webinar_count->attendees_sum);

                $subthematic_list[] = $subthematic_content;

            }

            $return['values'] = $subthematic_list;
            $return['table'] = "<table style='width:400px;margin: 30px;'>
                              <tr>
                                <th>Number of total Webinars:</th>
                                <td>" . $webinar_metr . "</td>
                              
                              </tr>
                              <tr>
                                  <th>Total Number of attendees:</th>
                                <td>" . $arrendees_metr . "</td>
                              </tr>
                            </table>";

            $return['title'] = 'Subthematic Area';

        } else if ($table_class == 'attendees') {

            $courses_sql = "SELECT c.id, c.fullname
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           WHERE c.startdate >= {$startdate} AND gtw.timestart >= {$startdate} AND gtw.timestart < {$enddate}
                           AND (c.enddate > {$enddate} || c.enddate='0')
                           AND (cc.parent={$subthematic_id} || cc.parent={$thematic_id})
                           GROUP BY c.id";

            $courses_all = $DB->get_records_sql($courses_sql);

            foreach ($courses_all as $course) {

                $course_content['id'] = $course->id;

                $webinar_count_sql = "SELECT COUNT(gtw.id) AS webinarcount, SUM(gtw.attendees) AS attendees_sum
                           FROM {gotowebinar_data} gtw
                           INNER JOIN {lti} l ON l.name=gtw.name
                           INNER JOIN {course} c ON c.id=l.course
                           INNER JOIN {course_categories} cc ON cc.id=c.category
                           
                           WHERE c.id={$course->id}";

                //WHERE c.startdate >= {$startdate} AND gtw.timestart >= {$startdate} AND gtw.timestart < {$enddate}
                //AND (c.enddate < {$enddate} || c.enddate=0)
                //AND (cc.parent={$subthematic_id} || cc.parent={$thematic_id})

                $webinar_count = $DB->get_record_sql($webinar_count_sql);

                //$thematic_content['name'] = "<a href='?thematicid=".$thematic->id."'>".$thematic->name."</a>";
                $course_content['name'] = $course->fullname;
                $course_content['webinarcount'] = $webinar_count->webinarcount;
                $course_content['attendees'] = $webinar_count->attendees_sum;

                $course_list[] = $course_content;

            }
            $return['values'] = $course_list;
            $return['table'] = '';
            $return['title'] = 'Attendees Area';

        } else {
            $return[] = false;
        }

        return $return;
    }

    public static function get_no_of_attendees_returns()
    {
        return
            new external_single_structure(
                array(
                    'title' => new external_value(PARAM_RAW, 'Area Title'),
                    'table' => new external_value(PARAM_RAW, 'Table values'),
                    'values' => new external_multiple_structure(
                        new external_single_structure(
                            array(
                                'id' => new external_value(PARAM_RAW, 'Thematic Id'),
                                'name' => new external_value(PARAM_RAW, 'Thematic/Subthematic name'),
                                'webinarcount' => new external_value(PARAM_RAW, 'Webinar counter'),
                                'attendees' => new external_value(PARAM_RAW, 'Attendees counter')
                            )
                        )
                    )
                )
            );
    }


    public static function get_webinar_questions_parameters()
    {
        return new external_function_parameters(
            array(
                'cnu' => new external_value(PARAM_TEXT, 'If the user viewing is cnu', VALUE_REQUIRED),

            )
        );
    }

//returns the thematic areas data for the trained_officers report
    public static function get_webinar_questions($cnu)
    {
        global $DB, $USER;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_webinar_questions_parameters(), [
            'cnu' => $cnu
        ]);
        $cnu = $params['cnu'];

        $questionsql = 'SELECT gq.id, gq.questiontext, gd.name FROM {gotowebinar_questions} gq INNER JOIN {gotowebinar_data} gd ON gq.webinarid=gd.id';
        $webinars=$DB->get_records('gotowebinar_data');

        $questions = $DB->get_records_sql($questionsql);
        $webinar_list = array();
        $webinar_content = array();
        if($cnu){
            $org=$DB->get_record('organization_user',array('userid'=>$USER->id));
        }
        foreach ($webinars as $webinar){
            $webinar_content['id']=$webinar->id;
            $webinar_content['webinarname']=$webinar->name;
            $questions=$DB->get_records('gotowebinar_questions',array('webinarid'=>$webinar->id));
            $questiontext='';
            foreach ($questions as $question){
                if ($cnu){
                    if(!$DB->record_exists('organization_user',array('userid'=>$question->askedby,'organizationid'=>$org->organizationid))){
                        continue;
                    }
                }
                $question='<u><span class="question" data-id="'.$question->id.'">'.$question->questiontext.'</span></u>';
                $questiontext.=$question.'<br>';
            }
            if($questiontext==''){
                $questiontext='No questions asked';
            }
            $webinar_content['questiontext']=$questiontext;
            $webinar_list[]=$webinar_content;
        }



        return $webinar_list;
    }

    public static function get_webinar_questions_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Webinar id'),
                    'webinarname' => new external_value(PARAM_TEXT, 'Webinar name'),
                    'questiontext' => new external_value(PARAM_TEXT, 'Question text')
                )
            )
        );
    }

    public static function get_webinar_question_answers_parameters()
    {
        return new external_function_parameters(
            array(
                'cnu' => new external_value(PARAM_BOOL, 'If the user viewing is cnu', VALUE_REQUIRED),
                'questionid' => new external_value(PARAM_INT, 'Id of the question', VALUE_REQUIRED)

            )
        );
    }

//returns the thematic areas data for the trained_officers report
    public static function get_webinar_question_answers($cnu, $questionid)
    {
        global $DB, $USER;
        require_once(__DIR__ . '/../lib.php');
        $params = self::validate_parameters(self::get_webinar_question_answers_parameters(), [
            'cnu' => $cnu,
            'questionid' => $questionid
        ]);
        $cnu = $params['cnu'];
        $questionid = $params['questionid'];
        $question = $DB->get_record('gotowebinar_questions', array('id' => $questionid));
        $data['questiontext'] = $question->questiontext;
        $answers = $DB->get_records('gotowebinar_question_answers', array('questionid' => $questionid));
        $answers_list = array();
        $answers_content = array();
        foreach ($answers as $answer) {

            $answers_content['id'] = $answer->id;
            $answers_content['answertext'] = $answer->answertext;
            $answers_content['answeredby'] = $answer->answeredby;
            $answers_list[] = $answers_content;
        }
        $data['data'] = $answers_list;
        return $data;
    }

    public static function get_webinar_question_answers_returns()
    {
        return new external_single_structure(
            array(
                'questiontext' => new external_value(PARAM_TEXT, 'Question text'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Question id'),
                            'answertext' => new external_value(PARAM_TEXT, 'Webinar name'),
                            'answeredby' => new external_value(PARAM_TEXT, 'Question text')
                        )
                    )
                )
            )
        );
    }


}