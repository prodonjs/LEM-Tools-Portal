<?php
$order = $this->request->data;
/* Build list of valid statuses */
$statuses = Configure::read('Orders.statuses');
unset($statuses['open']);
switch($order['Order']['status']) {
    /* Orders in 'received' status can only be cancelled or completed */
    case 'received':
        unset($statuses['submitted']);
        break;
    /* Orders in 'cancelled' status can only be put back into processing */
    case 'cancelled':
        unset($statuses['submitted']);
        unset($statuses['completed']);
        break;
    /* Orders in 'completed' status cannot be changed */
    case 'completed':
        unset($statuses['submitted']);
        unset($statuses['received']);
        unset($statuses['cancelled']);
        break;
}

?>
<h2><small>Order #</small> <?php printf('%06d', $order['Order']['id']); ?></h2>
<?php
echo $this->Form->create('Order', array(
    'type' => 'file',
    'inputDefaults' => array(
        'error' => array('attributes' => array('class' => 'alert alert-error'))
    )
));
echo $this->Form->input('Order.id');
?>
<div class="clearfix">
    <div class="span4">
        <dl>
            <dt>Customer</dt>
            <dd><?php echo h($order['Customer']['name']); ?>&nbsp;</dd>
            <dt>E-mail Address</dt>
            <dd><?php echo $this->Html->link($order['LemUser']['email'], "mailto:{$order['LemUser']['email']}"); ?>&nbsp;</dd>
            <dt>Purchase Order</dt>
            <dd><?php echo h($order['Order']['purchase_order']); ?>&nbsp;</dd>
            <dt>Status</dt>
            <dd>
                <?php
                echo $this->Form->input('Order.status', array(
                    'label' => false,
                    'options' => $statuses
                ));
                ?>
            </dd>
            <dt>Work Order No</dt>
            <dd>
                <?php echo $this->Form->input('Order.work_order_no', array('label' => false)); ?>
            </dd>
            <dt class="order-shipped-fields">Invoice No</dt>
            <dd class="order-shipped-fields">
                <?php echo $this->Form->input('Order.invoice_no', array('label' => false)); ?>
            </dd>
            <dt class="order-shipped-fields">Invoice File</dt>
            <dd class="order-shipped-fields">
                <?php
                if(!is_null($order['Order']['invoice'])) {
                    echo $this->Html->link($order['Order']['invoice'],
                        '/' . Order::INVOICES_FOLDER . "/{$order['Order']['invoice']}",
                        array('target' => '_new')
                    );
                }
                else {
                    echo $this->Form->input('Order.invoice', array('label' => false, 'type' => 'file'));
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
            <dt class="order-shipped-fields">Tracking No</dt>
            <dd class="order-shipped-fields">
                <?php echo $this->Form->input('Order.tracking_no', array('label' => false)); ?>
            </dd>
            <dt>Notes</dt>
            <dd>
                <small>
                    <?php
                    echo $this->Form->input('Order.notes', array(
                        'label' => false,
                        'class' => 'span5'
                    ));
                    ?>
                    &nbsp;
                </small>
            </dd>
        </dl>
    </div>
</div>
<?php echo $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-inverse',
    'disabled' => $order['Order']['status'] === 'completed'
));
?>
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
<?php
$js = <<<EOF
/**
  * Shows/Hides input controls
  * depending on the status of the order
  * @param {String} orderStatus Value from the Order status control
  */
function toggleOrderFields(orderStatus) {
    if(orderStatus === 'completed') {
        $('.order-shipped-fields').show(0);
    }
    else {
        $('.order-shipped-fields').hide(0);
    }
};

/* Set initial visibility of shipped fields and toggle when #OrderStatus changes */
toggleOrderFields($('#OrderStatus').val());
$('#OrderStatus').change(function () {
    toggleOrderFields($(this).val());
});
EOF;
$this->Js->buffer($js);
?>