<div class="skus form">
    <?php echo $this->Form->create('Sku', array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    )); 
    ?>
    <fieldset>
        <legend>Edit SKU <?php echo h($this->request->data['Sku']['part_no']); ?></legend>
    <?php
        echo $this->Form->input('id');
        echo $this->Form->input('product_id', array('type' => 'hidden'));
        echo $this->Form->input('part_no', array('label' => 'Part No'));
        echo $this->Form->input('alternative_part_no', array('label' => 'Alt Part No'));
        echo $this->Form->input('name', array('class' => 'span5'));
        echo $this->Form->input('description', array('class' => 'span5'));
        echo $this->Form->input('price');
        echo $this->Form->input('is_active', array('label' => 'Active?'));
    ?>        
    </fieldset>
<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn')); ?>
</div>