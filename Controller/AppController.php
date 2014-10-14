<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

/**
 * Public controllers have all actions authorized by default
 * @var array
 */
    public $publicControllers = array('pages');

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Session', 'Time', 'Text');

/**
 * Components
 *
 * @var array
 */
	public $components = array(
        'Auth',
        'Session',
        'Cookie',
        'Paginator',
        'Security'
    );

/**
 * Default paginate settings
 * @var array
 */
    public $paginate = array(
        'paramType' => 'querystring',
        'limit' => 25
    );

    /**
     * User's cart
     * @var array
     */
    public $cart = array();

/**
 * Constructor
 *
 * @param mixed $request
 * @param mixed $response
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);
		if (Configure::read('debug')) {
			$this->components[] = 'DebugKit.Toolbar';
		}
	} // end __construct()

/**
 * Override beforeFilter()
 */
    public function beforeFilter() {
        $this->Auth->loginRedirect = '/';
		$this->Auth->logoutRedirect = '/login';
		$this->Auth->loginAction = '/login';
        $this->Auth->authorize = array('Controller');

        if (in_array(strtolower($this->params['controller']), $this->publicControllers)) {
			$this->Auth->allow();
		}
        $this->Paginator->settings[$this->modelClass] = $this->paginate;

        /* Return immediately if this is from a requestAction call */
        if($this->request->is('requested')) {
            return;
        }

        /**
         * Set page title, breadcrumbs, and cart
         */
        if(isset($this->_actionTitles[$this->request->action])) {
            $this->set('title_for_layout', $this->_actionTitles[$this->request->action]);
        }
        if(isset($this->_breadcrumbs[$this->request->action])) {
            $this->setBreadcrumb($this->_breadcrumbs[$this->request->action]['page'],
                $this->_breadcrumbs[$this->request->action]['reset']
            );
        }
        else {
            $this->Session->delete('Breadcrumbs');
        }

        /**
         * Set the user's cart variable if logged in
         */
        if($this->Auth->loggedIn()) {
            $this->_configureCart();
        }

        /**
         * Configure callback method on Security Black Hole
         */
        $this->Security->blackHoleCallback = 'handleSecurityBlackHole';
    } // end beforeFilter()

/**
 * isAuthorized
 *
 * @return boolean
 */
	public function isAuthorized() {
		if ($this->Auth->user() && $this->params['prefix'] !== 'admin') {
			return true;
		}
		if ($this->params['prefix'] === 'admin' && $this->Auth->user('is_admin')) {
			return true;
		}
		return false;
	} // end isAuthorized()

    /**
     * Convenience wrapper for $this->Session->setFlash
     * @param string $messageText
     * @param string $element
     * @param array $class
     */
    public function flashMessage($messageText, $element = 'flash_default', $class = '') {
        $this->Session->setFlash($messageText, $element, array('class' => $class));
    } // end flashMessage()

    /**
     * Stores the current action's entry in the Breadcrumbs Session parameter to be used
     * when building the breadcrumb navigation links in Views.
     * @param string $pageName
     * @param boolean $reset
     * @return void
     */
    public function setBreadcrumb($pageName, $reset = false) {
        if($reset) {
            $this->Session->delete('Breadcrumbs');
        }
        $this->Session->write("Breadcrumbs.{$pageName}", $this->request->here);
    } // end setBreadcrumb()

    /**
     * Handler for Security component Black-hole operation
     * @param string $type
     * @return void
     */
    public function handleSecurityBlackHole($type) {
        switch($type) {
            case 'csrf':
                $this->flashMessage('Sorry, the form has expired. Please try submitting it again', 'flash_alert');
                break;
            default:
                $this->flashMessage('Sorry, our security settings have blocked your request. Please try again', 'flash_alert');
                break;
        }
    } // end handleSecurityBlackHole()

    /**
     * Determines if the logged-in user's cart is populated in the Session data.
     * If not, tries to grab an open order for the user from the DB. If none is found,
     * build an empty Order dataset in the Session
     * @return void
     */
    private function _configureCart() {
        $this->cart = $this->Session->read('Cart');
        if(empty($this->cart)) {
            $this->cart = ClassRegistry::init('Order')->getOpenCart(
                $this->Auth->user('id'),
                $this->Auth->user('customer_id')
            );
        }
        $this->Session->write('Cart', $this->cart);
        $this->set('cart', $this->cart);
    } // end _configureCart()
} // end class AppController
