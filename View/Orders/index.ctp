<div class="orders index">
	<h2>My Orders</h2>
	<?php
	//echo $this->Form->create('Order', array('action' => 'index'));
    //echo $this->Form->input('name', array('label' => 'Name'));
	//echo $this->Form->input('account_no', array('label' => 'Account Number'));
	//echo $this->Form->end(array('label' => 'Search', 'class' => 'btn'));
    ?>
	<?php
    echo $this->element('paging');
	echo $this->element('pagination'); ?>
	<table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $this->Paginator->sort('id', 'Order No'); ?></th>
                <th><?php echo $this->Paginator->sort('purchase_order', 'PO'); ?></th>
                <th><?php echo $this->Paginator->sort('status', 'Status'); ?></th>
                <th><?php echo $this->Paginator->sort('quantity', 'Items'); ?></th>
                <th><?php echo $this->Paginator->sort('price', 'Price'); ?></th>
                <th><?php echo $this->Paginator->sort('submitted'); ?></th>
                <th><?php echo $this->Paginator->sort('completed'); ?></th>
                <th><?php echo $this->Paginator->sort('work_order_no', 'Work Order No'); ?></th>
                <th><?php echo $this->Paginator->sort('invoice_no', 'Invoice No'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $o) { ?>
			<tr>
                <td>
                    <?php
                    echo $this->Html->link('View', array('action' => 'view', $o['Order']['id']), array(
                        'class' => 'btn btn-mini'
                    ));
                    ?>
                </td>
                <td>
                    <?php printf('%06d', $o['Order']['id']); ?>
                </td>
				<td>
					<?php echo h($o['Order']['purchase_order']); ?>
				</td>
				<td>
					<?php
                    $statuses = Configure::read('Orders.statuses');
                    echo $statuses[$o['Order']['status']];
                    ?>
				</td>
                <td><?php echo $this->Number->format($o['Order']['quantity']); ?></th>
                <td><?php echo $this->Number->currency($o['Order']['price'], 'USD'); ?></th>
				<td>
					<?php
                    if(!is_null($o['Order']['submitted'])) {
                        echo date('n/j/Y g:i A', strtotime($o['Order']['submitted']));
                    }
                    ?>
				</td>
                <td>
					<?php
                    if(!is_null($o['Order']['completed'])) {
                        echo date('n/j/Y g:i A', strtotime($o['Order']['completed']));
                    }
                    ?>
				</td>
                <td>
					<?php echo h($o['Order']['work_order_no']); ?>
				</td>
                <td>
					<?php echo h($o['Order']['invoice_no']); ?>
				</td>
			</tr>
		<?php } ?>
        </tbody>
	</table>
	<?php echo $this->element('pagination'); ?>
</div>
