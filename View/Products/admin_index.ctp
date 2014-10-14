<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="products index">
	<h2>Product Administration</h2>	
    <p>
        <?php echo $this->Html->link('Add Product', array('action' => 'add'), array('class' => 'btn btn-inverse')); ?>
    </p>
	<?php
    echo $this->element('paging');
	echo $this->element('pagination'); ?>
	<table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>     
                <th>Thumbnail</th>
                <th><?php echo $this->Paginator->sort('is_active', 'Active?'); ?></th>                
                <th><?php echo $this->Paginator->sort('created'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product) { ?>
			<tr>
                <td>
                    <?php 
                    echo $this->Html->link('Edit', array('admin' => true, 'action' => 'edit', $product['Product']['id']), array(
                        'class' => 'btn btn-mini btn-block'
                    ));
                    echo $this->Html->link('Manage SKUs', array(
                        'admin' => true, 
                        'controller' => 'skus',
                        'action' => 'index', $product['Product']['id']), array(
                        'class' => 'btn btn-mini btn-block'
                    ));
                    ?>
                </td>
				<td><?php echo h($product['Product']['name']); ?></td>
                <td>
                    <?php
                    if(!empty($product['ProductImage'])) {
                        $thumbnail = array_shift($product['ProductImage']);
                        echo $this->Html->image("products/{$thumbnail['thumbnail_file']}", array(
                            'alt' => $thumbnail['name'], 
                            'class' => 'img-polaroid'
                        ));
                    }
                    else {
                        echo $this->Html->image('coming_soon_150x150.jpg', array(
                            'alt' => 'Coming Soon', 
                            'class' => 'img-polaroid'
                        ));
                    }
                    ?>
                </td>
				<td>
					<?php echo $product['Product']['is_active'] == 1 ? 'Yes' : 'No'; ?>
				</td>
				<td>
					<?php echo date('n/j/Y', strtotime($product['Product']['created'])); ?>
				</td>				
			</tr>            
		<?php } ?>
        </tbody>
	</table>
	<?php echo $this->element('pagination'); ?>
</div>
