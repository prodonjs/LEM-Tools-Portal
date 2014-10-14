<h2>LEM Tools Product Catalog</h2>
<p class="lead">
    Click a product family to learn more or to place your order!
</p>
<p>
    View as:
    <?php
    $viewTypes = array('list' => 'List', 'tiles' => 'Tiles');
    foreach($viewTypes as $v => $label) {
        if($viewType === $v) {
            echo "<strong>{$label}</strong>";
        }
        else {
            echo $this->Html->link($label, "/catalog/{$v}");
        }
        echo ' | ';
    }
    ?>
</p>
<?php
/*
 * Start of view block for tiled products display
 */
$this->start('tiles');
foreach($products as $p) : ?>
<div class="span4 well product-box">
    <h4><?php echo h($p['Product']['name']); ?></h4>
    <p>
        <small><?php echo h($p['Product']['description']); ?></small>
    </p>
    <?php
    if(!empty($p['ProductImage'])) {
        $picture = array_shift($p['ProductImage']);
        echo $this->Html->image("products/{$picture['file']}", array(
            'alt' => $picture['name'],
            'class' => 'img-polaroid'
        ));
    }
    else {
        echo $this->Html->image('coming_soon_150x150.jpg', array(
            'alt' => 'Coming Soon',
            'class' => 'img-polaroid'
        ));
    }
    ?>
    <p>
        <small>
            <?php echo $this->Number->format(count($p['Sku'])); ?> SKUs available
        </small>
        <br>
        <?php
        echo $this->Html->link('Place an Order', "/catalog/{$p['Product']['id']}", array('class' => 'btn btn-inverse'));
        ?>
    </p>
</div>
<?php
endforeach;
$this->end();
/* End of view block for tiled products display */
?>

<?php
/*
 * Start of view block for tiled products display
 */
$this->start('list');
?>
<table class="table">
    <thead>
        <tr>
            <th>&nbsp;</th>
            <th>Name</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($products as $p) : ?>
        <tr>
            <td>
                <?php
                if(!empty($p['ProductImage'])) {
                    $picture = array_shift($p['ProductImage']);
                    echo $this->Html->image("products/{$picture['file']}", array(
                        'alt' => $picture['name'],
                        'class' => 'img-polaroid'
                    ));
                }
                else {
                    echo $this->Html->image('coming_soon_150x150.jpg', array(
                        'alt' => 'Coming Soon',
                        'class' => 'img-polaroid'
                    ));
                }
                ?>
            </td>
            <td>
                <?php echo h($p['Product']['name']); ?><br>
                <small>
                    <?php echo $this->Number->format(count($p['Sku'])); ?> SKUs available
                </small>
                <br>
                <?php
                echo $this->Html->link('Place an Order', "/catalog/{$p['Product']['id']}", array('class' => 'btn btn-inverse'));
                ?>
            </td>
            <td><small><?php echo h($p['Product']['description']); ?></small></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$this->end();
/* End of view block for list products display */

echo $this->fetch($viewType);

$js = <<<EOF
/* Get the max height of all product-box elements and set all other boxes to that height */
var max = 0;
$('div.product-box').each(function() {
    max = $(this).height() > max ? $(this).height() : max;
});
$('div.product-box').height(max);
EOF;
$this->Js->buffer($js);
?>
