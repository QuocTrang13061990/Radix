<?php 
if(!defined('_INCODE')) die('access delined......');
?>
<!-- File này chứa tất cả các lỗi (Trừ 404: lỗi đường dẫn) -->
<div class="" style="margin: 0 auto; width: 800px; text-align: center;">
    <h2 style="text-transform: uppercase">Vui lòng kiểm tra và xử lý các lỗi sau</h2>
    <hr>
    <p><?php echo $exception->getMessage(); ?></p>
    <p>File: <?php echo $exception->getFile(); ?></p>
    <p>Line: <?php echo $exception->getLine(); ?></p>
</div>
