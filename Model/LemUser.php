<?php
/**
 * Extend the User Model from cakedc/Users Plugin
 */
App::uses('User', 'Users.Model');
class LemUser extends User {
    public $useTable = 'users';

    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customer_id'
        )
    );

/**
 * All search fields need to be configured in the Model::filterArgs array.
 *
 * @var array
 * @link https://github.com/CakeDC/search
 */
    public $filterArgs = array(
        'username' => array('type' => 'like'),
        'email' => array('type' => 'like'),
        'unconfirmed' => array(
            'type' => 'expression',
            'method' => 'checkboxAsFalse',
            'field' => 'email_verified'
        )
    );

/**
 * Constructor
 *
 * @param string $id ID
 * @param string $table Table
 * @param string $ds Datasource
 */
    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    } // end __construct()

/**
 * Data manipulation before the registration record is saved
 *
 * @param array post data array
 * @param boolean Use email generation, create token, default true
 * @return array
 */
    protected function _beforeRegistration($postData = array(), $useEmailVerification = true) {
        $postData = parent::_beforeRegistration($postData, $useEmailVerification);
	$postData[$this->alias]['email_verified'] = 0;
        $postData[$this->alias]['active'] = 0;
        return $postData;
    } // end _beforeRegistration()

/**
 * Registers a new user
 *
 * @see User
 */
    public function register($postData = array(), $options = array()) {
        if (is_bool($options)) {
            $options = array('emailVerification' => $options);
        }
        $defaults = array(
            'emailVerification' => false,
            'removeExpiredRegistrations' => true,
            'returnData' => true);
        extract(array_merge($defaults, $options));

        $postData = $this->_beforeRegistration($postData, $emailVerification);

        if ($removeExpiredRegistrations) {
            $this->_removeExpiredRegistrations();
        }
        return $this->add($postData);
    } // end register()

/**
 * Edits an existing user
 *
 * @param string $userId User ID
 * @param array $postData controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 */
    public function edit($userId = null, $postData = null) {
        $return = parent::edit($userId, $postData);
        if($return === true && isset($postData['UserDetail'])) {
            $this->setupDetail();
            if(!$this->UserDetail->saveSection($this->id, $postData['UserDetail'], $this->alias)) {
                return false;
            }
            $details = $this->UserDetail->getSection($this->id);
            $this->data['UserDetail'] = $details;
        }
        return $return;
    } // end edit()

/**
 * Adds a new user
 *
 * @param array post data, should be Controller->data
 * @return boolean True if the data was saved successfully.
 */
    public function add($postData = null) {
        $this->setupDetail();
        $this->set($postData);
        if(!($this->validates() && $this->UserDetail->validates()) ) {
            return false;
        }
        $return = parent::add($postData);
        if($return) {
            if(!$this->UserDetail->saveSection($this->id, $postData['UserDetail'], $this->alias)) {
                $this->delete($this->id);
                return false;
            }
            $details = $this->UserDetail->getSection($this->id);
            $this->data['UserDetail'] = $details;
        }
        return $return;
    } // end add()

/**
 * Confirms an existing user
 *
 * @param string $userId User ID
 * @return mixed True on successfully save else post data as array
 */
    public function confirm($userId = null) {
        $this->data = $this->find('first', array(
            'conditions' => array("{$this->alias}.id" => $userId),
            'recursive' => 0
        ));
        if (empty($this->data)) {
            throw new NotFoundException('Invalid User');
        }
        $this->data[$this->alias]['active'] = 1;
        $this->data[$this->alias]['role'] = 'active';
        $this->data[$this->alias]['email_token'] = $this->generateToken();
        $this->data[$this->alias]['email_token_expires'] = $this->emailTokenExpirationTime();
        return $this->save($this->data);
    } // end confirm()

    /**
 * Provide default section configuration
 *
 * @return void
 */
    public function setupDetail() {
        $this->UserDetail->sectionSchema[$this->alias] = array(
            'first_name' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
            'last_name' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
            'customer' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
            'phone' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
            'fax' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
            'title' => array(
                'type' => 'string',
                'null' => null,
                'default' => null,
                'length' => null
            ),
        );

        $this->UserDetail->sectionValidation[$this->alias] = array(
            'first_name' => array(
                'notEmpty' => array(
                    'rule' => array('notEmpty'),
                    'message' => 'First name cannot be empty'
                )
            ),
            'last_name' => array(
                'notEmpty' => array(
                    'rule' => array('notEmpty'),
                    'message' => 'Last name cannot be empty'
                )
            ),
            'customer' => array(
                'notEmpty' => array(
                    'rule' => array('notEmpty'),
                    'message' => 'Customer cannot be empty'
                )
            ),
            'phone' => array(
                'phone' => array(
                    'rule' => array('phone'),
                    'allowEmpty' => false,
                    'message' => 'Please provide a valid 10 digit phone number'
                )
            ),
            'fax' => array(
                 'fax' => array(
                    'rule' => array('phone', null, 'us'),
                    'allowEmpty' => true,
                    'message' => 'Please provide a valid 10 digit fax number'
                )
            )
        );
    } // end setupDetail()
} // end class LemUser
