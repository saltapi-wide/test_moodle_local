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

/**
 * User Sessions Block
 *
 * @package    block_coursecompletioncounter
 * @copyright  1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


class block_user_info_banner extends block_base {




    public function init() {
        $this->title = get_string('user_info_banner', 'block_user_info_banner');
    }
    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content() {
    if ($this->content !== null) {
      return $this->content;
    }

        global $DB,$THEME;
        global $COURSE;
        global $USER;
        global $PAGE,$CFG,$OUTPUT;

        $userid = $USER->id;

        require_login();

        $this->content         =  new stdClass;

        $PAGE->requires->css('/blocks/user_info_banner/styles/style.css');

        $name=$USER->firstname.' '.$USER->lastname;

        $sql = "SELECT ud.id,ud.data,uf.shortname
                FROM {user_info_data} ud
                LEFT JOIN {user_info_field} uf ON uf.id=ud.fieldid
                WHERE ud.userid={$userid} AND (uf.shortname='team' OR uf.shortname='department' OR uf.shortname='division')";
        $user_info = $DB->get_record_sql($sql);

        if($user_info){

            foreach($user_info AS $dato){
                if($dato->shortname=='team'){
                    $team=$dato->data;
                }else if($dato->shortname=='division'){
                    $division=$dato->data;
                }else if($dato->shortname=='department'){
                    $department=$dato->data;
                }
            }

        }else{
            $team='My Team';
            $division ='My Division';
            $department='My Department';
        }



        //dummy image
        $img="<img src='/moodle/blocks/user_info_banner/pix/user_pic_dummy.png' alt='test' title='Εικόνα Χρήστη'>";




        $div = "
        <div class='container_user_info container-fluid'>
        <div class='col-md-3 user_info' id='pix'>       
       ".$img."     
        </div>
        
        <div class='col-md-9 user_info' id='info'>
        <p class='name'>".$name."</p>
        
        
        <div class='extra_info'>
        
        <div class='team col-md-4' title='Team'><i class='fa fa-users fa-2x'></i><p>".$team."</p></div>
            
        <div class='division col-md-4' title='Division'><i class='fa fa-space-shuttle fa-2x'></i><p>".$division."</p></div>
       
        <div class='department col-md-4' title='Department'><i class='fa fa-building-o fa-2x'></i><p>".$department."</p></div>
        
        </div>
        
        
        </div>
        
        </div>
        
        ";

        $CFG->cachejs = false;

        $this->content->text = $div;


    return $this->content;

}


    public function specialization() {

        if (isset($this->config)) {
            if (empty($this->config->title)) {
                $this->title = get_string('defaultitle', 'block_user_info_banner');
            } else {
                $this->title = $this->config->title;
            }

        }
    }







}

