<?php
App::uses('AppController', 'Controller');
/**
 * Skus Controller
 *
 * @property Sku $Sku
 */
class SkusController extends AppController {
    /**
     * Map of controller actions to page titles
     * @var array
     */
    protected $_actionTitles = array(
        'admin_index' => 'Administrators - Manage SKUs',
        'admin_add' => 'Administrators - Add SKU',
        'admin_edit' => 'Administrators - Edit SKU'
    );

    /**
     * Map of controller actions to breadcrumb link names
     */
    protected $_breadcrumbs = array(
        'admin_index' => array(
            'page' =>'Manage SKUs',
            'reset' => true
        ),
        'admin_add' => array(
            'page' =>'Add SKU',
            'reset' => false
        ),
        'admin_edit' => array(
            'page' => 'Edit SKU',
            'reset' => false
        )
    );

/**
 * index method
 *
 * @return void
 */
	public function index($productId = null) {
		if(is_null($productId)) {
            throw new NotFoundException('Invalid Product Specified');
        }
        $this->Paginator->settings[$this->modelClass] = array(
            'conditions' => array(
                'Sku.product_id' => $productId,
                'Sku.is_active' => true
            ),
            'order' => 'Sku.part_no',
            'recursive' => -1
        );
		$this->set('skus', $this->paginate());
        $product = $this->Sku->Product->find('first', array(
            'conditions' => array('Product.id' => $productId),
            'contain' => array('ProductImage')
        ));
        $this->set('product', $product);
        $this->set('title_for_layout', "{$product['Product']['name']} Parts List");
	} // end index()

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index($productId = null) {
		if(is_null($productId)) {
            throw new NotFoundException('Invalid Product Specified');
        }
        $this->Paginator->settings[$this->modelClass] = array(
            'conditions' => array('Sku.product_id' => $productId),
            'recursive' => -1
        );
		$this->set('skus', $this->paginate());
        $this->set('product', $this->Sku->Product->find('first', array(
            'conditions' => array('Product.id' => $productId),
            'recursive' => -1
        )));
	} // end admin_index()

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add($productId = null) {
        if(is_null($productId)) {
            throw new NotFoundException('Invalid Product Specified');
        }
		if ($this->request->is('post')) {
			$this->Sku->create();
			if ($this->Sku->save($this->request->data)) {
				$this->flashMessage('The SKU has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Sku->id));
			} else {
				$this->flashMessage('The SKU could not be saved. Please, try again.', 'flash_alert');
			}
		}
        $this->set('product', $this->Sku->Product->find('first', array(
            'conditions' => array('Product.id' => $productId),
            'recursive' => -1
        )));
	} // end admin_add()

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Sku->exists($id)) {
			throw new NotFoundException(__('Invalid sku'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Sku->save($this->request->data)) {
				$this->flashMessage('The SKU has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Sku->id));
			} else {
				$this->flashMessage('The SKU could not be saved. Please, try again.', 'flash_alert');
			}
		} else {
			$options = array('conditions' => array('Sku.' . $this->Sku->primaryKey => $id));
			$this->request->data = $this->Sku->find('first', $options);
		}
	} // end admin_edit()

} // end class SkusController()
