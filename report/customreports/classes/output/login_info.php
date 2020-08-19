<?php

namespace report_customreports\output;

defined('MOODLE_INTERNAL') || die();

use renderer_base;
use moodle_url;


class login_info implements \templatable, \renderable
{


    public function export_for_template(renderer_base $output)
    {

        $users_info = get_login_info();
        $users_list = array();
        $item = array();



        $totals = array();
        $prev_key = 0;
        foreach($users_info->logs AS $key=>$session){

            if ((isset($totals[$session->user_id]))) {
                //echo "Array Key exists...";

                if( (intval($users_info->logs[$prev_key]->timecreated) > 0)
                    && ( abs(intval($session->timecreated) - intval($users_info->logs[$prev_key]->timecreated)) < 7200)
                    && (intval($session->timecreated) > intval($users_info->logs[$prev_key]->timecreated) )
                ) {
                    $result_helper = abs(intval($session->timecreated) - intval($users_info->logs[$prev_key]->timecreated));
                }else{
                    $result_helper = 900;
                }
                $totals[$session->user_id] = intval($totals[$session->user_id]) + intval($result_helper);
            } else if(isset($totals[$session->user_id])){
                //logout or other
                $totals[$session->user_id] = intval($totals[$session->user_id]) + abs(intval($session->timecreated) - intval($users_info->logs[$prev_key]->timecreated));
            }else if (!isset($totals[$session->user_id])) {
                //echo "Array Key does not exist...";
                $totals[$session->user_id] = 0;
            }
            $prev_key = $key;
        }
        unset($totals[0]);




        foreach($users_info->users AS $user){

            $item['id'] = $user->id;
            $item['firstname'] = $user->firstname;
            $item['lastname'] = $user->lastname;

            if(intval($user->timeaccess) > 0 ){

                if(intval($users_info->users_login[$user->id]->min_login) > 0) {
                    $item['login_date'] = userdate($users_info->users_login[$user->id]->min_login);
                }else{
                    $item['login_date'] = get_string('never','report_customreports');
                }

                if(intval($users_info->users_logout[$user->id]->max_logout) > 0) {
                    $item['logout_date'] = userdate($users_info->users_logout[$user->id]->max_logout);
                }else{
                    $item['logout_date'] = get_string('never','report_customreports');
                }


                if($totals[$user->id] > 0){
                    $item['login_time'] = format_time($totals[$user->id]);
                    $item['total_time'] = format_time($totals[$user->id]);
                }else{
                    $item['login_time'] = get_string('null','report_customreports');
                    $item['total_time'] = get_string('null','report_customreports');
                }


            }else{
                $item['login_date'] = get_string('never','report_customreports');
                $item['logout_date'] = get_string('never','report_customreports');
                $item['login_time'] = get_string('null','report_customreports');
                $item['total_time'] = get_string('null','report_customreports');
            }



            $users_list[] = $item;
        }


        $data = [
            'users_info' => $users_list
        ];

        return $data;
    }
}