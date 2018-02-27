<?php

Config::set('site_name', 'Simplex');

Config::set('languages', array('en', 'ru'));

// Routes. Route name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin'   => 'admin_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_controller', 'pages');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.psw', '');
Config::set('db.db_name', 'mvc');

Config::set('salt', 'dio878ma0dkfd4+kf7s*k');