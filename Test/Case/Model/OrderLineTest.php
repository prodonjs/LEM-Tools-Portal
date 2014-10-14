<?php
App::uses('OrderLine', 'Model');

/**
 * OrderLine Test Case
 *
 */
class OrderLineTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.order_line',
		'app.order',
		'app.customer',
		'app.user',
		'app.sku',
		'app.product',
		'app.product_image'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->OrderLine = ClassRegistry::init('OrderLine');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->OrderLine);

		parent::tearDown();
	}

}
