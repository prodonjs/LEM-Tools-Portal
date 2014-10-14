<?php
$breadcrumbs = $this->Session->read('Breadcrumbs');
if(!empty($breadcrumbs)) :
?>
<ul class="breadcrumb">
    <?php
    end($breadcrumbs);
    $currentPage = key($breadcrumbs);
    foreach($breadcrumbs as $page => $url) :
        if($page === $currentPage) : ?>
    <li class="active">
        <?php echo h($page); ?>
    </li>
        <?php else: ?>
    <li>
        <a href="<?php echo $url; ?>">
            <?php echo h($page); ?>&nbsp;<span class="divider">&gt;</span>
        </a>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
