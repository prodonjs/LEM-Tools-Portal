<h2><small>Order #</small> <?php printf('%06d', $order['Order']['id']); ?></h2>
<div class="span4">
    <dl>
        <dt>Purchase Order</dt>
        <dd><?php echo h($order['Order']['purchase_order']); ?>&nbsp;</dd>
        <dt>Status</dt>
        <dd>
            <?php
            $statuses = Configure::read('Orders.statuses');
            echo $statuses[$order['Order']['status']];?>&nbsp;
        </dd>
        <dt>Work Order No</dt>
        <dd><?php echo h($order['Order']['work_order_no']); ?>&nbsp;</dd>
        <dt>Invoice No</dt>
        <dd><?php echo h($order['Order']['invoice_no']); ?>&nbsp;</dd>
        <dt>Invoice File</dt>
        <dd>
            <?php
            if(!is_null($order['Order']['invoice'])) {
                echo $this->Html->link($order['Order']['invoice'],
                    '/' . Order::INVOICES_FOLDER . "/{$order['Order']['invoice']}",
                    array('target' => '_new')
                );
            }
            ?>
        </dd>
    </dl>
</div>
<div class="span4">
    <dl>
        <dt>Created</dt>
        <dd><?php echo date('n/j/Y g:i A', strtotime($order['Order']['created'])); ?>&nbsp;</dd>
        <dt>Requested Delivery</dt>
        <dd>
            <?php
            if(!is_null($order['Order']['requested_delivery'])) {
                echo date('n/j/Y', strtotime($order['Order']['requested_delivery']));
            }
            ?>&nbsp;
        </dd>
        <dt>Submitted</dt>
        <dd>
            <?php
            if(!is_null($order['Order']['submitted'])) {
                echo date('n/j/Y g:i A', strtotime($order['Order']['submitted']));
            }
            ?>&nbsp;
        </dd>
        <dt>Completed</dt>
        <dd>
            <?php
            if(!is_null($order['Order']['completed'])) {
                echo date('n/j/Y g:i A', strtotime($order['Order']['completed']));
            }
            ?>&nbsp;
        </dd>
        <dt>Notes</dt>
        <dd>
            <small><?php echo h($order['Order']['notes']); ?>&nbsp;</small>
        </dd>
    </dl>
</div>
<br>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Line No</th>
            <th>Part No</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Line Price</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $subtotal = $quantity = 0;
    foreach($order['OrderLine'] as $i => $ol) {
        $subtotal += $ol['quantity'] * $ol['price'];
        $quantity += $ol['quantity'];
    ?>
        <tr>
            <td><?php echo h($ol['line_no']); ?></td>
            <td><?php echo h($ol['part_no']); ?></td>
            <td><?php echo h($ol['description']); ?></td>
            <td><?php echo $this->Number->currency($ol['price'], 'USD'); ?></td>
            <td><?php echo $this->Number->format($ol['quantity']); ?></td>
            <td><?php echo $this->Number->currency($ol['price'] * $ol['quantity'], 'USD'); ?></td>
        </tr>
    <?php
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4">&nbsp</th>
            <th><?php echo $this->Number->format($quantity); ?></th>
            <th><?php echo $this->Number->currency($subtotal, 'USD'); ?></th>
        </tr>
    </tfoot>
</table>