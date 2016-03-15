<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return array(
    'db' => array(
         'driver'         => 'Pdo',
         'dsn'            => 'mysql:dbname=cfp;host=localhost',
         'driver_options' => array(
             PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
         ),
     ),
    
    'service_manager' => array(
         'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
         ),
    ),
    
    'email' => array(
        'name' => 'mailtrap.io',
        'host' => 'mailtrap.io',
        'port' => '2525',
        'connection_class'  => 'CRAM-MD5',
        'connection_config' => array(
            'username' => '534170651e04c0af5',
            'password' => '16ef6225b76122',
            'ssl'      => 'tls',
        ),
    ),
);
