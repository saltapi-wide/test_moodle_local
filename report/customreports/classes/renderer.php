<?php
/**
 * Class tool_exchange_renderer
 *
 * @package    block_exchange
 * @copyright  2019 Yiannis Lagos WIDE Services
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_customreports\output;
defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;


class renderer extends plugin_renderer_base
{
    public function render_main(main $main) {
        return $this->render_from_template('report_customreports/main', $main->export_for_template($this));
    }
    public function render_course_files(course_files $course_files) {
        return $this->render_from_template('report_customreports/course_files', $course_files->export_for_template($this));
    }

    public function render_category_files(login_info $login_info) {
        return $this->render_from_template('report_customreports/login_info', $login_info->export_for_template($this));
    }


}