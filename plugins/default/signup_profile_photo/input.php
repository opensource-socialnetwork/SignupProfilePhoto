<?php
		$max_upload   = (int) (ini_get('upload_max_filesize'));
		$max_post     = (int) (ini_get('post_max_size'));
		$memory_limit = min($max_upload, $max_post);

?>
<script>
	$(document).ready(function(){
			var image = '<div> <label><?php echo ossn_print('singupprofilephoto', array($memory_limit));?></label> <input type="file" name="profilephoto" class="form-control" accept="image/*"/> <div class="margin-top-10"></div> </div>';		
			$(image).insertBefore('#ossn-home-signup #ossn-signup-errors');
	});
</script>
