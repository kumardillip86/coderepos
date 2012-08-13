<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">
 function validateLogin(){
    validator=$("#lformid").validate({
        rules: {
			"user[ud_password]":{
		    required: true,
		    minlength: 6
		},
			"user[ud_email_id]": {
				required:true,
				email:true
			}
	    },
	    messages: {
			"user[ud_password]":{
		    required:"<br>This field is required",
		    minlength:"<br>Please enter at least 6 characters."
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



</script>
<form action="<?php echo HTTP_ROOT?>user/login" name="lform" id="lformid" method="post" onsubmit="return validateLogin();">
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Login</b></td></tr>
	<?php if(isset($error)){ ?>
	<tr>
		<td colspan="2" style="color:red"><?php echo $error;?></td>
	</tr>
	<?php }?>
	<tr>
		<td align="right" valign="top">Email ID:</td>
		<td><input type="text" name="user[ud_email_id]" id="email_id"/></td>
	</tr>
	<tr>
		<td align="right" valign="top">Password:</td>
		<td><input type="password" name="user[ud_password]" id="password" /></td>
	</tr>
	<?php if(isset($sendpwd_link)){ ?>
	<tr>
		<td colspan="2"><a href="<?php echo $sendpwd_link; ?>">Click here </a>to send new password.</td>
	</tr>
	<?php }?>
	<tr>
		<td>
			New user?<a href="<?php echo HTTP_ROOT?>user/register">&nbsp;Register</a>	<br />	
			<a href="<?php echo HTTP_ROOT?>user/forgotpwd">Forgot Password ?</a>
		</td>
		<td align="right"><input type="submit" name="login" value="Login" /></td>
	</tr>	
</table>
</form>