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
<div class="users form">
<h2>Forgot your password?</h2>
<p>
    Please enter the email you used for registration and you'll get an email with further instructions.
</p>
<?php
    echo $this->Form->create($model, array(
        'url' => array(
            'admin' => false,
            'action' => 'reset_password')));
    echo $this->Form->input('email', array(
        'label' => __d('users', 'Your Email Address')));
    echo $this->Form->end(array('label' => 'Register', 'class' => 'btn'));
?>
</div>