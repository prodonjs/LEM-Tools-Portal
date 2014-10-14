<?php
/* cart-status container */
$items = $dollars = 0;
if(isset($cart['OrderLine'])) {
    foreach($cart['OrderLine'] as $ol) {
        $items += $ol['quantity'];
        $dollars += $ol['quantity'] * $ol['price'];
    }
}
$items = $this->Number->format($items);
$dollars = $this->Number->currency($dollars, 'USD');
echo "{$items} items - {$dollars}";
echo "<br>";
echo $this->Html->link('Checkout', '/cart', array('class' => 'btn btn-link btn-small'));
?>