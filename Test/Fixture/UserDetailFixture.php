<?php
/**
 * UserDetailFixture
 *
 */
class UserDetailFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Users.UserDetail');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 1,
			'position' => 1,
			'field' => 'LemUser.first_name',
			'value' => 'Test',
			'input' => 'Lorem ipsum do',
			'data_type' => 'Lorem ipsum do',
			'label' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-07-02 14:55:58',
			'modified' => '2013-07-02 14:55:58'
		),
        array(
			'id' => 2,
			'user_id' => 1,
			'position' => 1,
			'field' => 'LemUser.last_name',
			'value' => 'User',
			'input' => 'Lorem ipsum do',
			'data_type' => 'Lorem ipsum do',
			'label' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-07-02 14:55:58',
			'modified' => '2013-07-02 14:55:58'
		),
        array(
			'id' => 3,
			'user_id' => 1,
			'position' => 1,
			'field' => 'LemUser.customer',
			'value' => 'New Customer',
			'input' => 'Lorem ipsum do',
			'data_type' => 'Lorem ipsum do',
			'label' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-07-02 14:55:58',
			'modified' => '2013-07-02 14:55:58'
		),
        array(
			'id' => 4,
			'user_id' => 1,
			'position' => 1,
			'field' => 'LemUser.phone',
			'value' => '555-555-5555',
			'input' => 'Lorem ipsum do',
			'data_type' => 'Lorem ipsum do',
			'label' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-07-02 14:55:58',
			'modified' => '2013-07-02 14:55:58'
		),
	);

}
