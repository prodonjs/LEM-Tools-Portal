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
    <?php echo $this->Form->create($model, array(
        'inputDefaults' => array(
            'error' => array('attributes' => array('class' => 'alert alert-error'))
        )
    )); ?>
        <fieldset>
            <legend>Edit Your Profile</legend>
            <?php
                echo $this->Form->input('UserDetail.first_name', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['first_name']) ?
                        h($this->request->data['UserDetail']['LemUser']['first_name']) : ''
                ));
                echo $this->Form->input('UserDetail.last_name', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['last_name']) ?
                        h($this->request->data['UserDetail']['LemUser']['last_name']) : ''
                ));
                echo $this->Form->input('UserDetail.company', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['company']) ?
                        h($this->request->data['UserDetail']['LemUser']['company']) : ''
                ));
                echo $this->Form->input('UserDetail.phone', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['phone']) ?
                        h($this->request->data['UserDetail']['LemUser']['phone']) : ''
                ));
                echo $this->Form->input('UserDetail.fax', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['fax']) ?
                        h($this->request->data['UserDetail']['LemUser']['fax']) : ''
                ));
                echo $this->Form->input('UserDetail.title', array(
                    'value' => isset($this->request->data['UserDetail']['LemUser']['title']) ?
                        h($this->request->data['UserDetail']['LemUser']['title']) : ''
                ));
            ?>
            <p>
                <?php echo $this->Html->link(__d('users', 'Change your password'), array(
                    'action' => 'change_password'), array('class' => 'btn'));
                ?>
            </p>
        </fieldset>
    <?php echo $this->Form->end(array('label' => 'Save Changes', 'class' => 'btn')); ?>
</div>