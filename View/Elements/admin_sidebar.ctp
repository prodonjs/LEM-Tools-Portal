<p class="label label-important">Administrative Functions</p>
<div id="admin-sidebar">
    <ul class="nav nav-tabs nav-stacked">
        <li class="dropdown">
            <?php
            /* Get unconfirmed users */
            $unconfirmedCount = $this->requestAction(array(
                'admin' => true,
                'controller' => 'lem_users',
                'action' => 'unconfirmed_count'
            ));
            $unconfirmedCount = empty($unconfirmedCount) ? 0 : $unconfirmedCount;
            ?>
            <a href="#users">Users <strong>(<?php echo $this->Number->format($unconfirmedCount); ?>)</strong><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <?php
                    echo $this->Html->link('Manage Users', array(
                        'admin' => true,
                        'controller' => 'lem_users',
                        'action' => 'index'
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('Add Users', array(
                        'admin' => true,
                        'controller' => 'lem_users',
                        'action' => 'add'
                    ));
                    ?>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#customers">Customers<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <?php
                    echo $this->Html->link('Manage Customers', array(
                        'admin' => true,
                        'controller' => 'customers',
                        'action' => 'index'
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('Add Customer', array(
                        'admin' => true,
                        'controller' => 'customers',
                        'action' => 'add'
                    ));
                    ?>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <a href="#products">Products<b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <?php
                    echo $this->Html->link('Manage Products', array(
                        'admin' => true,
                        'controller' => 'products',
                        'action' => 'index'
                    ));
                    ?>
                </li>
                <li>
                    <?php
                    echo $this->Html->link('Add Product', array(
                        'admin' => true,
                        'controller' => 'products',
                        'action' => 'add'
                    ));
                    ?>
                </li>
            </ul>
        </li>
        <li class="dropdown">
            <?php
            /* Get pending orders */
            $orderCount = $this->requestAction(array(
                'admin' => true,
                'controller' => 'orders',
                'action' => 'submitted_count'
            ));
            $orderCount = empty($orderCount) ? 0 : $orderCount;
            ?>
            <a href="#products">Orders <strong>(<?php echo $this->Number->format($orderCount); ?>)</strong><b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li>
                    <?php
                    echo $this->Html->link('Manage Orders', array(
                        'admin' => true,
                        'controller' => 'orders',
                        'action' => 'index'
                    ));
                    ?>
                </li>
            </ul>
        </li>
    </ul>
</div>
