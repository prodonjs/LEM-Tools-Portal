<div id="navbar" class="navbar">
    <div class="navbar-inner">
        <div class="span4">
            <a class="brand" href="http://www.lemtools.com">
                <?php echo $this->Html->image('logo.png', array('alt' => 'LEM Tools')); ?>
            </a>
            <p class="muted text-center">
                <small>Manufacturer of High Quality Blind Rivet Nut Hand Tools</small>
            </p>
        </div>
        <div class="span4">
            <p style="line-height: 14px; font-size: 10px;">
                LEM Tools for the past <strong>60 years</strong> has been engineering and manufacturing the best
                Blind Rivet Nut Installation Hand Tools on the market. Don’t be fooled by imitations, our tools are
                <strong>Made in the USA.</strong>
            </p>
            <ul class="nav">
                <li><?php echo $this->Html->link('CATALOG', '/catalog'); ?></li>
                <li><?php echo $this->Html->link('MY ORDERS', '/orders'); ?></li>
            </ul>
        </div>
        <div class="span3">
            <p id="cart-status" class="pull-right">
                <?php
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
                echo $this->Html->link(
                    '<i class="icon-shopping-cart"></i>&nbsp;Checkout', '/cart', array(
                        'class' => 'btn btn-link btn-small',
                        'escape' => false
                    )
                );
                ?>
            </p>
        </div>
    </div>
</div>