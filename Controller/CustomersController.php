<?php
App::uses('AppController', 'Controller');
/**
 * Customers Controller
 *
 * @property Customer $Customer
 */
class CustomersController extends AppController {
    public $components = array('Search.Prg');

    /**
     * Map of controller actions to page titles
     * @var array
     */
    protected $_actionTitles = array(
        'admin_index' => 'Administrators - Manage Customers',
        'admin_add' => 'Administrators - Add Customer',
        'admin_edit' => 'Administrators - Edit Customer'
    );

    /**
     * Map of controller actions to breadcrumb link names
     */
    protected $_breadcrumbs = array(
        'admin_index' => array(
            'page' =>'Manage Customers',
            'reset' => true
        ),
        'admin_add' => array(
            'page' =>'Add Customer',
            'reset' => false
        ),
        'admin_edit' => array(
            'page' => 'Edit Customer',
            'reset' => false
        )
    );

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
        $this->Prg->commonProcess();
		unset($this->Customer->validate['name']);
		unset($this->Customer->validate['account_no']);
		$this->Customer->data['Customer'] = $this->request->query;
		if ($this->Customer->Behaviors->attached('Searchable')) {
			$parsedConditions = $this->Customer->parseCriteria($this->request->query);
		} else {
			$parsedConditions = array();
		}
		$this->Paginator->settings[$this->modelClass]['conditions'] = $parsedConditions;
		$this->Paginator->settings[$this->modelClass]['order'] = array($this->modelClass . '.created' => 'desc');

		$this->Customer->contain(array('User', 'Order'));
		$this->set('customers', $this->paginate());

        if($this->request->is('ajax')) {
            $this->autoRender = false;
            return json_encode($this->viewVars['customers']);
        }
	} // end admin_index()

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Customer->create();
			if ($this->Customer->save($this->request->data)) {
				$this->flashMessage('The customer has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Customer->id));
			} else {
				$this->flashMessage('The customer could not be saved. Please, try again.', 'flash_alert');
			}
		}
	} // end admin_add()

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Customer->exists($id)) {
			throw new NotFoundException(__('Invalid customer'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Customer->save($this->request->data)) {
				$this->flashMessage('The customer has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Customer->id));
			} else {
				$this->flashMessage('The customer could not be saved. Please, try again.', 'flash_alert');
			}
		} else {
			$options = array('conditions' => array('Customer.' . $this->Customer->primaryKey => $id));
			$this->request->data = $this->Customer->find('first', $options);
		}
	} // end admin_edit()

} // end class Customer
