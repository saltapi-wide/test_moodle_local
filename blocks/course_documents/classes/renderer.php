<?php
/**
 * Created by PhpStorm.
 * User: moustis.p
 * Date: 22/5/2019
 * Time: 2:52 μμ
 */

defined('MOODLE_INTERNAL') || die();

use block_course_documents\output\documents_data;



class block_course_documents_renderer extends plugin_renderer_base {

    /**
     * Renders an entries list.
     *
     * @param entries_list $list
     * @return string HTML
     */
    protected function render_documents_data(documents_data $outputpage) {
        $data = $outputpage->export_for_template($this);
        return $this->render_from_template('block_course_documents/documents_data', $data);
    }

}