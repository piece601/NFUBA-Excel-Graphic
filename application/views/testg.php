<?php require_once 'templates/_header.php' ?>
<script src="<?=base_url('asset/js/Chart.js')?>"></script>
<?php $counter = 0;?>
<?php foreach ($query as $key => $value): ?>
	<?php if ( $counter != $value->pointId ): ?>
		<canvas id="myChart<?= $value->pointId ?>" width="1024" height="700"></canvas>		
		<br>
	<?php endif ?>
<?php endforeach ?>
<script>
window.onload = function(){
<?php $counter = 0;?>
<?php foreach ($query as $key => $value1): ?>
	<?php if ( $counter == $value1->pointId ) {continue;}; // 如果計算次數跟上一個次數相等?>
	<?php $counter = $value1->pointId ?>
	var ctx = document.getElementById("myChart<?=$value1->pointId?>").getContext("2d");
	var data = {
		labels : ["first","second","third","forth","fifth"],
		datasets : [
		<?php foreach ($query as $key => $value): ?>
		<?php if ($value->pointId != $value1->pointId) {continue;};?>
			{
				<?php $color = array(rand(0, 255), rand(0, 255), rand(0, 255))?>
				label: "Point: <?= $value->pointId?> - Field: <?= $value->fieldName?>",	
				fillColor : "rgba(220,220,220,0)",
				strokeColor : "rgba(<?= $color[0]?>, <?= $color[1]?>, <?= $color[2]?>, 1)",
				pointColor : "rgba(<?= $color[0]?>, <?= $color[1]?>, <?= $color[2]?>, 1)",
        pointHighlightStroke: "rgba(220,220,220,1)",
				data : [<?=$value->first?>,
								<?=$value->second?>,
								<?=$value->third?>,
								<?=$value->forth?>,
								<?=$value->fifth?>],
			},

		<?php endforeach ?>
		]
	}
	new Chart(ctx).Line(data, {
		 animation: false,
	   // responsive : true,
	   multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
	});
<?php endforeach ?>
}
	
</script>

<?php require_once 'templates/_footer.php' ?>
