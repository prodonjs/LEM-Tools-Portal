<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('LemUsersController', 'Controller');
App::uses('User', 'Users.Model');
App::uses('AuthComponent', 'Controller/Component');
App::uses('CookieComponent', 'Controller/Component');
App::uses('SessionComponent', 'Controller/Component');
App::uses('RememberMeComponent', 'Users.Controller/Component');
App::uses('Security', 'Utility');
app::uses('CakeEmail', 'Network/Email');

/**
 * TestLemUsersController
 *
 * @package users
 * @subpackage users.tests.controllers
 */
class TestLemUsersController extends LemUsersController {

/**
 * Name
 *
 * @var string
 */
	public $name = 'LemUsers';

/**
 * beforeFilter Callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->authorize = array('Controller');
		$this->Auth->fields = array('username' => 'email', 'password' => 'password');
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'plugin' => 'users');
		$this->Auth->loginRedirect = $this->Session->read('Auth.redirect');
		$this->Auth->logoutRedirect = '/';
		$this->Auth->authError = __d('users', 'Sorry, but you need to login to access this location.');
		$this->Auth->autoRedirect = true;
		$this->Auth->userModel = 'User';
		$this->Auth->userScope = array(
			'OR' => array(
				'AND' =>
					array('User.active' => 1, 'User.email_verified' => 1)));
	}

/**
 * Public interface to _setCookie
 */
	public function setCookie($options = array()) {
		parent::_setCookie($options);
	}
	
/**
 * Public intefface to _getMailInstance 
 */	
	public function getMailInstance() {
		return parent::_getMailInstance();
	}

/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect URL
 *
 * @var mixed
 */
	public $redirectUrl = null;

/**
 * CakeEmail Mock
 *
 * @var object
 */
	public $CakeEmail = null;

/**
 * Override controller method for testing
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}

/**
 * Override controller method for testing
 *
 * @param string
 * @param string
 * @param string
 * @return string
 */
	public function render($action = null, $layout = null, $file = null) {
		$this->renderedView = $action;
	}

/**
 * Overriding the original method to return a mock object
 *
 * @return object CakeEmail instance
 */
	protected function _getMailInstance() {
		return $this->CakeEmail;
	}

	}

/**
 * Email configuration override for testing 
 */
class EmailConfig {

	public $default = array(
		'transport' => 'Debug',
		'from' => 'default@example.com',
	);

	public $another = array(
		'transport' => 'Debug',
		'from' => 'another@example.com',
	);
}
	
	
class LemUsersControllerTestCase extends CakeTestCase {

/**
 * Instance of the controller
 *
 * @var mixed
 */
	public $LemUsers = null;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user',
		'app.user_detail'
	);

/**
 * Sampletdata used for post data
 *
 * @var array
 */
	public $usersData = array(
		'admin' => array(
			'email' => 'adminuser@localhost.com',
			'username' => 'adminuser',
			'password' => 'test'),
		'validUser' => array(
			'email' => 'validuser@localhost.com',
			'username' => 'testuser',
			'password' => 'secretkey',
			'redirect' => '/user/slugname'),
		'invalidUser' => array(
			'email' => 'wronguser@localhost.com',
			'username' => 'invalidUser',
			'password' => 'invalid-password!'),
	);

/**
 * Start test
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		Configure::write('App.UserClass', null);

		$request = new CakeRequest();
		$response = $this->getMock('CakeResponse');

		$this->LemUsers = new TestLemUsersController($request, $response);
		$this->LemUsers->constructClasses();
		$this->LemUsers->request->params = array(
			'pass' => array(),
			'named' => array(),
			'controller' => 'users',
			'admin' => false,
			'plugin' => 'users',
			'url' => array());

		$this->LemUsers->CakeEmail = $this->getMock('CakeEmail');
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('to')
			 ->will($this->returnSelf());
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('from')
			 ->will($this->returnSelf());
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('subject')
			 ->will($this->returnSelf());
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('template')
			 ->will($this->returnSelf());
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('viewVars')
			 ->will($this->returnSelf());
		$this->LemUsers->CakeEmail->expects($this->any())
			 ->method('emailFormat')
			 ->will($this->returnSelf());

		$this->LemUsers->Components->disable('Security');
	}

/**
 * Test controller instance
 *
 * @return void
 */
	public function testLemUsersControllerInstance() {
		$this->assertInstanceOf('LemUsersController', $this->LemUsers);
	}

