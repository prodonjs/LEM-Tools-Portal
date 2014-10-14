<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
    /**
     * Map of controller actions to page titles
     * @var array
     */
    protected $_actionTitles = array(
        'admin_index' => 'Administrators - Manage Products',
        'admin_add' => 'Administrators - Add Product',
        'admin_edit' => 'Administrators - Edit Product'
    );

    /**
     * Map of controller actions to breadcrumb link names
     */
    protected $_breadcrumbs = array(
        'admin_index' => array(
            'page' =>'Manage Products',
            'reset' => true
        ),
        'admin_add' => array(
            'page' =>'Add Product',
            'reset' => false
        ),
        'admin_edit' => array(
            'page' => 'Edit Product',
            'reset' => false
        )
    );

/**
 * index method
 *
 * @return void
 */
	public function index($viewType = 'list') {
		$this->set('products', $this->Product->find('all', array(
            'contain' => array(
                'Sku' => array(
                    'conditions' => array('Sku.is_active' => 1),
                    'order' => 'Sku.part_no'
                ),
                'ProductImage'
            ),
            'conditions' => array('Product.is_active' => 1),
            'order' => 'Product.rank'
        )));
        $this->set('title_for_layout', 'Product Catalog');
        $this->set('viewType', $viewType);
	} // end index()

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->set('products', $this->paginate());
        $this->set('title_for_layout', 'Administrators - Manage Products');
        $this->setBreadcrumb('Manage Products', true);
	} // end admin_index()

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Product->create();
            /* Unset any empty ProductImage entries */
            $data = $this->request->data;
            if(!empty($data['ProductImage'])) {
                foreach($data['ProductImage'] as $key => $pi) {
                    if(empty($pi['name'])) {
                        unset($data['ProductImage'][$key]);
                    }
                }
            }
			if ($this->Product->saveAssociated($data)) {
				$this->flashMessage('The product has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Product->id));
			} else {
				$this->flashMessage('The product could not be saved. Please, try again.', 'flash_alert');
			}
		}
        $this->set('title_for_layout', 'Administrators - Add Product');
        $this->setBreadcrumb('Add Product');
	} // end admin_add()

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Product->exists($id)) {
			throw new NotFoundException(__('Invalid product'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
            $data = $this->request->data;
            if(!empty($data['ProductImage'])) {
                foreach($data['ProductImage'] as $key => $pi) {
                    if(empty($pi['name'])) {
                        unset($data['ProductImage'][$key]);
                    }
                }
            }
			if ($this->Product->saveAssociated($data)) {
				$this->flashMessage('The product has been saved', 'flash_success');
				$this->redirect(array('action' => 'edit', $this->Product->id));
			} else {
                $this->request->data = $data;
				$this->flashMessage('The product could not be saved. Please, try again.', 'flash_alert');
			}
		} else {
			$options = array(
                'conditions' => array('Product.' . $this->Product->primaryKey => $id),
                'contain' => array('ProductImage')
            );
			$this->request->data = $this->Product->find('first', $options);
		}
        $this->set('title_for_layout', 'Administrators - Edit Product');
        $this->setBreadcrumb('Edit Product');
	} // end admin_edit()

/**
 * admin_delete_image method
 *
 * @throws NotFoundException
 * @param int $productImageId
 * @return void
 */
	public function admin_delete_image($productImageId = null) {
        $this->Product->ProductImage->id = $productImageId;
        $productImage = $this->Product->ProductImage->read();
        $productId = $productImage['Product']['id'];
		if (empty($productImage)) {
			throw new NotFoundException(__('Invalid product image'));
		}
		if ($this->Product->ProductImage->delete()) {
			$this->flashMessage('The product image has been deleted', 'flash_success');
		}
		else {
            $this->flashMessage('The product image could not be deleted. Please, try again.', 'flash_alert');
        }
		$this->redirect(array('action' => 'edit', $productId));
	} // end admin_delete_image()
}
