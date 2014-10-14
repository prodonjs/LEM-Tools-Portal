<?php
App::uses('AppModel', 'Model');
/**
 * OrderLine Model
 *
 * @property Order $Order
 * @property Sku $Sku
 */
class OrderLine extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'order_id' => array(
            'numeric' => array(
                'rule' => array('numeric')
            )
        ),
        'line_no' => array(
            'naturalnumber' => array(
                'rule' => array('naturalnumber')
            )
        ),
        'sku_id' => array(
            'numeric' => array(
                'rule' => array('numeric')
            )
        ),
        'part_no' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'You must provide a valid SKU'
            )
        ),
        'description' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'Order line item description cannot be empty'
            )
        ),
        'quantity' => array(
            'numeric' => array(
                'rule' => array('naturalnumber'),
                'message' => 'You must specify a valid quantity'
            )
        ),
        'price' => array(
            'numeric' => array(
                'rule' => array('numeric'),
                'message' => 'You must specify a valid price'
            ),
        ),
    );
/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Order' => array(
            'className' => 'Order',
            'foreignKey' => 'order_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Sku' => array(
            'className' => 'Sku',
            'foreignKey' => 'sku_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
