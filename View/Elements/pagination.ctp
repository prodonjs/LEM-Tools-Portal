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
<div class="pagination pagination-small">
    <ul>
        <li><?php echo $this->Paginator->prev('<<', array(), null, array('class' => 'prev disabled')); ?></li>
        <?php
        echo $this->Paginator->numbers(array(
            'first' => 'First Page',
            'separator' => '',
            'tag' => 'li',
            'last' => 'Last Page',
            'ellipsis' => '<li><span>...</span><li>',
            'currentClass' => 'active',
            'currentTag' => 'a'
        ));
        ?>
        <li><?php echo $this->Paginator->next('>>', array(), null, array('class' => 'next disabled')); ?></li>

    </ul>
</div>
