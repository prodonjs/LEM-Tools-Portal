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
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title>LEM Tools Portal E-mail System</title>
    <style type="text/css">
        html{border:0;margin:0;padding:0;}body,div,span,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,code,del,dfn,em,img,q,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,dialog,figure,footer,header,hgroup,nav,section{border:0;font-size:100%;font:inherit;vertical-align:baseline;margin:0;padding:0;}article,aside,details,figcaption,figure,dialog,footer,header,hgroup,menu,nav,section,small{display:block;}table{border-collapse:separate;border-spacing:0;}caption,th,td{text-align:left;font-weight:400;float:none!important;}table,th,td{vertical-align:middle;}blockquote:before,blockquote:after,q:before,q:after{content:'';}:focus{outline:0;}body{line-height:1.5;font-family:"Calibri","Tahoma","Arial","sans-serif";color:#000;background:0;font-size:10pt;margin:20px 0 0 20px;}.container{background:0;}hr{background:#ccc;color:#ccc;width:100%;height:2px;border:0;margin:2em 0;padding:0;}hr.space{background:#fff;color:#fff;visibility:hidden;}h1,h2,h3,h4,h5,h6{font-family:"Helvetica Neue",Arial,"Lucida Grande",sans-serif;}code{font:.9em "Courier New",Monaco,Courier,monospace;}h3{font-weight:700;font-size:1.6em;margin:1em 0 .25em;}a img{border:0;}p img.top{margin-top:0;}blockquote{font-style:italic;font-size:.9em;margin:1.5em;padding:1em;}.small{font-size:.9em;}.large{font-size:1.1em;}.quiet{color:#999;}.hide{display:none;}a:link,a:visited{background:transparent;font-weight:700;text-decoration:underline;}th,td{text-align:left;padding:4px;}th{border-bottom:2px solid #333;border-right:1px solid #333;background-color:lightgray;}td{border-bottom:1px dotted #999;}tfoot td{border-bottom-width:0;border-top:2px solid #333;padding-top:20px;}img{-webkit-border-radius:6px;-moz-border-radius:6px;border-radius:6px;background-color:#fff;border:1px solid rgba(0,0,0,0.2);-webkit-box-shadow:0 1px 3px rgba(0,0,0,0.1);-moz-box-shadow:0 1px 3px rgba(0,0,0,0.1);box-shadow:0 1px 3px rgba(0,0,0,0.1);padding:4px;}p{margin-bottom: 1.5em;}
    </style>
</head>
<body>
	<?php echo $this->fetch('content');?>
	<p style="border-top: 1px solid #000;margin-top: 10px;font-size: 10px;">
        This email was sent from the <a href="http://www.lemtools.com">LEM Tools Customer Portal</a><br>
        Contact our customer service team if you have any questions.
    </p>
</body>
</html>