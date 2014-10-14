<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 *
 * @property User $User
 */
class Customer extends AppModel {

/**
 * Display field
 *
 * @var string
 */
    public $displayField = 'name';

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Customer name cannot be empty'
            ),
        ),
        'account_no' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                'message' => 'Customer account number must be alpha-numeric (no spaces, special characters, etc.)'
            ),
        ),
        'is_active' => array(
            'boolean' => array(
                'rule' => array('boolean')
            ),
        )
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'customer_id',
            'dependent' => false,
        ),
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'customer_id',
            'dependent' => false,
        )
    );

    /**
     * Model behaviors
     * @var array
     */
    public $actsAs = array('Search.Searchable');

    /**
 * All search fields need to be configured in the Model::filterArgs array.
 *
 * @var array
 * @link https://github.com/CakeDC/search
 */
    public $filterArgs = array(
        'name' => array('type' => 'like', 'field' => array('Customer.name', 'Customer.alternative_name')),
        'account_no' => array('account_no', 'type' => 'like')
    );
} // end class Customer
