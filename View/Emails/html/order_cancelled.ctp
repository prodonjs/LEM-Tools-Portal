<p>
    Hello, <?php echo $order['LemUser']['username'] ?><br><br>

    Your web order #: <?php printf('%06d', $order['Order']['id']); ?> has been cancelled. If you have any questions or concerns,
    please contact our customer service office. Thank you for being our customer.
</p>