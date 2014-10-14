<div id="user-sidebar">    
	<ul class="nav nav-tabs nav-stacked">        
		<?php
        /* Not Logged In */
        if (!$this->Session->read('Auth.User.id')) { ?>
            <li><?php echo $this->Html->link('Register', '/register'); ?></li>
			<li><?php echo $this->Html->link('Login', '/login'); ?></li>
        <?php }
        /* Logged In */
		else { ?>			
			<li><?php echo $this->Html->link('My Profile', array(
                'admin' => false,
                'controller' => 'users', 
                'action' => 'edit', $this->Session->read('Auth.User.id'))
            ); ?>
			<li><?php echo $this->Html->link('Change Password', array('admin' => false, 'controller' => 'users', 'action' => 'change_password')); ?>
            <li><?php echo $this->Html->link('Logout', '/logout'); ?>
		<?php } ?>		
	</ul>
</div>
