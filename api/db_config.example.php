<?php
// Copy to api/db_config.php and fill in. Used on EC2 where there is no
// environment-variable panel (Elastic Beanstalk uses environment properties instead).
return [
    'host' => '127.0.0.1',
    'port' => '3306',
    'name' => 'click2dine',
    'user' => 'c2d_app',
    'pass' => 'CHANGE_ME',
];
