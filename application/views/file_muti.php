<? @require_once('templates/_header.php');?>

<article class="container">
	<section class="row">

		<form class="form-horizontal" role="form" method="post" action="" enctype="multipart/form-data">
		  <div class="form-group">
		    <label class="col-sm-2 control-label">檔案位置</label>
		    <div class="col-sm-9" id="userfile">
		    	<ul class="list-inline">
		    		<li><input class="form-control" type="file" name="userfile"></li>
		    	</ul>
		    </div>
		  </div>

	    <div class="form-group">
		    <label class="col-sm-2 control-label"></label>
		    <div class="col-sm-9">
		      <a class="btn btn-info btn-xs" id="add_uploadfield_btn" role="button"><span class="glyphicon glyphicon-plus"></span></a>
		    </div>
		  </div>
			      

		  <div class="form-group">
		    <div class="col-sm-offset-2 col-sm-10">
		      <button type="submit" class="btn btn-primary btn-lg">送出</button>
		    </div>
		  </div>
		</form>

	</section>
</article>


<script>
  //set the default value
  var txtId = 1;
  
  //add input block in showBlock
  $("#add_uploadfield_btn").click(function () {
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

<? @require_once('templates/_footer.php');?>