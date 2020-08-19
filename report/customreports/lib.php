<?php

function get_custom_reports_css($PAGE)
{
    $PAGE->requires->css('/report/customreports/styles/checkboxes.css');
    $PAGE->requires->css('/report/customreports/styles/jquery.dataTables.css');
    $PAGE->requires->css('/report/customreports/styles/select.dataTables.css');
    $PAGE->requires->css('/report/customreports/styles/responsive.dataTables.css');
    $PAGE->requires->css('/report/customreports/styles/responsive.jqueryui.css');
    $PAGE->requires->css('/report/customreports/styles/responsive.bootstrap.css');
    $PAGE->requires->css('/report/customreports/styles/responsive.bootstrap4.css');
    $PAGE->requires->css('/report/customreports/styles/custom.css');

}


//check daylight saving changes
function dts_check($date)
{
    //timestamp date
    $tmdate = strtotime($date);

    //get year
    $year = date('Y', $tmdate);
    $year = (int)$year;

    //this year last sunday october
    $tyoctobers = strtotime('last Sunday of October ' . $year);
    $tyoctobers = date('d-m-Y', $tyoctobers);
    //this year last sunday march
    $tymarchs = strtotime('last Sunday of March ' . $year);
    $tymarchs = date('d-m-Y', $tymarchs);

    if ($tmdate > strtotime($tymarchs)) {
        if ($tmdate < strtotime($tyoctobers)) {
            $tmdate = (int)$tmdate;
            $tmdate = $tmdate + 3600;
            $ndate = (int)$tmdate;
            return $ndate;
        } else if ($tmdate > strtotime($tyoctobers)) {
            $ndate = strtotime($date);
            return (int)$ndate;
        }
    } else if ($tmdate < strtotime($tymarchs)) {
        $ndate = strtotime($date);
        return (int)$ndate;
    }
}


function get_course_files($courses_helper)
{
    global $DB, $CFG;

    $course_files_list = array();
    $return_array = array();

//    $sql="SELECT f.id,f.filename,f.contextid,f.component,f.userid,f.timecreated,u.firstname,u.lastname,f.filepath
//          FROM {files} f
//          LEFT JOIN {user} u ON u.id=f.userid
//          WHERE NOT f.filename ='.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents')";

    $sql = "SELECT 
            course.mainid AS id,
            course.id AS CourseID, 
            course.fullname AS CourseFullName, 
            course.shortname AS CourseShortName, 
            course.filename, 
            course.filesize AS CourseSizeBytes,
            course.firstname,
            course.lastname,
            course.contextid,
            course.filepath,
            course.component,
            course.timecreated,
            course.instanceid AS contextinstanceid
            
            FROM (
            
            SELECT f.id mainid,c.id, c.fullname, c.shortname, cx.contextlevel,f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated, cx.instanceid
            FROM {context} cx
            JOIN {course} c ON cx.instanceid=c.id
            JOIN {files} f ON cx.id=f.contextid
            JOIN {user} u ON u.id=f.userid
            WHERE f.filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents')
            AND f.component NOT IN ('private', 'automated', 'backup','draft') {$courses_helper->extra_sql}
            
            UNION
            
            SELECT f.id mainid,cm.course, c.fullname, c.shortname, cx.contextlevel,f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated, cx.instanceid
            FROM {files} f
            JOIN {context} cx ON f.contextid = cx.id
            JOIN {course_modules} cm ON cx.instanceid=cm.id
            JOIN {course} c ON cm.course=c.id
            JOIN {user} u ON u.id=f.userid
            WHERE filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents') {$courses_helper->extra_sql}
            
            UNION
            
            SELECT f.id mainid,c.id, c.shortname, c.fullname, cx.contextlevel, f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated, cx.instanceid
            from {block_instances} bi
            join {context} cx on (cx.contextlevel=80 and bi.id = cx.instanceid)
            join {files} f on (cx.id = f.contextid)
            join {context} pcx on (bi.parentcontextid = pcx.id)
            join {course} c on (pcx.instanceid = c.id)
            JOIN {user} u ON u.id=f.userid
            where filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents')  {$courses_helper->extra_sql}
            
            ) AS course;";

    $files_all = $DB->get_records_sql($sql);

    foreach ($files_all as $file) {

        $course_files_list['filename'] = $file->filename;

        if (($file->firstname != NULL) && ($file->lastname != NULL)) {
            $course_files_list['uploaded_by'] = $file->firstname . ' ' . $file->lastname;
        } else {
            $course_files_list['uploaded_by'] = 'System';
        }

        if ($file->component != 'local_user_documents') {
            //$course_files_list['used_in'] = $file->component;

            $mod = explode('_', $file->component);
            $course_files_list['filelink'] = '/' . $mod[0] . '/' . $mod[1] . '/view.php?id=' . $file->contextinstanceid;
            /// mod/scorm/view.php?id=16

            $mod_sql = "SELECT mods.*
            FROM {files} f
            JOIN {context} cx ON f.contextid = cx.id
            JOIN {course_modules} cm ON cx.instanceid=cm.id
            JOIN {course} c ON cm.course=c.id
            JOIN {{$mod[1]}} mods ON mods.id=cm.instance
            WHERE f.id=?";
            $activity = $DB->get_record_sql($mod_sql, array($file->id));

            $course_files_list['used_in'] = $activity->name;
            $course_files_list['haslink'] = true;

        } else {
            $course_files_list['used_in'] = 'User Documents Plugin';
            $course_files_list['filelink'] = '';
            $course_files_list['haslink'] = false;
        }

        $course_files_list['time_created'] = userdate($file->timecreated);

        $return_array[] = $course_files_list;
    }


    return $return_array;
}

