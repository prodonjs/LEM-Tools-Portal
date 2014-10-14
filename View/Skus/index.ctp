<div class="skus index">
    <div class="clearfix">
        <div id="product-thumbnails" class="pull-left">
            <?php
            foreach($product['ProductImage'] as $pi) {
                echo $this->Html->image("products/{$pi['thumbnail_file']}", array(
                    'alt' => $pi['name'],
                    'class' => 'img-polaroid'
                ));
            }
            ?>
        </div>
        <p>
            <h3><?php echo h($product['Product']['name']);?></h3>
            <small><?php echo h($product['Product']['description']);?></small>
        </p>
    </div>
	<?php
    echo $this->element('paging');
	echo $this->element('pagination');
    ?>
    <?php
    echo $this->Form->create('Order', array('action' => 'add'));
    echo $this->Form->input('id', array(
        'type' => 'hidden',
        'value' => $cart['Order']['id']
    ));
    echo $this->Form->input('user_id', array(
        'type' => 'hidden',
        'value' => $cart['Order']['user_id']
    ));
    echo $this->Form->input('customer_id', array(
        'type' => 'hidden',
        'value' => $cart['Order']['customer_id']
    ));
    echo $this->Form->submit('Add Items to Cart', array('class' => 'btn btn-inverse'));
    ?>
	<table class="table">
        <thead>
            <tr>
                <th><?php echo $this->Paginator->sort('part_no', 'Part No'); ?></th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('price'); ?></th>
                <th>Order Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0;
            foreach($skus as $sku) {
            ?>
			<tr>
                <td><?php echo h($sku['Sku']['part_no']); ?></td>
                <td><?php echo h($sku['Sku']['name']); ?></td>
                <td><?php echo $this->Number->currency($sku['Sku']['price'], 'USD'); ?></td>
				<td>
                    <?php
                    echo $this->Form->input('OrderLine.' . $i . '.sku_id', array(
                        'type' => 'hidden',
                        'value' => $sku['Sku']['id']
                    ));
                    echo $this->Form->input('OrderLine.' . $i . '.part_no', array(
                        'type' => 'hidden',
                        'value' => $sku['Sku']['part_no']
                    ));
                    echo $this->Form->input('OrderLine.' . $i . '.description', array(
                        'type' => 'hidden',
                        'value' => $sku['Sku']['name']
                    ));
                    echo $this->Form->input('OrderLine.' . $i . '.price', array(
                        'type' => 'hidden',
                        'value' => $sku['Sku']['price']
                    ));
                    echo $this->Form->input('OrderLine.' . $i . '.quantity', array(
                        'label' => false,
                        'class' => 'span1',
                        'required' => false
                    ));
                    $i++;
                    ?>
                </td>
			</tr>
		<?php } ?>
        </tbody>
	</table>
	<?php
    echo $this->Form->submit('Add Items to Cart', array('class' => 'btn btn-inverse'));
    echo $this->Form->end();
    echo $this->element('pagination');
    ?>
</div>
<?php echo $this->element('orders_add'); ?>