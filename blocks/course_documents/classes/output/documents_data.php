<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 2:56 μμ
 */


namespace block_course_documents\output;

defined('MOODLE_INTERNAL') || die();

use context_user;
use moodle_url;
use renderer_base;

class documents_data implements \templatable, \renderable
{

    /** @var int */
    protected $userid;
    protected $courseid;

    /**
     * entries_list constructor.
     * @param int $userid
     */


    public $context;
    public $dir;

    public function __construct($userid, $courseid)
    {

        $this->userid = $userid;
        $this->courseid = $courseid;

        $this->context_course = \context_course::instance($courseid);
        $fs = get_file_storage();
        $this->dir = $fs->get_area_tree($this->context_course->id, 'block_course_documents', 'files', $userid);

    }

    /**
     * Implementation of exporter from templatable interface
     *
     * @param renderer_base $output
     * @return array
     */
    public function export_for_template(renderer_base $output)
    {

        global $CFG, $USER, $PAGE, $OUTPUT;
        require_once(__DIR__ . '/../../../../config.php');
        require_once(__DIR__ . '/../../lib.php');
        require_once(__DIR__ . '/../../../../user/files_form.php');
        require_once(__DIR__ . '/../../../../repository/lib.php');

        $user = get_user_by_id($this->userid);

        $context = context_user::instance($this->userid);
        $contextid = $context->id;

        $context_course = \context_course::instance($this->courseid);
        $context_course_id = $context_course->id;

        $url = new moodle_url('/pluginfile.php/' . $contextid . '/user/icon/boost/f1?rev');
        $return_url = new moodle_url('/course/view.php?', ['id' => $this->courseid]);
        $mdocuments_url = new moodle_url('/blocks/course_documents/documents.php?', ['cid' => $this->courseid]);

        $nofile = get_string('uploadfirst', 'block_course_documents');


        $documents_name = get_documents_name($context_course_id, 'block_course_documents', 'files', $user->id);
        $documents_info = get_documents_info($context_course_id, 'block_course_documents', 'files', $user->id);
        $backtocourse = get_string('btcourse', 'block_course_documents');
        $mdocuments = get_string('managecd', 'block_course_documents');
        $return_btn = "<a href=$return_url><button class='btn btn-warning private_files'>$backtocourse</button></a>";
        $mdocuments_btn = "<a href=$mdocuments_url><button class='btn btn-warning private_files'>$mdocuments</button></a>";


        if ($documents_info == 1) {
            $data = ['imageurl' => format_string($url . '=' . $user->picture, true, ['context' => $context]),
                'user_fullname' => format_string("$user->lastname $user->firstname", true, ['context' => $context])];
            $data['nodata'] = $nofile;
            $data['documents_header'] = 'Available Documents';
        } else {
            $data = ['imageurl' => format_string($url . '=' . $user->picture, true, ['context' => $context]),
                'user_fullname' => format_string("$user->lastname $user->firstname", true, ['context' => $context])];
            $data['documents_name'] = $documents_name;
            $data['documents'] = $documents_info;
            $data['documents_header'] = 'Available Documents';
            $data['documentname'] = 'Document Name';
            $data['timecr'] = 'Date Created';
            $data['timemo'] = 'Date Modified';
        }

        $data['returnbtn'] = $return_btn;
        $data['mdocuments_btn'] = $mdocuments_btn;

        return $data;

    }


}