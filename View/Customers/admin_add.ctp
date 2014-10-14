<div class="customers form">
    <?php echo $this->Form->create('Customer', array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        ))); 
    ?>
    <fieldset>
        <legend>Add Customer</legend>
    <?php
        echo $this->Form->input('name');
        echo $this->Form->input('alternative_name');
        echo $this->Form->input('account_no');
        echo $this->Form->input('is_active', array('type' => 'hidden', 'value' => 1));
    ?>
    </fieldset>
<?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn')); ?>
</div>