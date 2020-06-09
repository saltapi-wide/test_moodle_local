<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_user_sessions'),
            get_string('descconfig', 'block_user_sessions')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'user_sessions/Allow_HTML',
            get_string('labelallowhtml', 'block_user_sessions'),
            get_string('descallowhtml', 'block_user_sessions'),
            '0'
        ));

