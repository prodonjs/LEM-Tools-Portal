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
<div class="skus index">
    <h2>SKU Administration</h2>
    <p>
        <h3><?php echo h($product['Product']['name']);?></h3>
        <?php echo h($product['Product']['description']);?>
    </p>
    <p>
        <?php echo $this->Html->link('Add SKU', array(
            'action' => 'add', $product['Product']['id']
        ), array('class' => 'btn btn-inverse'));
        ?>
    </p>
    <?php
    echo $this->element('paging');
    echo $this->element('pagination'); ?>
    <table class="table">
        <thead>
            <tr>
                <th>&nbsp;</th>
                <th><?php echo $this->Paginator->sort('part_no', 'Part No'); ?></th>
                <th><?php echo $this->Paginator->sort('alternative_part_no', 'Alt Part No'); ?></th>
                <th><?php echo $this->Paginator->sort('name'); ?></th>
                <th><?php echo $this->Paginator->sort('price'); ?></th>
                <th><?php echo $this->Paginator->sort('is_active', 'Active?'); ?></th>
                <th><?php echo $this->Paginator->sort('created'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($skus as $sku) { ?>
            <tr>
                <td>
                    <?php
                    echo $this->Html->link('Edit', array('admin' => true, 'action' => 'edit', $sku['Sku']['id']), array(
                        'class' => 'btn btn-mini'
                    ));
                    ?>
                </td>
                <td><?php echo h($sku['Sku']['part_no']); ?></td>
                <td><?php echo h($sku['Sku']['alternative_part_no']); ?></td>
                <td><?php echo h($sku['Sku']['name']); ?></td>
                <td><?php echo $this->Number->currency($sku['Sku']['price'], 'USD'); ?></td>
                <td>
                    <?php echo $sku['Sku']['is_active'] == 1 ? 'Yes' : 'No'; ?>
                </td>
                <td>
                    <?php echo date('n/j/Y', strtotime($sku['Sku']['created'])); ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php echo $this->element('pagination'); ?>
</div>
