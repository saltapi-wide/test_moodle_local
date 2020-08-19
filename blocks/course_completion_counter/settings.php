<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_course_completion_counter'),
            get_string('descconfig', 'block_course_completion_counter')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'user_sessions/Allow_HTML',
            get_string('labelallowhtml', 'block_course_completion_counter'),
            get_string('descallowhtml', 'block_course_completion_counter'),
            '0'
        ));

