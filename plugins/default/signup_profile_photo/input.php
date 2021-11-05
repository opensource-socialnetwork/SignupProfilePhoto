<script>
	$(document).ready(function(){
			var image = '<div> <label><?php echo ossn_print('singupprofilephoto');?></label> <input type="file" name="profilephoto" class="form-control" /> <div class="margin-top-10"></div> </div>';		
			$(image).insertBefore('#ossn-home-signup #ossn-signup-errors');
	});
</script>