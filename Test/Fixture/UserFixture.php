<?php
/**
 * UserFixture
 *
 */
class UserFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Users.User');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'username' => 'user',
			'slug' => 'Lorem ipsum dolor sit amet',
			'password' => 'password',
			'password_token' => 'Lorem ipsum dolor sit amet',
			'email' => 'user@user.com',
			'email_verified' => 1,
			'email_token' => 'Lorem ipsum dolor sit amet',
			'email_token_expires' => '2013-07-02 14:55:36',
			'tos' => 1,
			'active' => 1,
			'last_login' => '2013-07-02 14:55:36',
			'last_action' => '2013-07-02 14:55:36',
			'is_admin' => 1,
			'role' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-07-02 14:55:36',
			'modified' => '2013-07-02 14:55:36'
		),
	);

}
