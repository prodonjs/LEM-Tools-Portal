<?php
App::uses('Sku', 'Model');

/**
 * Sku Test Case
 *
 */
class SkuTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.sku',
		'app.product'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Sku = ClassRegistry::init('Sku');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Sku);

		parent::tearDown();
	}

}
