<p>
    Hello, <?php echo $order['LemUser']['username'] ?><br><br>

    Your order has been received by our administrators and is under review. You will be notified when your order has been acknowledged.
    Please save the following information as confirmation of your order and thank you for being our customer.
</p>
<p>
    Web Order #: <strong><?php printf('%06d', $order['Order']['id']); ?></strong><br>
    Customer: <strong><?php echo "{$order['Customer']['account_no']} - {$order['Customer']['name']}"; ?></strong><br>
    Purchase Order #: <strong><?php echo $order['Order']['purchase_order']; ?></strong><br>
    <?php
    $delivery = is_null($order['Order']['requested_delivery']) ? '' : date('n/j/Y', strtotime($order['Order']['requested_delivery']));
    ?>
    Requested Delivery: <strong><?php echo $delivery; ?></strong><br>
    Submitted at: <strong><?php echo date('n/j/Y g:i:s A', strtotime($order['Order']['submitted'])); ?></strong><br>
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