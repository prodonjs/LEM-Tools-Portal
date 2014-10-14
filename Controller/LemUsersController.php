<?php
/**
 * Extend the UsersController from cakedc/Users Plugin
 * @property User $User User Model
 */
App::uses('UsersController', 'Users.Controller');
class LemUsersController extends UsersController {
    public $name = 'LemUsers';

    /**
     * Map of controller actions to page titles
     * @var array
     */
    protected $_actionTitles = array(
        'add' => 'Register',
        'login' => 'Login',
        'edit' => 'Manage your Profile',
        'reset_password' => 'Forgot Password',
        'change_password' => 'Change Password',
        'admin_index' => 'Administrators - Manage Users',
        'admin_add' => 'Administrators - Add User',
        'admin_edit' => 'Administrators - Edit User'
    );

    /**
     * Map of controller actions to breadcrumb link names
     */
    protected $_breadcrumbs = array(
        'admin_index' => array(
            'page' =>'Manage Users',
            'reset' => true
        ),
        'admin_add' => array(
            'page' =>'Add User',
            'reset' => false
        ),
        'admin_edit' => array(
            'page' => 'Edit User',
            'reset' => false
        )
    );

/**
 * Override Plugin beforeFilter method
 *
 * @return void
 */
    public function beforeFilter() {
        parent::beforeFilter();
        /**
         * Override Auth settings
         */
        $this->Auth->authenticate = array(
            'Form' => array(
                'fields' => array(
                    'username' => 'username',
                    'password' => 'password'
                ),
                'userModel' => 'LemUser',
                'scope' => array(
                    'LemUser.active' => 1,
                    'LemUser.customer_id IS NOT NULL',
                    'LemUser.email_verified' => 1
                )
            )
        );

        $this->User = ClassRegistry::init('LemUser');
        $this->set('model', 'LemUser');
    } // end beforeFilter()

/**
 * Override render method to use App view if present, or fall back to Plugin views if not
 * @param string $view
 * @param string $layout
 * @return type
 */
    public function render($view = null, $layout = null) {
        if (is_null($view)) {
            $view = $this->action;
        }
        $viewPath = substr(get_class($this), 0, strlen(get_class($this)) - 10);
        if (!file_exists(APP . 'View' . DS . $viewPath . DS . $view . '.ctp')) {
            $this->plugin = 'Users';
        } else {
            $this->viewPath = $viewPath;
        }
        return parent::render($view, $layout);
    } // end render()

/**
 * Override login function to allow user to supply either
 * their username or their e-mail address when logging in
 */
    public function login() {
        if ($this->Auth->user()) {
            $this->flashMessage('You are already logged in', 'flash_default');
            $this->redirect('/');
        }
        /**
         * If the supplied username validates as an e-mail address, change the Auth component
         * settings
         */
        if($this->request->is('post') &&
            Validation::email($this->request->data[$this->modelClass]['username']) ) {
            $this->request->data[$this->modelClass]['email'] = $this->request->data[$this->modelClass]['username'];
            unset($this->request->data[$this->modelClass]['username']);
            $this->Auth->authenticate['Form']['fields']['username'] = 'email';
        }
        parent::login();
    } // end login()

/**
 * Override edit function to ensure that UserDetail records are properly retrieved
 *
 * @param string $id User ID
 * @return void
 */
    public function edit() {
        if (!empty($this->request->data)) {
            if ($this->LemUser->edit($this->Auth->user('id'), $this->request->data)) {
                $this->flashMessage('Your profile was saved', 'flash_success');
                $this->redirect(array('action' => 'edit'));
            } else {
                $this->flashMessage('Your profile could not be saved. Please try again.', 'flash_alert');
                $this->request->data['UserDetail']['LemUser'] = $this->request->data['UserDetail'];
            }
        } else {
            $this->LemUser->edit($this->Auth->user('id'));
            $this->request->data = $this->LemUser->data;
        }
    } // end edit()

/**
 * User register action
 *
 * @return void
 */
    public function add() {
        if ($this->Auth->user()) {
            $this->Session->setFlash(__d('users', 'You are already registered and logged in!'));
            $this->redirect('/');
        }

        if (!empty($this->request->data)) {
            $user = $this->{$this->modelClass}->register($this->request->data);
            if ($user !== false) {
                $this->_sendVerificationEmail(array_merge($this->request->data, $this->{$this->modelClass}->data), array(
                    'subject' => 'LEM Tools :: Registration Received',
                    'template' => $this->_pluginDot() . 'account_registration'
                ));
                $this->redirect(array('controller' => 'pages', 'action' => 'display', 'registered'));
            } else {
                unset($this->request->data[$this->modelClass]['password']);
                unset($this->request->data[$this->modelClass]['temppassword']);
                $this->flashMessage('Your account could not be created. Please check your inputs and try again.', 'flash_alert');
            }
        }
    } //end add()

/**
 * Admin Index
 *
 * @return void
 */
    public function admin_index() {
        $this->Prg->commonProcess();
        unset($this->{$this->modelClass}->validate['username']);
        unset($this->{$this->modelClass}->validate['email']);
        unset($this->{$this->modelClass}->validate['email_verified']);
        $this->{$this->modelClass}->data[$this->modelClass] = $this->request->query;
        if ($this->{$this->modelClass}->Behaviors->attached('Searchable')) {
            $parsedConditions = $this->{$this->modelClass}->parseCriteria($this->request->query);
        } else {
            $parsedConditions = array();
        }
        $this->Paginator->settings[$this->modelClass]['conditions'] = $parsedConditions;
        $this->Paginator->settings[$this->modelClass]['order'] = array($this->modelClass . '.created' => 'desc');

        $this->{$this->modelClass}->recursive = 0;
        $this->set('users', $this->paginate());
    } // end admin_index()

/**
 * Activates the specified user and sends their confirmation e-mail
 *
 * @return void
 */
    public function admin_confirm($userId) {
        $confirmed = $this->LemUser->confirm($userId);
        if(($confirmed)) {
            $this->_sendVerificationEmail($confirmed, array(
                'subject' => 'LEM Tools :: Registration Confirmed',
                'template' => $this->_pluginDot() . 'account_verification'
            ));
            $this->Session->setFlash("Verification e-mail sent to {$confirmed['LemUser']['email']}");
        }
        else {
            $this->Session->setFlash("Unable to confirm user!");
        }
        $this->redirect(array('action' => 'index'));
    } // end admin_confirm()


/**
 * Override the admin_add() to make sure customers are set as a view variable
 */
    public function admin_add() {
        parent::admin_add();
        $this->set('customers', $this->getCustomerList());
    } // end admin_add()

/**
  * Override the admin_edit() to make sure customers are set as a view variable
  */
    public function admin_edit($userId = null) {
        parent::admin_edit($userId);
        $this->set('customers', $this->getCustomerList());
    } // end admin_edit()

/**
  * Resets a user's password and sends them the confirmation e-mail
  */
    public function admin_reset_password($userId = null) {
        $this->LemUser->recursive = -1;
        $this->LemUser->id = $userId;
        $this->request->data = $this->LemUser->read();
        if(empty($this->request->data)) {
            throw new NotFoundException('Invalid user');
        }
        $this->_sendPasswordReset(true);

        $this->redirect(array('admin' => true, 'action' => 'edit', $userId));
    } // end admin_reset_password()

    /**
     * Designed to be used with a requestAction call to
     * return the number of users needing to be confirmed awaiting processing
     * @return mixed
     */
    public function admin_unconfirmed_count() {
        $this->LemUser->recursive = -1;
        $count = $this->LemUser->find('count', array(
            'conditions' => array(
                'OR' => array(
                    'LemUser.email_verified' => 0,
                    'LemUser.customer_id IS NULL'
                )
            )
        ));
        if($this->request->is('requested')) {
            return $count;
        }
        else {
            throw new BadRequestException('This method can only be called internally');
        }
    } // end admin_submitted_status

    /**
     * Returns list of Customer data for drop-down list
     * @return array
     */
    private function getCustomerList() {
        $this->LemUser->Customer->virtualFields = array(
            'label' => "CONCAT(Customer.account_no, ' | ', Customer.name)"
        );
        return $this->LemUser->Customer->find('list', array(
            'fields' => array('Customer.id', 'Customer.label'),
            'order' => 'Customer.label'
        ));
    } // end setCustomerList()

} // end class LemUsersController
