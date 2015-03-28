<? @require_once('templates/_header.php');?>
<?php 
function createColumnsArray($end_column, $first_letters = '')
{
  $columns = array();
  $length = strlen($end_column);
  $letters = range('A', 'Z');

  // Iterate over 26 letters.
  foreach ($letters as $letter) {
      // Paste the $first_letters before the next.
      $column = $first_letters . $letter;

      // Add the column to the final array.
      $columns[] = $column;

      // If it was the end column that was added, return the columns.
      if ($column == $end_column)
          return $columns;
  }

  // Add the column children.
  foreach ($columns as $column) {
      // Don't itterate if the $end_column was already set in a previous itteration.
      // Stop iterating if you've reached the maximum character length.
      if (!in_array($end_column, $columns) && strlen($column) < $length) {
          $new_columns = createColumnsArray($end_column, $column);
          // Merge the new columns which were created with the final columns array.
          $columns = array_merge($columns, $new_columns);
      }
  }

  return $columns;
}
?>

<br><br>
<article class="container">
	<section class="row">
		<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
			<div class="col-md-6 form-group">
		    <label class="col-sm-2 control-label">檔案位置</label>
		    <div class="col-sm-9">
	        <input class="form-control" type="file" name="userfile" />
		    </div>
		    <div class="clearfix"></div><br>
		    <label class="col-sm-2 control-label">比對欄位</label>
		    <div class="col-sm-9">
					<select class="form-control" name="compareX">
						<?php foreach (createColumnsArray('ZZ') as $char): ?>
						<option value="<?= $char?>"><?= $char?></option>	
						<?php endforeach ?>
					</select>
		    </div>
		    <div class="clearfix"></div><br>
		    <label class="col-sm-2 control-label">被寫入欄</label>
		    <div class="col-sm-9">
					<select class="form-control" name="writerX">
						<?php foreach (createColumnsArray('ZZ') as $char): ?>
						<option value="<?= $char?>"><?= $char?></option>	
						<?php endforeach ?>
					</select>
		    </div>
		  </div>	
			<div class="col-md-6 form-group">
		    <label class="col-sm-2 control-label">檔案位置</label>
		    <div class="col-sm-9">
	        <input class="form-control" type="file" name="userfile1" />
		    </div>
		    <div class="clearfix"></div><br>
		    <label class="col-sm-2 control-label">比對欄位</label>
		    <div class="col-sm-9">
					<select class="form-control" name="compareY">
						<?php foreach (createColumnsArray('ZZ') as $char): ?>
						<option value="<?= $char?>"><?= $char?></option>	
						<?php endforeach ?>
					</select>
		    </div>
		    <div class="clearfix"></div><br>
		    <label class="col-sm-2 control-label">要寫入欄</label>
		    <div class="col-sm-9">
					<select class="form-control" name="writerY">
						<?php foreach (createColumnsArray('ZZ') as $char): ?>
						<option value="<?= $char?>"><?= $char?></option>	
						<?php endforeach ?>
					</select>
		    </div>
		    <div class="clearfix"></div><br>
		<!--     <label class="col-sm-2 control-label">要繪圖欄</label>
		    <div class="col-sm-9">
					<select class="form-control" name="graphic">
						<?php foreach (createColumnsArray('ZZ') as $char): ?>
						<option value="<?= $char?>"><?= $char?></option>	
						<?php endforeach ?>
					</select>
		    </div> -->
		  </div>	

			<div class="clearfix"></div>
		  <div class="form-group">
		    <div class="text-center">
		    	<span class="glyphicon glyphicon-arrow-left" style="font-size: 50px;"></span><br>
		      <button type="submit" class="btn btn-primary btn-lg">送出</button>
		      <a href="<?= base_url('welcome/clear')?>" role="btn" class="btn btn-warning btn-lg">清除檔案</a>
		      <a href="<?= base_url('welcome/graphic')?>" class="btn btn-success btn-lg">繪圖</a>
		    </div>
		  </div>
		</form>
	</section>
	<hr>
</article>

<? @require_once('templates/_footer.php');?>