/**
 * Test the user login
 *
 * @return void
 */
	public function testUserLogin() {
		$this->LemUsers->request->params['action'] = 'login';
 		$this->__setPost(array('User' => $this->usersData['admin']));
		$this->LemUsers->request->url = '/users/users/login';
 		
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Auth = $this->getMock('AuthComponent', array('login', 'user', 'redirect'), array($this->Collection));
		$this->LemUsers->Auth->expects($this->once())
			->method('login')
			->will($this->returnValue(true));
		$this->LemUsers->Auth->staticExpects($this->at(0))
			->method('user')
			->with('last_login')
			->will($this->returnValue(1));
		$this->LemUsers->Auth->staticExpects($this->at(1))
			->method('user')
			->with('id')
			->will($this->returnValue(1));
		$this->LemUsers->Auth->staticExpects($this->at(2))
			->method('user')
			->with('username')
			->will($this->returnValue('adminuser'));
		$this->LemUsers->Auth->expects($this->once())
			->method('redirect')
			->with(null)
			->will($this->returnValue(Router::normalize('/')));
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->any())
			->method('setFlash')
			->with(__d('users', 'adminuser you have successfully logged in'));
		$this->LemUsers->RememberMe = $this->getMock('RememberMeComponent', array(), array($this->Collection));
		$this->LemUsers->RememberMe->expects($this->any())
			->method('destroyCookie');

		$this->LemUsers->login();
		$this->assertEqual(Router::normalize($this->LemUsers->redirectUrl), Router::normalize(Router::url($this->LemUsers->Auth->loginRedirect)));
	}
	
/**
 * We should not see any flash message if we GET the login action
 */	
	public function testUserLoginGet() {
		// test with the login action
		$this->LemUsers->request->url = '/users/users/login';
		$this->LemUsers->request->params['action'] = 'login';
		$this->__setGet();
 		
		$this->LemUsers->login();
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->never())
			->method('setFlash');
	}

/**
 * testFailedUserLogin
 *
 * @return void
 */
	public function testFailedUserLogin() {
		$this->LemUsers->request->params['action'] = 'login';
		$this->__setPost(array('User' => $this->usersData['invalidUser']));
 		
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Auth = $this->getMock('AuthComponent', array('flash', 'login'), array($this->Collection));
		$this->LemUsers->Auth->expects($this->once())
			->method('login')
			->will($this->returnValue(false));
		$this->LemUsers->Auth->expects($this->once())
			->method('flash')
			->with(__d('users', 'Invalid e-mail / password combination.  Please try again'));
		$this->LemUsers->login();
	}

/**
 * Test user registration
 *
 */
	public function testAdd() {
		$this->LemUsers->CakeEmail->expects($this->at(0))
			->method('send');

		$_SERVER['HTTP_HOST'] = 'test.com';
		$this->LemUsers->params['action'] = 'add';
		$this->__setPost(array(
			'LemUser' => array(
				'username' => 'newUser',
				'email' => 'newUser@localhost.com',
				'company' => 'newCompany',
                'password' => 'password',
				'temppassword' => 'password',                
				'tos' => 1)));
		$this->LemUsers->beforeFilter();
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->add();

		$this->__setPost(array(
			'User' => array(
				'username' => 'newUser',
				'email' => '',
				'password' => '',
				'temppassword' => '',
				'tos' => 0)));
		$this->LemUsers->beforeFilter();
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->once())
			->method('setFlash')
			->with(__d('users', 'Your account could not be created. Please, try again.'));
		$this->LemUsers->add();
	}

