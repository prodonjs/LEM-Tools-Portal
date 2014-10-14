<?php
$order = $this->request->data;
?>
<h2><small>Open Order #</small> <?php printf('%06d',$order['Order']['id']); ?></h2>
<?php
echo $this->Form->create('Order', array(
    'inputDefaults' => array(
        'error' => array('attributes' => array('class' => 'alert alert-error'))
    )
));
echo $this->Form->input('Order.id');
?>
<p>
    <?php
    echo $this->Form->input('Order.purchase_order', array(
        'label' => 'Purchase Order'
    ));
    echo $this->Form->input('Order.requested_delivery', array(
        'label' => 'Requested Delivery Date',
        'type' => 'text',
        'data-date-format' => 'yyyy-mm-dd'
    ));
    echo $this->Form->input('Order.notes', array(
        'label' => 'Notes or Instructions',
        'class' => 'span5'
    ));
    ?>
</p>
<?php echo $this->Form->submit('Submit', array(
    'class' => 'btn btn-inverse',
    'disabled' => is_null($order['Order']['id'])
)); ?>
<br>
<table class="table table-striped">
    <thead>
        <tr>
            <th>&nbsp;</th>
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
            <td>
                <?php
                echo $this->Html->link('Delete', array(
                    'action' => 'delete_item', $ol['id']),
                    array('class' => 'order-line-delete btn btn-mini'),
                    'Are you sure you want to delete this line?'
                );
                ?>
            </td>
            <td>
                <?php
                echo $this->Form->input("OrderLine.{$i}.id");
                echo h($ol['line_no']);
                ?>
            </td>
            <td><?php echo h($ol['part_no']); ?></td>
            <td><?php echo h($ol['description']); ?></td>
            <td><?php echo $this->Number->currency($ol['price'], 'USD'); ?></td>
            <td>
                <?php
                echo $this->Form->input("OrderLine.{$i}.quantity", array(
                    'label' => false,
                    'class' => 'span1',
                    'required' => false
                ));
                ?>
            </td>
            <td><?php echo $this->Number->currency($ol['price'] * $ol['quantity'], 'USD'); ?></td>
        </tr>
    <?php
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">&nbsp</th>
            <th><?php echo $this->Number->format($quantity); ?></th>
            <th><?php echo $this->Number->currency($subtotal, 'USD'); ?></th>
        </tr>
    </tfoot>
</table>
<?php echo $this->Form->end(array(
    'label' => 'Submit',
    'class' => 'btn btn-inverse',
    'disabled' => is_null($order['Order']['id'])
));

/* Bootstrap Datepicker */
$this->Html->script('vendor/bootstrap-datepicker', array('inline' => false));
$this->Html->css('datepicker', null, array('inline' => false));
$js = <<<EOF
var now = new Date();
$('#OrderRequestedDelivery').datepicker({
    onRender: function(date) {
        return date.valueOf() <= now.valueOf() ? 'disabled' : '';
    }
});
EOF;
$this->Js->buffer($js);
?>