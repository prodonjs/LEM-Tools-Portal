<div class="products form">
    <?php echo $this->Form->create('Product', array(
        'type' => 'file',
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    ));
    ?>
    <fieldset>
        <legend>Add Product</legend>
    <?php
        echo $this->Form->input('name', array('class' => 'span5'));
        echo $this->Form->input('description', array('class' => 'span5'));
        echo $this->Form->input('rank', array('label' => 'Catalog Order'));
        echo $this->Form->input('is_active', array('label' => 'Active?'));
    ?>
        <table class="table table-condensed">
            <caption>Add up to 5 images. <small>The images will be ordered by the Rank column values.</small></caption>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>File</th>
                    <th>Rank</th>
                </tr>
            </thead>
            <tbody>
                <?php
                for($i = 0; $i < 5; $i++) {
                ?>
                <tr>
                    <td>
                        <?php echo $this->Form->input("ProductImage.{$i}.name", array(
                            'label' => false,
                            'required' => false
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input("ProductImage.{$i}.file", array(
                            'label' => false,
                            'type' => 'file',
                            'required' => false
                        ));
                        ?>
                    </td>
                    <td>
                        <?php
                        echo $this->Form->input("ProductImage.{$i}.rank_order", array(
                            'label' => false,
                            'type' => 'select',
                            'options' => array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5),
                            'value' => $i + 1,
                            'class' => 'span1'
                        ));
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </fieldset>
<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn')); ?>
</div>