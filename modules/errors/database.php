<?php 
if(!defined('_INCODE')) die('access delined......');
?>
<div class="" style="margin: 0 auto; width: 800px; text-align: center;">
    <h2 style="text-transform: uppercase">Lối kết nối CSDL</h2>
    <hr>
    <p><?php echo $exception->getMessage(); ?></p>
    <p>File: <?php echo $exception->getFile(); ?></p>
    <p>Line: <?php echo $exception->getLine(); ?></p>
</div>