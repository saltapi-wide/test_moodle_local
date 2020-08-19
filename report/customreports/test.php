<?php


require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');


$url = new moodle_url('/local/customreports/test.php');
$PAGE->set_url($url);
//$users=optional_param('users','all',PARAM_TEXT);
global $DB, $CFG, $USER;
$CFG->cachejs = true;
//get_custom_reports_css($PAGE);




$password = '3sc3RLrpd17!ioystyissdfi';
$method = 'aes-256-cbc';
$key = substr(hash('sha256', $password, true), 0, 32);
$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
$username = $DB->get_record('gtw_config', array('name' => 'username'));
$decrypted_username = openssl_decrypt(base64_decode($username->value), $method, $key, OPENSSL_RAW_DATA, $iv);
$userpassword = $DB->get_record('gtw_config', array('name' => 'password'));
$decrypted_password = openssl_decrypt(base64_decode($userpassword->value), $method, $key, OPENSSL_RAW_DATA, $iv);



$consumerkey = 'I4c19uWm50pzRAFj8dqvfVqv3Qi6rsRd';
$consumersecret = 'appeEHXayMYl1Nqi';
$base64 = base64_encode($consumerkey . ':' . $consumersecret);
$curl = "curl -X POST 'https://api.getgo.com/oauth/v2/token' -H 'Authorization: Basic " . $base64 . "' -H 'Content-Type: application/x-www-form-urlencoded' -d 'grant_type=password&username=".urlencode($decrypted_username)."&password=".urlencode($decrypted_password)."'";
$data = shell_exec($curl);
$conn = json_decode($data, true);



$webinars = get_webinars($conn, 'general_gotowebinar', true);

foreach ($webinars as $webinar) {
    $array_data = array();
    $webinar_users = get_webinar_attendees($conn, $webinar, false);

    $registrands = count($webinar_users);
    $attendees = 0;
    $early_leavers = 0;
    $uniquevisits = 0;
    $uniques = array();
    $unique_attendees = array();
    $webinar_visits = array();
    foreach ($webinar_users as $user) {
        $unique_attendees[$user['email']] += $user['time'];
        $webinar_visits[] = $user['email'];
    }
    foreach ($unique_attendees as $key => $user) {

        if ($user['time'] / 60 >= 20) {
            $attendees++;
            $uniques[] = $key;
        } else if ($user['time'] / 60 < 20) {
            $early_leavers++;
        }
    }
    $visits_arr = array_intersect($webinar_visits, $uniques);

    $visits = count($visits_arr);
//            foreach ($webinar_users as $user) {
//                //if user is in webinar for 20 minutes or more then count them.
//                if ($user / 60 >= 20) {
//                    $attendees++;
//                } else if ($user / 60 < 20) {
//                    $early_leavers++;
//                }
//            }

    $organizer = get_webinar($conn, $webinar, false, array('organizerName', 'times'));

    $starttime = $organizer['times'][0]['startTime'];
    $panelists_users = get_webinar_panelists($conn, $webinar, false);


    $panelists = 'No panelists';


    foreach ($panelists_users as $user) {
        if ($panelists == 'No panelists') {
            $panelists = '';
        }

        $panelists .= $user['name'] . ' ' . $user['lastName'] . '<br>';
    }


    $array_data['name'] = $webinar['subject'];
    $array_data['webinarKey'] = $webinar['webinarKey'];
//            $array_data['sessionKey']=get_webinar_session($webinar['webinarKey'],$conn['organizer_key']);
    $array_data['panelists'] = $panelists;
    $array_data['organizer'] = $organizer['organizerName'];
    $array_data['registrands'] = $registrands;
    $array_data['early_leavers'] = $early_leavers;
    $array_data['timestart'] = strtotime($starttime);
    $array_data['attendees'] = $attendees;
    $array_data['visits_attendees'] = $visits;

    $recordid=0;
    if ($exists = $DB->get_record('gotowebinar_data', array('name' => $array_data['name']))) {
        $array_data['id'] = $exists->id;
        $recordid=$exists->id;
        $DB->update_record('gotowebinar_data', $array_data);
    } else {
        $recordid=$DB->insert_record('gotowebinar_data', $array_data);
    }
    $sessionsrecord=new \stdClass();
    echo '<pre>';
    var_dump($webinar);

    echo '</pre>';

    $curl = 'curl -X GET "https://api.getgo.com/G2W/rest/v2/organizers/' . $conn['organizer_key'] . '/webinars/' . $webinar['webinarKey'] . '" -H  "accept: application/json" -H  "Authorization: ' . $conn['access_token'] . '"';
    $request = shell_exec($curl);
    $request_response = json_decode($request, true);
    $sessions=get_webinar_sessions($webinar['webinarKey'],$conn['organizer_key'],$conn['access_token']);
    var_dump($sessions);die();

}

echo $output->footer();