<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>LEM Tools :: <?php echo $title_for_layout; ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <?php
    echo $this->Html->css('bootstrap.min');
    echo $this->Html->css('lem');
    echo $this->Html->meta('icon');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    ?>
</head>
<body>
    <div class="container">
        <?php echo $this->element('navbar'); ?>
        <div class="row">
            <div id="sidebar" class="span2">
                <?php echo $this->element('sidebar'); ?>
                <?php if($this->Session->read('Auth.User.is_admin')) {
                    echo $this->element('admin_sidebar');
                }
                ?>
            </div>
            <div id="content" class="span10">
                <?php
                echo $this->element('breadcrumbs');
                echo $this->Session->flash('flash');
                echo $this->fetch('content');
                ?>
            </div>
        </div>
        <footer id="footer">
            <?php echo $this->element('sql_dump'); ?>
        </footer>
    </div>

    <!-- JS Includes -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <?
    echo $this->Html->scriptBlock('window.jQuery || document.write(\'' . str_replace('</script>', '<\/script>', $this->Html->script('vendor/jquery-1.9.1.min')) . '\');');
    echo $this->Html->script('vendor/modernizr-2.6.2.min');
    echo $this->Html->script('vendor/bootstrap.min');
    echo $this->fetch('script');
    echo $this->Js->writeBuffer(array('cache' => true));
    ?>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.
    <script>
        var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
        (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
        g.src='//www.google-analytics.com/ga.js';
        s.parentNode.insertBefore(g,s)}(document,'script'));
    </script>
    -->
</body>
</html>
