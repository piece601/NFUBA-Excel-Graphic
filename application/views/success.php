<? @require_once('templates/_header.php');?>

<div class="alert alert-success text-center"><h3><?echo $message;?></h3></div> 
<p class="text-info text-center">三秒回前頁</p>
<script>setTimeout("location.href='<?= base_url($redirectUrl)?>'",3000)</script>

<? @require_once('templates/_footer.php');?>