<div class="users form">
	<?php echo $this->Form->create($model, array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    ));
    ?>
    <fieldset>
        <legend>Edit User</legend>
        <?php
        echo $this->Html->link('Reset Password', array(
            'action' => 'reset_password',
            $this->request->data['LemUser']['id']),
            array('class' => 'btn')
        );
        $this->Form->unlockField('LemUser.customer_id');
        echo $this->Form->input('id');
        echo $this->Form->input('username', array(
            'label' => __d('users', 'Username')));
        echo $this->Form->input('email', array(
            'label' => __d('users', 'E-mail')));
        echo $this->Form->input('UserDetail.first_name', array(
            'label' => 'First Name',
            'value' => isset($this->request->data['UserDetail']['LemUser']['first_name']) ?
                h($this->request->data['UserDetail']['LemUser']['first_name']) : ''
        ));
        echo $this->Form->input('UserDetail.last_name', array(
            'label' => 'Last Name',
            'value' => isset($this->request->data['UserDetail']['LemUser']['last_name']) ?
                h($this->request->data['UserDetail']['LemUser']['last_name']) : ''
        ));

        $customerLabel = '<span class="label label-important">Unassigned</span>';
        if(isset($this->request->data['UserDetail']['LemUser']['customer'])) {
            $customer = h($this->request->data['UserDetail']['LemUser']['customer']);
        }
        echo $this->Form->input('LemUser.customer_id', array(
            'label' => "Customer",
            'empty' => '-- UNASSIGNED --',
            'class' => 'span5',
        ));
        echo $this->Form->input('UserDetail.phone', array(
            'label' => 'Phone',
            'value' => isset($this->request->data['UserDetail']['LemUser']['phone']) ?
                h($this->request->data['UserDetail']['LemUser']['phone']) : ''
        ));
        echo $this->Form->input('UserDetail.fax', array(
            'label' => 'Fax',
            'value' => isset($this->request->data['UserDetail']['LemUser']['fax']) ?
                h($this->request->data['UserDetail']['LemUser']['fax']) : ''
        ));
        echo $this->Form->input('UserDetail.title', array(
            'label' => 'Title',
            'value' => isset($this->request->data['UserDetail']['LemUser']['title']) ?
                h($this->request->data['UserDetail']['LemUser']['title']) : ''
        ));
        if (!empty($roles)) {
            echo $this->Form->input('role', array(
                'label' => __d('users', 'Role'), 'values' => $roles));
        }
        echo $this->Form->input('is_admin', array(
            'label' => 'Portal Administrator?'
        ));
        echo $this->Form->input('active', array(
            'label' => __d('users', 'Active')));
        ?>
    </fieldset>
    <?php echo $this->Form->end(array('label' => 'Save', 'class' => 'btn')); ?>
</div>
