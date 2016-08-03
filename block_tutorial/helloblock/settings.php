<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_helloblock'),
            get_string('descconfig', 'block_helloblock')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'helloblock/Allow_HTML',
            get_string('labelallowhtml', 'block_helloblock'),
            get_string('descallowhtml', 'block_helloblock'),
            '1'
        ));