<div class="hero-unit">
  <h1>Thanks!</h1>
  <p>
      Your order has been received. You should also receive a confirmation receipt at <?php echo $this->Session->read('Auth.User.email'); ?>.
      Thank you for your business.
  </p>
  <p>
      <?php
      echo $this->Html->link('Back to Catalog', '/catalog', array('class' => 'btn btn-primary btn-large'));
      echo '&nbsp;';
      echo $this->Html->link('My Order History', '/orders', array('class' => 'btn btn-primary btn-large'));
      ?>
  </p>
</div>