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
    <h2>User Administration</h2>
    <?php
    echo $this->Form->create($model, array('action' => 'index'));
    echo $this->Form->input('username', array('label' => __d('users', 'Username')));
    echo $this->Form->input('email', array('label' => __d('users', 'Email'), 'type' => 'text'));
    echo $this->Form->input('unconfirmed', array(
        'type' => 'checkbox',
        'hiddenField' => false,
        'label' => 'Unconfirmed?'
    ));
    $this->Form->unlockField('LemUser.unconfirmed');
    echo $this->Form->end(array('label' => 'Search', 'class' => 'btn'));
    ?>
    <p>
        <?php echo $this->Html->link('Add User', array('action' => 'add'), array('class' => 'btn btn-inverse')); ?>
    </p>
    <?php echo $this->element('paging'); ?>
    <?php echo $this->element('pagination'); ?>
    <table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $this->Paginator->sort('username'); ?></th>
                <th><?php echo $this->Paginator->sort('email'); ?></th>
                <th><?php echo $this->Paginator->sort('Customer.name', 'Customer'); ?></th>
                <th><?php echo $this->Paginator->sort('email_verified', 'Confirmed?'); ?></th>
                <th><?php echo $this->Paginator->sort('active', 'Active?'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
                <th><?php echo $this->Paginator->sort('role'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user) { ?>
            <tr>
                <td>
                    <?php
                    echo $this->Html->link('Edit', array('admin' => true, 'action' => 'edit', $user[$model]['id']), array(
                        'class' => 'btn btn-mini'
                    ));
                    ?>
                </td>
                <td>
                    <?php echo h($user[$model]['username']); ?>
                </td>
                <td>
                    <?php echo h($user[$model]['email']); ?>
                </td>
                <td>
                    <?php if(!is_null($user[$model]['customer_id'])) :
                        echo h("{$user['Customer']['account_no']} | {$user['Customer']['name']}");
                    ?>
                    <?php else : ?>
                    <span class="label label-important">Unassigned</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($user[$model]['email_verified']) : ?>
                    Yes
                    <?php else : ?>
                    <span class="label label-important">No</span>
                        <?php
                        /* Allow sending of confirmation e-mail */
                        if(!is_null($user['Customer']['id'])) :
                            $tokenExpires = is_null($user[$model]['email_token_expires']) ? '1970-01-01' : $user[$model]['email_token_expires'];
                            if(time() < strtotime($tokenExpires)) :
                                echo '<br><br><span class="label label-warning">Waiting for user</span>';
                            else:
                                echo '<br><br>' . $this->Html->link('Confirm', array(
                                    'admin' => true,
                                    'action' => 'confirm', $user[$model]['id']), array(
                                        'class' => 'btn btn-mini'
                                ));
                            endif;
                        endif;
                        ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $user[$model]['active'] == 1 ? __d('users', 'Yes') : __d('users', 'No'); ?>
                </td>
                <td>
                    <?php echo date('n/j/Y', strtotime($user[$model]['created'])); ?>
                </td>
                <td>
                    <?php
                        $roles = Configure::read('Users.roles');
                        echo $roles[$user[$model]['role']];
                    ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination'); ?>
</div>
