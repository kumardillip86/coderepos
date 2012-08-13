<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">	
	function validatepwdForm(){
    validator=$("#pwdformid").validate({
        rules: {
			"user[password]":{
		    required: true,
		    minlength: 6
		},
			 "cpwd":{
		    required: true,
		    equalTo:'#password'
			}
	    },
	    messages: {
			"user[password]":{
		    required:"<br>This field is required",
		    minlength:"<br>Please enter at least 6 characters."
		},
			"cpwd":{
		    required:"<br>This field is required",
		    equalTo: '<br>Please Enter the same value again'
			}
	    }
	});
	x=validator.form();
	return x;
    }


</script>
<form name="pwdform" id="pwdformid" method="post" onsubmit="return validatepwdForm();">
<?php if(isset($el) && $el){?>
<input type="hidden" name="elid" id="hid" value="<?php echo $el; ?>" />
<?php }else{ ?>
<input type="hidden" name="hid" id="hid" value="<?php echo $_SESSION['ud_id']; ?>" />
<?php }?>
<?php //print_r($_SESSION); ?>
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Change Password</b></td></tr>
	<tr>
		<td align="right" valign="top">New password:</td>
		<td><input type="password" name="user[password]" id="password" />
		<?php if(isset($error['blank'])){ ?>
		<label class="error" for="password" generated="true"><br>
			This field is required.
		</label>
		<?php }?>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">Confirm new password:</td>
		<td><input type="password" name="cpwd" id="cpwd"/>
		<?php if(isset($error['blank'])){ ?>
		<label class="error" for="password" generated="true"><br>
			This field is required.
		</label>
		<?php }?>
		
		<?php if(isset($error['mismatch'])){ ?>
		<label class="error" for="password" generated="true"><br>
			Confirm password mismatch.
		</label>
		<?php }?>
		</td>
	</tr>
	<tr>
		<td  colspan="2" align="right"><input type="submit" name="changepwd" value="Save" /></td>
	</tr>
</table>
</form>