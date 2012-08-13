<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">
 function validateUser(){
    validator=$("#userformid").validate({
        rules: {
			"user[ud_fname]":{
			required: true
		},
			"user[ud_email_id]": {
				required:true,
				email:true
			}
	    },
	    messages: {
			"user[ud_fname]":{
		    required:"<br>This field is required"
		},
			"user[ud_email_id]": {
				required:"<br>This field is required",
				email:"<br>Please enter an valid email address."
			}
	    }
	});
	x=validator.form();
	return x;
    }
    
	function EmailExist() {
		var email_id = $('#uemail_id').val();
		//alert(email_id)
		if(!email_id){
			return false;
		}
		var url="<?php echo HTTP_ROOT; ?>user/checkemail";
		$.post(url,{'isajax':1,"email_id":email_id},function(res){
			if(res == 1){
				$("#ajaxem").html("<br>This Email Already Exist.").show();
				$('#uemail_id').val("");
				return false;
			}else{
				$('#ajaxem').html('');
				return true;
			}
		});
	}

	function checkStatus(status,id){
		//alert(status+"----"+id);//return false;
		var url="<?php echo HTTP_ROOT; ?>user/updateStatus";
		$.post(url,{"status":status,"id":id},function(res){
			if(status){
				var shtml = "<a href='javascript:void(0);' onclick='return checkStatus(0,"+id+");'>In Active</a>";
			}else{
				var shtml = "<a href='javascript:void(0);' onclick='return checkStatus(1,"+id+");'>Active</a>";
			}
			$("#st_"+id).html(shtml);
		});
	}
	function resetpwd(name,email,id){
		var url="<?php echo HTTP_ROOT; ?>user/resetpwd";
		$("#loader").show();
		$.post(url,{"name":name,"email":email,id:id},function(res){
		$("#loader").hide();
		alert(res);return false;
			if(res == 1){
				$("#reset_msg").html("<br>password reset sucessfully.").show();
			}else{
				$('#reset_msg').html('');
			}
			
		});
	}
	
</script>
<form action="<?php echo HTTP_ROOT?>user/userlisting" name="userform" id="userformid" method="post" onsubmit="return validateUser();">
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Add Sub User</b></td></tr>
	<?php if(isset($error)){ ?>
	<tr>
		<td colspan="2">Either email or password is wrong.</td>
	</tr>
	<?php }?>
	<tr>
		<td align="right" valign="top">Name:</td>
		<td><input type="text" name="user[ud_fname]" id="ud_fname"/></td>
	</tr>
	<tr>
		<td align="right" valign="top">Email ID:</td>
		<td>
			<input type="text" name="user[ud_email_id]" id="uemail_id" onblur="return EmailExist();"/>
			<label id="ajaxem" class="error" for="uemail_id" generated="true" style="display: none;"><br>
				This field is required
			</label>
		</td>
	</tr>		
	<tr>
		<td></td>
		<td align="right"><input type="submit" name="usave" value="save" /></td>
	</tr>	
</table>
</form>
 <div class="header1 msg_color" align="center">
	</div>
<?php if(isset($ulisting) && $ulisting){	?>
<span id="reset_msg"></span>
	<center><b>User Listing</b></center>
	<div id="loader" style="display:none">Loading...</div>
	<table border='1px' align='center' id='tbl' width="700px">
		<thead>
			<tr><th>Name</th><th>Email id</th><th>Status</th><th>Action</th></tr>
		</thead>
		<?php foreach($ulisting as $k1=>$v1){ ?>
			<tr>
				<td><?php echo $v1['ud_fname']; ?></td>
				<td><?php echo $v1['ud_email_id']; ?></td>
				<td id="st_<?php echo $v1['ud_id']; ?>" align="center">
					<?php if($v1['ud_status']) { ?>
					<a href="javascript:void(0);" onclick="return checkStatus(<?php echo $v1['ud_status']?>,<?php echo $v1['ud_id'];?>);">Active</a>
					<?php }else{ ?>
					<a href="javascript:void(0);" onclick="return checkStatus(<?php echo $v1['ud_status']?>,<?php echo $v1['ud_id'];?>);">In Active</a>
					<?php }?>
					
				</td>
				<td align="center"><a href="<?php echo HTTP_ROOT?>user/deleteUser/<?php echo $v1['ud_id'] ?>" onclick="javascript:return confirm('Are u sure want to delete this record?')">Delete </a>||<a href="javascript:void(0);" onclick="return resetpwd('<?php echo $v1['ud_fname'];?>','<?php echo $v1['ud_email_id'];?>',<?php echo $v1['ud_id'];?>);">Reset Password</a></td>
			</tr>
		<?php } ?>
	</table>	
<?php }else{
	print "No records found.";
}?>
