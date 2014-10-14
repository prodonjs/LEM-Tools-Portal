<div class="customers form">
    <?php echo $this->Form->create('Customer', array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        ))); 
    ?>
    <fieldset>
        <legend>Edit Customer - <?php echo h($this->request->data['Customer']['account_no']); ?></legend>
    <?php
        echo $this->Form->input('id');
        echo $this->Form->input('name');
        echo $this->Form->input('alternative_name');
        echo $this->Form->input('account_no');
        echo $this->Form->input('is_active', array('label' => 'Active?'));
    ?>
    </fieldset>
<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn')); ?>
</div>