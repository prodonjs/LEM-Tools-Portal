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

echo "Hello, {$order['LemUser']['username']}\n\n";

echo "Your order #: {$order['Order']['id']} has been completed. You should receive your final invoice soon. ";
echo "Please contact our customer service office if you have any questions concerning shipping or billing. ";
echo "Thank you for being our customer.\n\n";
echo "----------------------------------------------------------------------------------------------------\n";
echo "Order #: {$order['Order']['id']}\n";
echo "Customer: {$order['Customer']['account_no']} - {$order['Customer']['name']}\n";
echo "Purchase Order #: {$order['Order']['purchase_order']}\n";
$delivery = is_null($order['Order']['requested_delivery']) ? '' : date('n/j/Y', strtotime($order['Order']['requested_delivery']));
echo "Requested Delivery: {$delivery}\n";
echo "Submitted at: " . date('n/j/Y g:i:s A', strtotime($order['Order']['submitted'])) . "\n";
echo "Notes: {$order['Order']['notes']}\n\n";

echo " Line |       Part No      |                  Description                      |  Qty  |   Price    \n";
$subtotal = $quantity = 0;
foreach($order['OrderLine'] as $ol) {
    $subtotal += $ol['quantity'] * $ol['price'];
    $quantity += $ol['quantity'];
    printf("%6d|%-20.20s|%-51.51s|%7d|%12.2f\n", $ol['line_no'], $ol['part_no'], $ol['description'], $ol['quantity'], $ol['price']);
}
printf("%80s%7d|%12.2f\n", '', $quantity, $subtotal);