<?php
App::uses('AppController', 'Controller');
/**
 * Orders Controller
 *
 * @property Order $Order
 */
class OrdersController extends AppController {
    /* Use the Referer Component */
    public $components = array(
        'Utils.Referer',
        'Security' => array(
            'csrfUseOnce' => false
        )
    );

    /**
     * Map of controller actions to page titles
     * @var array
     */
    protected $_actionTitles = array(
        'index' => 'My Orders',
        'view' => 'View Order',
        'edit' => 'Open Order Item(s)',
        'admin_index' => 'Administrators - Manage Orders',
        'admin_edit' => 'Administrators - Edit Order'
    );

    /**
     * Map of controller actions to breadcrumb link names
     */
    protected $_breadcrumbs = array(
        'index' => array(
            'page' =>'My Orders',
            'reset' => true
        ),
        'view' => array(
            'page' =>'View Order',
            'reset' => false
        ),
        'admin_index' => array(
            'page' => 'Manage Orders',
            'reset' => true
        ),
        'admin_edit' => array(
            'page' => 'Manage Order',
            'reset' => false
        )
    );

/**
 * index method
 *
 * @return void
 */
	public function index() {
        $this->Paginator->settings[$this->modelClass]['findType'] = 'orderIndex';
        $this->Paginator->settings[$this->modelClass]['order'] = 'Order.id DESC';
        $this->Paginator->settings[$this->modelClass]['conditions'] = array(
            'Order.user_id' => $this->Auth->user('id'),
            'Order.status !=' => 'open'
        );
		$this->set('orders', $this->paginate());
	} // end index()

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
        if (!$this->Order->exists($id)) {
			throw new NotFoundException(__('Invalid order'));
		}
		$options = array(
            'conditions' => array('Order.' . $this->Order->primaryKey => $id),
            'contain' => array(
                'Customer',
                'LemUser',
                'OrderLine' => array('Sku')
            )
        );
		$this->set('order', $this->Order->find('first', $options));
	} // end view()

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
            $result = $this->Order->addToCart($this->cart, $this->request->data);
            if($result) {
                $this->cart = $result;
                $this->Session->write('Cart', $this->cart);
                $message = "Items added to Order #{$this->cart['Order']['id']}";
                $messageClass = 'flash_success';
            }
            else {
                $message = 'Sorry, there was an error adding items to your cart. Please try again.';
                $messageClass = 'flash_alert';
            }
            /* Ajax request */
            if($this->request->is('ajax')) {
                if(!$result) {
                    $this->log("Failed to process OrdersController::add(): " . json_encode($this->request->data), 'debug');
                    throw new InternalErrorException('Error adding items to order');
                }
                $this->layout = 'ajax';
                $this->set('cart', $this->cart);
            }
            /* Normal request */
            else {
                $this->flashMessage($message, $messageElement);
                /* Should use the $referer variable or default to the catalog page */
                $this->redirect(array('/catalog'));
            }
		}
        else {
            throw new MethodNotAllowedException();
        }
	} // end add()

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if(!isset($this->cart) || empty($this->cart)) {
            $this->log("Uninitialized cart OrdersController::edit(): " . json_encode($_SESSION), 'debug');
            throw new InternalErrorException('Error displaying cart');
        }
        if(empty($this->cart['OrderLine'])) {
            $this->flashMessage('Your cart is empty. Browse our catalog to order items', 'flash_default');
            $this->redirect('/catalog');
        }
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Order->submit($this->request->data)) {
				$this->flashMessage('Order has been submitted', 'flash_success');
                $this->Session->delete('Cart');
                $this->redirect('/thanks');
			} else {
				$this->flashMessage('Sorry, there was an error adding during submission. Please try again.', 'flash_alert');
                $this->request->data = $this->cart;
			}
		} else {
            $this->request->data = $this->cart;
		}
	} // end edit()

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete_item($orderLineId = null) {
        $result = $this->Order->deleteItem($orderLineId);
		if ($result) {
            $this->cart = $result;
            $this->Session->write('Cart', $this->cart);
			$this->flashMessage('Item has been deleted from cart', 'flash_success');
		}
        else {
            $this->flashMessage('Sorry, there was an error deleting the item from your cart. Please try again.', 'flash_alert');
        }
		$this->redirect('/cart');
	} // end delete_item()

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings[$this->modelClass]['findType'] = 'orderIndex';
        $this->Paginator->settings[$this->modelClass]['conditions'] = array(
            'Order.submitted IS NOT NULL'
        );
        $this->Paginator->settings[$this->modelClass]['order'] = 'Order.id DESC';
		$this->set('orders', $this->paginate());
	} // end admin_index()

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Order->exists($id)) {
			throw new NotFoundException('Invalid order');
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Order->save($this->request->data)) {
				$this->flashMessage('Order has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Order->id));
			} else {
				$this->flashMessage('The order could not be saved. Please, try again.', 'flash_alert');
			}
		} else {
            $this->Order->contain(array(
                'Customer',
                'LemUser',
                'OrderLine' => array('Sku')
            ));
			$options = array('conditions' => array('Order.' . $this->Order->primaryKey => $id));
			$this->request->data = $this->Order->find('first', $options);
		}
	} // end admin_edit()

    /**
     * Designed to be used with a requestAction call to
     * return the number of orders awaiting processing
     * @return mixed
     */
    public function admin_submitted_count() {
        $this->Order->recursive = -1;
        $count = $this->Order->find('count', array(
            'conditions' => array('Order.status' => 'submitted')
        ));
        if($this->request->is('requested')) {
            return $count;
        }
        else {
            throw new BadRequestException('This method can only be called internally');
        }
    } // end admin_submitted_status

} // end class OrdersController
