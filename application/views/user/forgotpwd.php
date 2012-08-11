<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">	
	function validatepwdForm(){
    validator=$("#pwdformid").validate({
        rules: {
			"email":{
		    	required: true,
		    	email: true
			}
	    },
	    messages: {
			"email":{
		   		required:"<br>This field is required",
				email:"<br>Please enter an valid email address."
			}
	    }
	});
	x=validator.form();
	return x;
    }


</script>
<form name="pwdform" id="pwdformid" method="post" onsubmit="return validatepwdForm();">
<?php //print_r($_SESSION); ?>
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Forgot Password</b></td></tr>
	<?php if(isset($error)){?>
	<tr>
		<td colspan="2" class="error"><?php echo $error; ?></td>
	</tr>
	<?php }?>
	<tr>
		<td align="right" valign="top">Email:</td>
		<td><input type="text" name="email" />
	</tr>
	
	<tr>
		<td  colspan="2" align="right"><input type="submit" name="forgotpwd" value="Submit" /></td>
	</tr>
</table>
</form>