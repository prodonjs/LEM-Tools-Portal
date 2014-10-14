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
<div class="customers index">
    <h2>Customer Administration</h2>
    <?php
    echo $this->Form->create('Customer', array('action' => 'index'));
    echo $this->Form->input('name', array('label' => 'Name'));
    echo $this->Form->input('account_no', array('label' => 'Account Number'));
    echo $this->Form->end(array('label' => 'Search', 'class' => 'btn'));
    ?>
    <p>
        <?php echo $this->Html->link('Add Customer', array('action' => 'add'), array('class' => 'btn btn-inverse')); ?>
    </p>
    <?php
    echo $this->element('paging');
    echo $this->element('pagination'); ?>
    <table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('alternative_name'); ?></th>
                <th><?php echo $this->Paginator->sort('account_no', 'Account No'); ?></th>
                <th><?php echo $this->Paginator->sort('is_active', 'Active?'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
                <th><?php echo $this->Paginator->sort('last_transaction'); ?></th>
                <th>User(s)</th>
                <th>Orders</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($customers as $customer) { ?>
            <tr>
                <td>
                    <?php
                    echo $this->Html->link('Edit', array('admin' => true, 'action' => 'edit', $customer['Customer']['id']), array(
                        'class' => 'btn btn-mini'
                    ));
                    ?>
                </td>
                <td>
                    <?php echo h($customer['Customer']['name']); ?>
                </td>
                <td>
                    <?php echo h($customer['Customer']['alternative_name']); ?>
                </td>
                <td>
                    <?php echo h($customer['Customer']['account_no']); ?>
                </td>
                <td>
                    <?php echo $customer['Customer']['is_active'] == 1 ? 'Yes' : 'No'; ?>
                </td>
                <td>
                    <?php echo date('n/j/Y', strtotime($customer['Customer']['created'])); ?>
                </td>
                <td>
                    <?php
                    if(!is_null($customer['Customer']['last_transaction'])) {
                        echo date('n/j/Y', strtotime($customer['Customer']['last_transaction']));
                    }
                    ?>
                </td>
                <td><?php echo implode(', ', Hash::extract($customer['User'], '{n}.username'));?> </td>
                <td><?php echo $this->Number->format(count($customer['Order'])); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination'); ?>
</div>
