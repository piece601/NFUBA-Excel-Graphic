<? @require_once('templates/_header.php');?>

<div class="alert alert-warning"><h3><?echo $message;?></h3></div> 
<p class="text-info text-center">三秒回前頁</p>
<script>setTimeout("history.go(-1)",3000)</script>

<? @require_once('templates/_footer.php');?>