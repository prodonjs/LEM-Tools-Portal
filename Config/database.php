<?php
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'lem_portal',
		'password' => '789ms4W4O51347C',
		'database' => 'lem_portal'
	);
    
    public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'lem_portal',
		'password' => '789ms4W4O51347C',
		'database' => 'lem_portal',
        'prefix' => 'test_'
	);
}
