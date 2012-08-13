<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">
 function validate(){
    validator=$("#formid").validate({
        rules: {
			"user[ud_fname]": {
				required:true
			},
			"user[ud_lname]": {
				required:true
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
			"user[ud_lname]":{
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


function showEmailExist() {
	var email_id = $('#email_id').val();
	//alert(email_id)
	if(!email_id){
		return false;
	}
    var url="<?php echo HTTP_ROOT; ?>user/checkemail";
	$.post(url,{'isajax':1,"email_id":email_id},function(res){
		if(res == 1){
			$("#ajaxel").html("<br>This Email Already Exist.").show();
			$('#email_id').val("");
			return false;
		}else{
			$('#ajaxel').html('');
			return true;
		}
	});
}
</script>
</head>
<form name="form" id="formid" method="post" onsubmit="return validate();">
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Register</b></td></tr>
	<tr>
		<td align="right" valign="top">First Name:</td>
		<td><input type="text" name="user[ud_fname]" id="uname" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Middle Name:</td>
		<td><input type="text" name="user[ud_mname_initial]" id="mname" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Last Name:</td>
		<td><input type="text" name="user[ud_lname]" id="lname" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Email ID:</td>
		<td>
			<input type="text" name="user[ud_email_id]" id="email_id"   onblur="return showEmailExist();" />
			<label id="ajaxel" class="error" for="email_id" generated="true" style="display: none;"><br>
				This field is required
			</label>
		</td>
	</tr>
	<tr>
		<td>Already user?&nbsp;<a href="<?php echo HTTP_ROOT?>user/login">login</a></td>
		<td align="right"><input type="submit" name="register" value="Register" /></td>
	</tr>
</table>
</form>