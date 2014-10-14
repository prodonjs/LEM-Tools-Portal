<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="users index">
    <?php echo $this->Session->flash('auth', array('element' => 'flash_alert')); ?>
	<h2>Login to the LEM Tools Customer Portal</h2>
	<fieldset>
		<?php
			echo $this->Form->create($model, array(
				'action' => 'login',
				'id' => 'LoginForm'));
			echo $this->Form->input('username', array(
				'label' => __d('users', 'Username or Email Address'),
                'placeholder' => 'Username',
                'type' => 'text'
            ));
			echo $this->Form->input('password',  array(
				'label' => __d('users', 'Password'),
                'placeholder' => 'Password'
            ));
			echo '<p>' . $this->Form->input('remember_me', array('type' => 'checkbox', 'label' =>  __d('users', 'Remember Me'))) . '</p>';
			echo '<p>' . $this->Html->link(__d('users', 'I forgot my password'), array('action' => 'reset_password')) . '</p>';

			echo $this->Form->hidden('User.return_to', array(
				'value' => $return_to));
			echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-large'));
		?>
	</fieldset>
</div>