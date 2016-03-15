<?php

return array(
    'db' => array(
        'driver' => 'PDO',
        'dsn' => 'mysql:dbname=cpf_teste;host=localhost',
        'username' => 'root',
        'password' => 'root',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
));
