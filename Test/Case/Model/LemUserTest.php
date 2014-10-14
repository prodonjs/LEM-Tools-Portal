<?php
App::uses('LemUser', 'Model');

/**
 * LemUser Test Case
 *
 */
class LemUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.user_detail',
        'app.customer',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->LemUser = ClassRegistry::init('LemUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->LemUser);
		parent::tearDown();
	}
    
/**
 * testRegister method
 *
 * @return void
 */
    public function testRegister() {
        $data = array(
            'LemUser' => array(
                'username' => 'newuser',
                'email' => 'newuser@localhost.com',
                'password' => 'password',
                'temppassword' => 'password',
                'tos' => 1
            ),
            'UserDetail' => array(
                'first_name' => 'New',
                'last_name' => 'User',
                'customer' => 'New Customer',
                'phone' => '555-555-5555'
            )
        );
        $this->LemUser->create();
        $save = $this->LemUser->register($data);
        $this->assertEquals(2, $save['LemUser']['id']);
        $this->assertEquals(false, $save['LemUser']['active']);
        $this->assertEquals('unconfirmed', $save['LemUser']['role']);
        $this->assertEquals('New', $save['UserDetail']['first_name']);        
        $data = array(
            'LemUser' => array(
                'username' => 'test',
                'email' => 'test@test.com',
                'password' => 'short',
                'temppassword' => 'short',
                'tos' => 1
            )            
        );
        $this->LemUser->create();
        $this->assertFalse($this->LemUser->register($data));
    } // end testRegister()
    
/**
 * testEdit method
 *
 * @return void
 */
    public function testEdit() {
        $this->LemUser->edit(1);        
        unset($this->LemUser->data['UserDetail']);        
        $this->LemUser->data['UserDetail'] = array(
            'first_name' => 'Changed',
            'last_name' => 'User Changed',
            'customer' => 'New Customer Changed',
            'phone' => '555-555-0000'
        );
        $this->assertTrue($this->LemUser->edit($this->LemUser->id, $this->LemUser->data));
        $this->assertEquals('Changed', $this->LemUser->data['UserDetail']['LemUser']['first_name']);
    } // end testEdit()
    
/**
 * testEdit method
 *
 * @return void
 */
    public function testConfirm() {
        $confirmed = $this->LemUser->confirm(1);        
        $this->assertEquals('active', $confirmed['LemUser']['role']);
    } // end testConfirm()

    
} // end class LemUserTest()
