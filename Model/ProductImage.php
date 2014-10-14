<?php
App::uses('AppModel', 'Model');
/**
 * ProductImage Model
 *
 * @property Product $Product
 */
class ProductImage extends AppModel {
/**
 * Use the Uploader behavior
 */
    public $actsAs = array(
        'Uploader.Attachment' => array(
            'file' => array(
                'nameCallback' => 'nameCallback',
                'uploadDir' => 'img/products/',
    			'overwrite' => true,
    			'stopSave' => true,
    			'allowEmpty' => true,
    			'transforms' => array(
    			    'thumbnail_file' => array(
    			        'method' => 'resize',
    			        'uploadDir' => 'img/products/',
    			        'width' => 150,
    			        'expand' => true,
    			        'aspect' => true
			        )
			    )
            )
        ),
        'Uploader.FileValidation' => array(
            'file' => array(
                'required'	=> array(
                    'value' => true,
                    'allowEmpty' => false,
                    'message' => 'You must specify an image file to upload'
                ),
                'extension'	=> array(
                    'value' => array('jpg', 'jpeg', 'png'),
                    'message' => 'File must be a JPEG or PNG image'
                )
            )
        )
    );

/**
 * Model validation
 * @var array
 */
    public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',            
                'message' => 'Please provide a descriptive name for the image.'
            ),
            'unique' => array(
                'rule' => 'isUnique',            
                'message' => 'An image with that name already exists.'
            )
        ),
        'rank_order' => array(
            'rule' => array('naturalNumber'),
            'message' => 'Please assign a rank order for this product image.'
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

/**
 * Used to format the name for this picture
 * @param string $name
 * @param Transit\File $file
 */
	public function nameCallback($name, Transit\File $file) {
	    return strtolower(Inflector::slug($this->data[$this->alias]['name']));
	} // end nameCallback()

} // end class ProductImage
