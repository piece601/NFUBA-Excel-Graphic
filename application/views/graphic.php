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


<section class="container">
	<form action="" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<label>選擇要繪圖的檔案</label>
			<input type="file" name="userfile" class="form-control">
		</div>
		<div class="form-group">
			<label for="search">Scan 的欄位</label>
			<select name="search" id="">
				<?php foreach (createColumnsArray('ZZ') as $char): ?>
				<option value="<?= $char?>"><?= $char?></option>	
				<?php endforeach ?>
			</select>
		</div>

		<div class="form-group">
			<label for="graphicField[]">Graphic 的欄位</label>
			<select name="graphicField[]">
				<?php foreach (createColumnsArray('ZZ') as $char): ?>
				<option value="<?= $char?>"><?= $char?></option>	
				<?php endforeach ?>
			</select>
		</div>

		<div id="graphic">
			
		</div>

    <div class="form-group">
	    <label class="col-sm-2 control-label"></label>
	    <div class="col-sm-9">
	      <a class="btn btn-info btn-xs" id="add_uploadfield_btn" role="button"><span class="glyphicon glyphicon-plus"></span></a>
	    </div>
	  </div>


		<div class="form-group">
			<label for=""></label>
			<input type="submit" class="btn btn-primary" value="送出">
		</div>
	</form>
</section>


<script>
  //set the default value
  var txtId = 1;
  
  //add input block in showBlock
  $("#add_uploadfield_btn").click(function () {
  		$("#graphic").append(	'<div class="form-group" id="div'+ txtId +'">'
  													+'<label for="graphicField[]" class="animated bounceIn" id="div'
  													+ txtId +'">Graphic 的欄位</label>'
  													+ '<select name="graphicField[]" class="animated bounceIn" id="div'
  													+ txtId +'">'
  													+ '<?php foreach (createColumnsArray("ZZ") as $char): ?>'
  													+ '<option value="<?= $char?>"><?= $char?></option>'
  													+ '<?php endforeach ?>'
  													+	'</select>'
  													+ '<input type="button" class="btn btn-danger btn-xs" onclick="del_uploadfield_btn('+txtId+')" value="X" />'
  													+ '</div>');
      $("#userfile").append('<ul class="list-inline animated bounceIn" id="div'
      											+ txtId
      											+'"><li><input type="file" class="form-control" name="userfile' 
      											+ txtId 
      											+ '" />'
      											+'</li> <li><input type="button" class="btn btn-danger btn-xs" onclick="del_uploadfield_btn('+txtId+')" value="X" /></li></ul>');
      txtId++;
  });
 
  //remove div
  function del_uploadfield_btn(id) {
      $("#div"+id).addClass('animated bounceOutLeft')
      						.delay(500)
      						.queue(function(){
      							$(this).remove();
      							$(this).dequeue();
      						});
  }
</script> 

<? @require_once('templates/_footer.php');?>