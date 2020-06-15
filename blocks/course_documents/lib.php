<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 2:57 μμ
 */


defined('MOODLE_INTERNAL') || die();

function get_user_by_id($userid)
{

    $userdata = get_complete_user_data('id', $userid);

    return $userdata;
}

function get_documents_name($contextid, $component, $filearea, $userid)
{

    $names = [];
    $fs = get_file_storage();
    $documents_name = $fs->get_area_files($contextid, $component, $filearea, $userid, 'filename', false);
    foreach ($documents_name as $document_name) {
        $names[] = $document_name->get_filename();
    }

    return $names;
}

function get_documents_info($contextid, $component, $filearea, $userid)
{

    global $DB, $OUTPUT;

    $fs = get_file_storage();

    $selectdoc = get_string('selectdoc', 'block_course_documents');

    if ($files = $fs->get_area_files($contextid, $component, $filearea, $userid, 'filename', false)) {
        $data1 = array();
        $data2 = array();
        $data3 = array();
        foreach ($files as $file) {

            // Build the File URL. Long process! But extremely accurate.
            $fileurl = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);
            // Display link for file download
            $download_url = $fileurl->get_port() ? $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path() . ':' . $fileurl->get_port() : $fileurl->get_scheme() . '://' . $fileurl->get_host() . $fileurl->get_path();
            $fullpath = '<a href="' . $download_url . '">' . $file->get_filename() . '</a><br/>';

            $timecreated = $file->get_timecreated();
            $timemodified = $file->get_timemodified();

            $data1[] = $fullpath;
            $data2[] = date('d/m/Y', $timecreated);
            $data3[] = date('d/m/Y', $timemodified);

        }

        $data = ['file' => $data1, 'timecreated' => $data2, 'timemodified' => $data3];
        return $data;

    } else {
        return 1;
    }


}


function block_course_documents_extend_settings_navigation($settingsnav, $context)
{
    global $CFG, $PAGE;

    // Only add this settings item on non-site course pages.
    if (!$PAGE->course or $PAGE->course->id == 1) {
        return;
    }

    // Only let users with the appropriate capability see this settings item.
    /*if (!has_capability('moodle/backup:backupcourse', context_course::instance($PAGE->course->id))) {
        return;
    }*/

    $courseid = $PAGE->course->id;

    $coursenode = $PAGE->navigation->find($courseid, navigation_node::TYPE_COURSE);
    $thingnode = $coursenode->add(get_string('cdocuments', 'block_course_documents'), new moodle_url('/blocks/user_documents/documents.php', array('cid' => $PAGE->course->id)));
    $thingnode->make_active();


    if ($settingnode = $settingsnav->find('courseadmin', navigation_node::TYPE_COURSE)) {
        $strfoo = get_string('cdocuments', 'block_course_documents');
        $url = new moodle_url('/blocks/course_documents/foo.php', array('id' => $PAGE->course->id));
        $documentsnode = navigation_node::create(
            $strfoo,
            $url,
            navigation_node::NODETYPE_LEAF,
            'user_documents',
            'myplugin',
            new pix_icon('t/addcontact', $strfoo)
        );


        $documentsnode->showinflatnavigation = true;


        if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
            $documentsnode->make_active();
        }
        $settingnode->add_node($documentsnode);
    }
}


function block_course_documents_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array())
{
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'files') {
        return false;
    }

    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    if (!has_capability('block/course_documents:view_course_documents', $context)) {
        return false;
    }

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.


    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/' . implode('/', $args) . '/'; // $args contains elements of the filepath
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_course_documents', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    $forcedownload = 0;
    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    send_stored_file($file, 0, 0, $forcedownload, $options);
}


