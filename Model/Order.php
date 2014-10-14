<?php
App::uses('AppModel', 'Model');
App::uses('OrderEventListener', 'Vendor');
/**
 * Order Model
 *
 * @property Customer $Customer
 * @property User $User
 * @property OrderLine $OrderLine
 */
class Order extends AppModel {

    /**
     * @var string Root-relative path for saving invoices
     */
    const INVOICES_FOLDER = 'files/invoices/';

/**
 * Use the Uploader behavior
 */
    public $actsAs = array(
        'Uploader.Attachment' => array(
            'invoice' => array(
                'nameCallback' => 'nameCallback',
                'uploadDir' => self::INVOICES_FOLDER,
                'overwrite' => true,
                'stopSave' => true,
                'allowEmpty' => true
            )
        )
    );

/**
 * Validation rules
 *
 * @var array
 */
    public $validate = array(
        'customer_id' => array(
            'numeric' => array(
                'rule' => array('numeric')
            )
        ),
        'user_id' => array(
            'numeric' => array(
                'rule' => array('numeric')
            )
        ),
        'status' => array(
            'notempty' => array(
                'rule' => array('isValidStatus'),
                'message' => 'Order status must be specified from the list of valid values'
            )
        ),
        'requested_delivery' => array(
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Please specify your desired delivery date',
                'allowEmpty' => true
            )
        ),
        'work_order_no' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                'message' => 'Work order number must be alphanumeric',
                'allowEmpty' => true
            )
        ),
        'invoice_no' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                'message' => 'Invoice number must be alphanumeric',
                'allowEmpty' => true
            )
        ),
        'tracking_no' => array(
            'alphanumeric' => array(
                'rule' => array('alphanumeric'),
                'message' => 'Tracking number must be alphanumeric',
                'allowEmpty' => true
            )
        )
    );

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
        'Customer' => array(
            'className' => 'Customer',
            'foreignKey' => 'customer_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'LemUser' => array(
            'className' => 'LemUser',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

/**
 * hasMany associations
 *
 * @var array
 */
    public $hasMany = array(
        'OrderLine' => array(
            'className' => 'OrderLine',
            'foreignKey' => 'order_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => 'OrderLine.line_no',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );

    /**
     * Custom find methods
     * @var array
     */
    public $findMethods = array('orderIndex' => true);

    /**
     * Custom find method for order summary data
     * @param string $state
     * @param array $query
     * @param array $results
     * @return array
     */
    protected function _findOrderIndex($state, $query, $results = array()) {
        if($state === 'before') {
            $this->unbindModel(array(
                'hasMany' => array('OrderLine')
            ), false);
            $this->bindModel(array(
                'hasOne' => array(
                    'OrderLine' => array(
                        'className' => 'OrderLine',
                        'foreignKey' => 'order_id'
                    )
                )
            ), false);
            $this->virtualFields = array(
                'quantity' => 'SUM(OrderLine.quantity)',
                'price' => 'SUM(OrderLine.quantity * OrderLine.price)'
            );
            $query['fields'] = array(
                'Customer.id', 'Customer.name', 'Customer.account_no',
                'LemUser.id', 'LemUser.username', 'LemUser.email',
                'Order.id', 'Order.status', 'Order.purchase_order', 'Order.notes',
                'Order.requested_delivery', 'Order.work_order_no', 'Order.invoice_no', 'Order.created', 'Order.modified',
                'Order.submitted', 'Order.completed', 'Order.quantity', 'Order.price'
            );
            $query['group'] = array(
                'Customer.id', 'Customer.name', 'Customer.account_no',
                'LemUser.id', 'LemUser.username', 'LemUser.email',
                'Order.id', 'Order.status', 'Order.purchase_order', 'Order.notes',
                'Order.requested_delivery', 'Order.work_order_no', 'Order.invoice_no', 'Order.created', 'Order.modified',
                'Order.submitted', 'Order.completed'
            );
            return $query;
        }
        return $results;
    } // end _findOrderIndex

    /**
     * Attempts to read and return an open Order from the DB to use
     * as the user's shopping cart. If none is found, creates and returns
     * an initialized Order object
     * @param int $userId
     * @return array
     */
    public function getOpenCart($userId, $customerId) {
        $cart = $this->find('first', array(
           'conditions' => array(
               'Order.user_id' => $userId,
               'Order.status' => 'open'
            ),
            'contain' => array('OrderLine')
        ));
        if(empty($cart)) {
            $cart = array(
                'Order' => array(
                    'id' => null,
                    'purchase_order' => null,
                    'notes' => null,
                    'requested_delivery' => null,
                    'customer_id' => $customerId,
                    'user_id' => $userId,
                    'status' => 'open'
                ),
                'OrderLine' => array()
            );
        }
        return $cart;
    } // end getOpenCart()

    /**
     * Adds the request data to the user's cart
     * @param array $cart
     * @param array $data
     * @return mixed|array of Order data on success, false on failure
     */
    public function addToCart($cart, $data) {
        $skus = Hash::combine($cart['OrderLine'], '{n}.sku_id', '{n}');
        $cart['OrderLine'] = array();
        $lineNo = 0;
        foreach($skus as $ol) {
            $lineNo = $ol['line_no'];
        }
        foreach($data['OrderLine'] as $ol) {
            if(empty($ol['quantity'])) {
                continue;
            }
            /* Sku already existed in open cart, append quantity */
            if(isset($skus[$ol['sku_id']])) {
                $skus[$ol['sku_id']]['quantity'] += $ol['quantity'];
                $cart['OrderLine'][] = $skus[$ol['sku_id']];
            }
            else {
                $ol['line_no'] = ++$lineNo;
                $cart['OrderLine'][] = $ol;
            }
        }
        unset($cart['Order']['invoice']);
        if(!$this->saveAssociated($cart)) {
            $this->log("Unable to add item(s) to Cart: " . json_encode($cart), 'debug');
            return false;
        }
        else {
            return $this->find('first', array(
                'conditions' => array('Order.id' => $this->id),
                'contain' => array('OrderLine')
            ));
        }
    } // end addToCart()

    /**
     * Deletes the specified OrderItem from the database
     * @param int $orderLineId
     * @return mixed|array of Order data on success, false on failure
     */
    public function deleteItem($orderLineId) {
        $order = $this->OrderLine->find('first', array(
            'conditions' => array('OrderLine.id' => $orderLineId),
            'contain' => array('Order')
        ));
        if(empty($order) || !$this->OrderLine->delete($orderLineId)) {
            return false;
        }
        else {
            return $this->find('first', array(
                'conditions' => array('Order.id' => $order['Order']['id']),
                'contain' => array('OrderLine')
            ));
        }
    } // end deleteItem()

    /**
     * Submits the order
     * @param array $order
     * @return boolean
     */
    public function submit($order) {
        $order['Order']['submitted'] = date('Y-m-d H:i:s');
        $order['Order']['status'] = 'submitted';
        if(!$this->saveAssociated($order)) {
            $this->log("Unable to submit Order: " . json_encode($order), 'debug');
            return false;
        }

        return true;
    } // end deleteItem()

    /**
     * User-defined validation
     * @param string $status
     * @return boolean
     */
    public function isValidStatus($check) {
        $validStatuses = Configure::read('Orders.statuses');
        return isset($validStatuses[$check['status']]);
    } // end _isValidStatus

   /**
    * Sets the completed timestamp if status is completed
    *
    * @param array $options
    * @return boolean
    */
    public function beforeSave($options) {
        if($this->data['Order']['status'] === 'completed' &&
            (!isset($this->data['Order']['completed']) || is_null($this->data['Order']['completed']))) {
            $this->data['Order']['completed'] = date('Y-m-d H:i:s');
        }
    } // end afterSave()

   /**
    * Retrieves all required order information to pass to the afterSave event handler
    *
    * @param boolean $created True if this save created a new record
    * @return void
    */
    public function afterSave($created) {
        $order = $this->find('first', array(
            'conditions' => array('Order.id' => $this->id),
            'contain' => array('LemUser', 'Customer', 'OrderLine')
        ));

        $this->getEventManager()->attach(new OrderEventListener());
        $this->getEventManager()->dispatch(new CakeEvent('Model.Order.afterSave', $this, array('order' => $order)));
    } // end afterSave()

/**
 * Used to format the name for an attached invoice document
 * @param string $name
 * @param Transit\File $file
 */
    public function nameCallback($name, Transit\File $file) {
        return "invoice_{$this->data['Order']['invoice_no']}_order_{$this->data['Order']['id']}";
    } // end nameCallback()

} // end class Order