function get_courses_list_for_files()
{

    global $DB, $CFG;

    $result = new stdClass();

    $result->courseid = optional_param('courseid', '', PARAM_INT);

    $sql_course_list = "SELECT 
            course.id AS CourseID,
            course.fullname AS CourseFullName, 
            course.shortname AS CourseShortName
            
            FROM (
            
            SELECT c.id, c.fullname, c.shortname, cx.contextlevel,f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated
            FROM {context} cx
            JOIN {course} c ON cx.instanceid=c.id
            JOIN {files} f ON cx.id=f.contextid
            JOIN {user} u ON u.id=f.userid
            WHERE f.filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents')
            AND f.component NOT IN ('private', 'automated', 'backup','draft') AND c.id<>1
            GROUP BY c.id
            
            
            UNION
            
            SELECT cm.course, c.fullname, c.shortname, cx.contextlevel,f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated
            FROM {files} f
            JOIN {context} cx ON f.contextid = cx.id
            JOIN {course_modules} cm ON cx.instanceid=cm.id
            JOIN {course} c ON cm.course=c.id
            JOIN {user} u ON u.id=f.userid
            WHERE filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents') AND c.id<>1
            GROUP BY c.id
           
            
            UNION
            
            SELECT c.id, c.shortname, c.fullname, cx.contextlevel, f.component, f.filearea, f.filename, f.filesize, u.firstname, u.lastname,f.contextid, f.filepath, f.timecreated
            from {block_instances} bi
            join {context} cx on (cx.contextlevel=80 and bi.id = cx.instanceid)
            join {files} f on (cx.id = f.contextid)
            join {context} pcx on (bi.parentcontextid = pcx.id)
            join {course} c on (pcx.instanceid = c.id)
            JOIN {user} u ON u.id=f.userid
            where filename <> '.' AND (f.component LIKE '%mod_%' OR f.component='local_user_documents') AND c.id<>1
            GROUP BY c.id
        
            
            ) AS course ORDER BY CourseID ASC;";

    $course_list = $DB->get_records_sql($sql_course_list);

    $result->course_list = $course_list;

    //var_dump($course_list); exit;

    $result->first_course = array_key_first($course_list);

    if (($result->courseid == '') && (isset($result->first_course))) {
        $result->extra_sql = 'AND c.id=' . $result->first_course;
    } else if (($result->courseid == '') && (!isset($result->first_course))) {
        $result->extra_sql = '';
    } else {
        $result->extra_sql = 'AND c.id=' . $result->courseid;
    }

    //var_dump($result);exit;

    return $result;
}

function get_login_info(){

    global $DB, $CFG;
    $result = new stdClass();


    $sql = "SELECT u.id, u.firstname, u.lastname,  la.timeaccess
                FROM {user} u
                LEFT JOIN {user_lastaccess} la ON la.userid=u.id
                GROUP BY u.id
                ORDER BY u.id ASC";
    $result->users = $DB->get_records_sql($sql);

    $sql2 = "SELECT u.id AS user_id, log.id AS log_id, MIN(log.timecreated) AS min_login
                FROM {user} u
                LEFT JOIN {logstore_standard_log} log ON log.userid=u.id
                #WHERE log.action='loggedin'
                GROUP BY u.id
                ORDER BY u.id,log.id ASC";
    $result->users_login = $DB->get_records_sql($sql2);

    $sql3 = "SELECT u.id AS user_id, log.id AS log_id, MAX(log.timecreated) AS max_logout
                FROM {user} u
                LEFT JOIN {logstore_standard_log} log ON log.userid=u.id
                #WHERE log.action='loggedout'
                GROUP BY u.id
                ORDER BY u.id,log.id ASC";
    $result->users_logout = $DB->get_records_sql($sql3);

    $sql4 = "SELECT log.id, u.id AS user_id, log.timecreated
                FROM {logstore_standard_log} log 
                LEFT JOIN {user} u ON u.id=log.userid
                GROUP BY log.id
                ORDER BY user_id,log.id ASC";
    $result->logs = $DB->get_records_sql($sql4);

    return $result;
}