/**
 * Test
 *
 * @return void
 */
	public function testVerify() {
		$this->LemUsers->beforeFilter();
		$this->LemUsers->User->id = '37ea303a-3bdc-4251-b315-1316c0b300fa';
		$this->LemUsers->User->saveField('email_token_expires', date('Y-m-d H:i:s', strtotime('+1 year')));
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->once())
			->method('setFlash')
			->with(__d('users', 'Your e-mail has been validated!'));

		$this->LemUsers->verify('email', 'testtoken2');

		$this->LemUsers->beforeFilter();
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->once())
			->method('setFlash')
			->with(__d('users', 'Invalid token, please check the email you were sent, and retry the verification link.'));

		$this->LemUsers->verify('email', 'invalid-token');;
	}

/**
 * Test logout
 *
 * @return void
 */
	public function testLogout() {
		$this->LemUsers->beforeFilter();
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Cookie = $this->getMock('CookieComponent', array('destroy'), array($this->Collection));
		$this->LemUsers->Cookie->expects($this->once())
			->method('destroy');
		$this->LemUsers->Session = $this->getMock('SessionComponent', array('setFlash'), array($this->Collection));
		$this->LemUsers->Session->expects($this->once())
			->method('setFlash')
			->with(__d('users', 'testuser you have successfully logged out'));
		$this->LemUsers->Auth = $this->getMock('AuthComponent', array('logout', 'user'), array($this->Collection));
		$this->LemUsers->Auth->expects($this->once())
			->method('logout')
			->will($this->returnValue('/'));
		$this->LemUsers->Auth->staticExpects($this->at(0))
			->method('user')
			->will($this->returnValue($this->usersData['validUser']));
		$this->LemUsers->RememberMe = $this->getMock('RememberMeComponent', array(), array($this->Collection));
		$this->LemUsers->RememberMe->expects($this->any())
			->method('destroyCookie');

		$this->LemUsers->logout();
		$this->assertEqual($this->LemUsers->redirectUrl, '/');
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testIndex() {
		$this->LemUsers->passedArgs = array();
 		$this->LemUsers->index();
		$this->assertTrue(isset($this->LemUsers->viewVars['users']));
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
 		$this->LemUsers->view('adminuser');
		$this->assertTrue(isset($this->LemUsers->viewVars['user']));

		$this->LemUsers->view('INVALID-SLUG');
		$this->assertEqual($this->LemUsers->redirectUrl, '/');
	}

/**
 * change_password
 *
 * @return void
 */
	public function testChangePassword() {
		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->Auth = $this->getMock('AuthComponent', array('user'), array($this->Collection));
		$this->LemUsers->Auth->staticExpects($this->once())
				->method('user')
				->with('id')
				->will($this->returnValue(1));
		$this->__setPost(array(
			'User' => array(
				'new_password' => 'newpassword',
				'confirm_password' => 'newpassword',
				'old_password' => 'test')));
		$this->LemUsers->RememberMe = $this->getMock('RememberMeComponent', array(), array($this->Collection));
		$this->LemUsers->RememberMe->expects($this->any())
			->method('destroyCookie');

		$this->LemUsers->change_password();
		$this->assertEqual($this->LemUsers->redirectUrl, '/');
	}

/**
 * testEdit
 *
 * @return void
 */
	public function testEdit() {
		$this->LemUsers->Session->write('Auth.User.id', '1');
		$this->LemUsers->edit();
		$this->assertTrue(!empty($this->LemUsers->data));
		
		$this->LemUsers->Session->write('Auth.User.id', 'INVALID-ID');
		$this->LemUsers->edit();
		$this->assertTrue(empty($this->LemUsers->data['User']));
	}

/**
 * testResetPassword
 *
 * @return void
 */
	public function testResetPassword() {
		$this->LemUsers->CakeEmail->expects($this->at(0))
			->method('send');

		$_SERVER['HTTP_HOST'] = 'test.com';
		$this->LemUsers->User->id = '1';
		$this->LemUsers->User->saveField('email_token_expires', date('Y-m-d H:i:s', strtotime('+1 year')));
		$this->LemUsers->data = array(
			'User' => array(
				'email' => 'adminuser@cakedc.com'));
		$this->LemUsers->reset_password();
		$this->assertEqual($this->LemUsers->redirectUrl, array('action' => 'login'));


		$this->LemUsers->data = array(
			'User' => array(
				'new_password' => 'newpassword',
				'confirm_password' => 'newpassword'));
		$this->LemUsers->reset_password('testtoken');
		$this->assertEqual($this->LemUsers->redirectUrl, $this->LemUsers->Auth->loginAction);
	}

/**
 * testAdminIndex
 *
 * @return void
 */
	public function testAdminIndex() {
		$this->LemUsers->params = array(
			'url' => array(),
			'named' => array(
				'search' => 'adminuser'));
		$this->LemUsers->passedArgs = array();
 		$this->LemUsers->admin_index();
		$this->assertTrue(isset($this->LemUsers->viewVars['users']));
	}

/**
 * testAdminView
 *
 * @return void
 */
	public function testAdminView() {
 		$this->LemUsers->admin_view('1');
		$this->assertTrue(isset($this->LemUsers->viewVars['user']));
	}

/**
 * testAdminDelete
 *
 * @return void
 */
	public function testAdminDelete() {
		$this->LemUsers->User->id = '1';
		$this->assertTrue($this->LemUsers->User->exists(true));
		$this->LemUsers->admin_delete('1');
		$this->assertEqual($this->LemUsers->redirectUrl, array('action' => 'index'));
		$this->assertFalse($this->LemUsers->User->exists(true));

		$this->LemUsers->admin_delete('INVALID-ID');
		$this->assertEqual($this->LemUsers->redirectUrl, array('action' => 'index'));
	}

/**
 * Test setting the cookie
 *
 * @return void
 */
	public function testSetCookie() {
		$this->__setPost(array(
			'User' => array(
				'remember_me' => 1,
				'email' => 'testuser@cakedc.com',
				'username' => 'test',
				'password' => 'testtest')));

		$this->Collection = $this->getMock('ComponentCollection');
		$this->LemUsers->RememberMe = $this->getMock('RememberMeComponent', array(), array($this->Collection));
		$this->LemUsers->RememberMe->expects($this->once())
			->method('configureCookie')
			->with(array('name' => 'userTestCookie'));
		$this->LemUsers->RememberMe->expects($this->once())
			->method('setCookie');

		$this->LemUsers->setCookie(array(
			'name' => 'userTestCookie'));

		$this->assertEqual($this->LemUsers->RememberMe->settings['cookieKey'], 'rememberMe');
	}

/**
 * Test getting default and setted email instance config
 *
 * @return void
 */
	public function testGetMailInstance() {
		$defaultConfig = $this->LemUsers->getMailInstance()->config();
		$this->assertEqual($defaultConfig['from'], 'default@example.com');
		
		Configure::write('Users.emailConfig', 'another');
		$anotherConfig = $this->LemUsers->getMailInstance()->config();
		$this->assertEqual($anotherConfig['from'], 'another@example.com');
		
		$this->setExpectedException('ConfigureException');
		Configure::write('Users.emailConfig', 'doesnotexist');
		$anotherConfig = $this->LemUsers->getMailInstance()->config();
	}

/**
 * Test
 *
 * @return void
 */
	private function __setPost($data = array()) {
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->LemUsers->request->data = array_merge($data, array('_method' => 'POST'));
	}

/**
 * Test
 *
 * @return void
 */
	private function __setGet() {
		$_SERVER['REQUEST_METHOD'] = 'GET';
	}

/**
 * Test
 *
 * @return void
 */
	public function endTest($method) {
		$this->LemUsers->Session->destroy();
		unset($this->LemUsers);
		ClassRegistry::flush();
	}

}
