<?php
App::uses('AppModel', 'Model');
/**
 * Product Model
 *
 * @property Sku $Sku
 */
class Product extends AppModel {

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
                'message' => 'All product families must be given a descriptive name'
            )
        ),
        'rank' => array(
            'naturalNumber' => array(
                'rule' => array('naturalNumber'),
                'message' => 'A catalog ordering rank must be provided for this product'
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
        'Sku' => array(
            'className' => 'Sku',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
        'ProductImage' => array(
            'className' => 'ProductImage',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => 'ProductImage.rank_order',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

} // end class Product
