<div class="users form">
	<?php echo $this->Form->create($model, array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    ));
    ?>
    <fieldset>
        <legend>Add User</legend>
        <?php
        $this->Form->unlockField('LemUser.customer_id');
        echo $this->Form->input('username', array(
            'label' => __d('users', 'Username')));
        echo $this->Form->input('email', array(
            'label' => __d('users', 'E-mail')));
        echo $this->Form->input('password');
        echo $this->Form->input('temppassword', array(
            'type' => 'password',
            'label' => 'Confirm Password'
        ));
        echo $this->Form->input('UserDetail.first_name', array(
            'label' => 'First Name'
        ));
        echo $this->Form->input('UserDetail.last_name', array(
            'label' => 'Last Name',
        ));
        echo $this->Form->input('LemUser.customer_id', array(
            'label' => "Customer",
            'empty' => '-- UNASSIGNED --',
            'class' => 'span5',
        ));
        echo $this->Form->input('UserDetail.phone', array(
            'label' => 'Phone'
        ));
        echo $this->Form->input('UserDetail.fax', array(
            'label' => 'Fax'
        ));
        echo $this->Form->input('UserDetail.title', array(
            'label' => 'Title'
        ));
        echo $this->Form->input('role', array(
                'label' => __d('users', 'Role'), 'values' => $roles
        ));
        echo $this->Form->input('is_admin', array(
            'label' => 'Portal Administrator?'
        ));
        echo $this->Form->input('active', array(
            'label' => __d('users', 'Active')
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => 'Create', 'class' => 'btn')); ?>
</div>