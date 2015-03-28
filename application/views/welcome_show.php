<? @require_once('templates/_header.php');?>
<script type="text/javascript" src="<?= base_url('asset/js/chart.js')?>"></script>
<div style="width:30%">
	<div>
		<canvas id="canvas" height="100%" width="100%"></canvas>
	</div>
</div>
<pre>
<?php
var_dump($query);
?>
</pre>

<script>
var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
var lineChartData = {
	labels : ["first","second","third","forth","fifth"],
	datasets : [
	<?php foreach ($query as $key => $value): ?>
	<?php echo "{ label:'" . $value->pointId . "',"; ?>
			fillColor : "rgba(220,220,220,0.2)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			data : [<?php $value->first?>,
							<?php $value->second?>,
							<?php $value->third?>,
							<?php $value->forth?>,
							<?php $value->fifth?>]},

	<?php endforeach ?>
		{
			label: "My First dataset",
			fillColor : "rgba(220,220,220,0.2)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			data : [randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor(),
							randomScalingFactor()]
		}
	]

}

window.onload = function(){
var ctx = document.getElementById("canvas").getContext("2d");
window.myLine = new Chart(ctx).Line(lineChartData, {
	responsive: true
});
}


</script>
<? @require_once('templates/_footer.php');?>