<p>
    Hello, <?php echo $order['LemUser']['username'] ?><br><br>

    Web order #: <?php printf('%06d', $order['Order']['id']); ?> has been completed. You should receive your final invoice soon.
    Please contact our customer service office if you have any questions concerning shipping or billing.
    Thank you for being our customer.
</p>
<p>
    Web Order #: <strong><?php printf('%06d', $order['Order']['id']); ?></strong><br>
    Work Order #: <strong><?php echo $order['Order']['work_order_no']; ?></strong><br>
    Invoice #: <strong><?php echo $order['Order']['invoice_no']; ?></strong><br>
    Customer: <strong><?php echo "{$order['Customer']['account_no']} - {$order['Customer']['name']}"; ?></strong><br>
    Purchase Order #: <strong><?php echo $order['Order']['purchase_order']; ?></strong><br>
    <?php
    $delivery = is_null($order['Order']['requested_delivery']) ? '' : date('n/j/Y', strtotime($order['Order']['requested_delivery']));
    ?>
    Requested Delivery: <strong><?php echo $delivery; ?></strong><br>
    Submitted at: <strong><?php echo date('n/j/Y g:i:s A', strtotime($order['Order']['submitted'])); ?></strong><br>
    Completed at: <strong><?php echo date('n/j/Y g:i:s A', strtotime($order['Order']['completed'])); ?></strong><br>
    Notes: <small><?php echo $order['Order']['notes']; ?></small>
</p>
<table>
    <thead>
        <tr>
            <th>Line</th>
            <th>Part No</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $subtotal = $quantity = 0;
        foreach($order['OrderLine'] as $ol) :
            $subtotal += $ol['quantity'] * $ol['price'];
            $quantity += $ol['quantity'];
        ?>
        <tr>
            <td><?php echo $ol['line_no']; ?></td>
            <td><?php echo $ol['part_no']; ?></td>
            <td><?php echo $ol['description']; ?></td>
            <td><?php echo CakeNumber::format($ol['quantity']); ?></td>
            <td><?php echo CakeNumber::currency($ol['price'], 'USD'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <th colspan="3">&nbsp;</th>
        <th><?php echo CakeNumber::format($quantity); ?></th>
        <th><?php echo CakeNumber::currency($subtotal, 'USD'); ?></th>
    </tfoot>
</table>