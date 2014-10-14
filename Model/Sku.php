<?php
App::uses('AppModel', 'Model');
/**
 * Sku Model
 *
 * @property Product $Product
 */
class Sku extends AppModel {

/**
 * Use table
 *
 * @var mixed False or table name
 */
	public $useTable = 'skus';

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
		'product_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
                'allowEmpty' => false
			)
		),
		'part_no' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'You must assign the internally used part number for a SKU'
			)
		),
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'message' => 'All SKUs must be given a descriptive name'
			)
		),
		'price' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				'message' => 'Price must be a valid number'
			),
            'positive' => array(
                'rule' => array('comparison', '>', 0),
				'message' => 'Price must be greater than 0'
            )
		),
        'is_active' => array(
			'boolean' => array(
				'rule' => array('boolean')
			)
		)
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'product_id'
		)
	);
}
