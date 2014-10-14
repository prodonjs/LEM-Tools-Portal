<div class="users form">
	<h2>Register for the LEM Tools Customer Portal</h2>
    <p>
        Please complete the form below. Once reviewed by our sales department, you will be given
        instructions on how to activate your login and begin using the LEM Tools portal to purchase from us.
        Thank you for your business!
    </p>
    <?php
    echo $this->Form->create($model, array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    ));
    echo $this->Form->input('UserDetail.first_name', array(
        'label' => 'First Name',
        'placeholder' => 'First Name'
    ));
    echo $this->Form->input('UserDetail.last_name', array(
        'label' => 'Last Name',
        'placeholder' => 'Last Name'
    ));
    echo $this->Form->input('UserDetail.customer', array(
        'label' => 'LEM Account Number or Business Name (if applicable)',
        'placeholder' => 'Customer Information'
    ));
    echo $this->Form->input('UserDetail.phone', array(
        'label' => 'Phone',
        'placeholder' => 'Phone'
    ));
    echo $this->Form->input('UserDetail.fax', array(
        'label' => 'Fax',
        'placeholder' => 'Fax'
    ));
    echo $this->Form->input('UserDetail.title', array(
        'label' => 'Title',
        'placeholder' => 'Title'
    ));
    echo $this->Form->input('username', array(
        'label' => 'Username',
        'placeholder' => 'Username'
    ));
    echo $this->Form->input('email', array(
        'label' => 'E-mail Address',
        'placeholder' => 'E-mail Address'
    ));
    echo $this->Form->input('password', array(
        'label' => 'Password',
        'placeholder' => 'Password',
        'type' => 'password'
    ));
    echo $this->Form->input('temppassword', array(
        'label' => 'Confirm Password',
        'placeholder' => 'Confirm Password',
        'type' => 'password'
    ));
    $tosLink = $this->Html->link('Terms of Service', array('controller' => 'pages', 'action' => 'tos', 'plugin' => null));
    echo $this->Form->input('tos', array(
        'label' => "I have read and agreed to {$tosLink}"
    ));
    echo $this->Form->end(array('label' => 'Register', 'class' => 'btn'));
    ?>
</div>
