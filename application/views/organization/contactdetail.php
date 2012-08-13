<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">
 function validateContact(){
    validator=$("#contactformid").validate({
        rules: {
			"contact[od_pname]": {
				required:true
			},
			"contact[od_position]": {
				required:true
			},
			"contact[od_pcontact_no]": {
				required:true
			},
			"contact[od_pemail]": {
				required:true,
				email:true
			}			
	    },
	    messages: {
			"contact[od_pname]":{
				required:"<br>This field is required"
			},
			"contact[od_position]":{
				required:"<br>This field is required"
			},
			"contact[od_pcontact_no]":{
				required:"<br>This field is required"
			},
			"contact[od_pemail]":{
				required:"<br>This field is required",
				email:"<br>Please enter valid email address"
			}
	    }
	});
	x=validator.form();
	return x;
    }
</script>
</head>


<?php 
if (isset($orgdetails) && $orgdetails) {
		$choice="updateContact";              
	    }else{
                $choice="insertContact";                
            }
	
$od_pcontact_no = isset($orgdetails['od_pcontact_no'])&&$orgdetails['od_pcontact_no']?$orgdetails['od_pcontact_no']:"";
$od_pos = isset($orgdetails['od_position'])?$orgdetails['od_position']:"";
?>

<form action="<?php echo HTTP_ROOT?>organization/<?php echo $choice;?>" name="contactform" id="contactformid" method="post" onsubmit="return validateContact();">
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b>Primary Contact Details</b></td></tr>
	<tr>
		<td align="right" valign="top">Name:</td>
		<td><input type="text" name="contact[od_pname]" id="od_pname"  value="<?php echo $_SESSION['ud_fname'];?>"/></td>
	</tr>
	<tr>
		<td align="right" valign="top">Position:</td>
		<td>
			<select name="contact[od_position]" id="od_position">
			<option value="">--Select--</option>
			<?php foreach($position as $k => $v){ ?>
				<option value="<?php echo $k;?>" <?php if($od_pos == $k){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			<?php }?>
			</select>
			</select>
		</td>
	</tr>	
	<tr>
		<td align="right" valign="top">Contact No:</td>
		<td><input type="text" name="contact[od_pcontact_no]" id="od_pcontact_no" maxlength="10"  value="<?php echo $od_pcontact_no; ?>"/></td>
	</tr>
	<tr>
		<td align="right" valign="top">Email Id:</td>
		<td><input type="text" name="contact[od_pemail]" id="od_pemail" value="<?php echo $_SESSION['ud_email_id'];?>" /></td>
	</tr>	
	<tr>
		<td></td>
		<td align="right"><input type="submit" name="save" value="Save" /></td>
	</tr>
</table>
</form>
</html>