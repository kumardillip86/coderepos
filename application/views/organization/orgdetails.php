<?php include(APP_DIR.'views/header.php'); ?>
<script type="text/javascript">
 function orgvalidate(){
    validator=$("#orgformid").validate({
        rules: {
			"org[od_name]": {
				required:true
			},
			"org[od_type]": {
				required:true
			},
			"org[od_industry]": {
				required:true
			},
			"org[od_country]": {
				required:true
			},
			"org[od_state]": {
				required:true
			},
			"org[od_city]": {
				required:true
			},
			"org[od_street]": {
				required:true
			},
			"org[od_zip]": {
				required:true
			},
			"org[od_contact_no]": {
				required:true
			},
			"org[od_website]": {
				required:true
			}
	    },
	    messages: {
			"org[od_name]":{
				required:"<br>This field is required"
			},
			"org[od_type]":{
				required:"<br>This field is required"
			},
			"org[od_industry]":{
				required:"<br>This field is required"
			},
			"org[od_country]":{
				required:"<br>This field is required"
			},
			"org[od_state]":{
				required:"<br>This field is required"
			},
			"org[od_city]":{
				required:"<br>This field is required"
			},
			"org[od_street]":{
				required:"<br>This field is required"
			},
			"org[od_zip]":{
				required:"<br>This field is required"
			},
			"org[od_contact_no]":{
				required:"<br>This field is required"
			},
			"org[od_website]":{
				required:"<br>This field is required"
			}
	    }
	});
	x=validator.form();
	return x;
    }
	function showState() {
	var country_id = $('#od_country').val();
    var url="<?php echo HTTP_ROOT; ?>organization/getState";
	$.post(url,{"country_id":country_id},function(res){
	//alert(res);return false;
	$('#od_state').html(res);
		
	});
}

</script>
</head>


<?php 
if (isset($org_details) && $org_details) {
		$choice="update";              
	    }else{
                $choice="insert";                
            }
	
$od_name = isset($org_details['od_name'])?$org_details['od_name']:"";
$od_city = isset($org_details['od_city'])?$org_details['od_city']:"";
$od_street = isset($org_details['od_street'])?$org_details['od_street']:"";
$od_zip = isset($org_details['od_zip'])?$org_details['od_zip']:"";
$od_contact_no = isset($org_details['od_contact_no'])&&$org_details['od_contact_no']?$org_details['od_contact_no']:"";
$od_website = isset($org_details['od_website'])?$org_details['od_website']:"";
$od_type = isset($org_details['od_type'])?$org_details['od_type']:"";
$od_industry = isset($org_details['od_industry'])?$org_details['od_industry']:"";
$od_country = isset($org_details['od_country'])?$org_details['od_country']:"";
$od_state = isset($org_details['od_state'])?$org_details['od_state']:"";
$org_state = isset($org_state)?$org_state:"";

?>

<form action="<?php echo HTTP_ROOT?>organization/<?php echo $choice;?>" name="orgform" id="orgformid" method="post" onsubmit="return orgvalidate();">
<table cellpadding="4px" cellspacing="4px" align='center' style="border:2px solid #000099">
	<tr><td colspan="2" bgcolor="#000099" style="color:#FFFFFF"><b> Organization Details</b></td></tr>
	<tr>
		<td align="right" valign="top">Name:</td>
		<td><input type="text" name="org[od_name]" id="od_name" value="<?php echo $od_name;?>" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Type:</td>
		<td>
			<select name="org[od_type]" id="od_type">
			<option value="">--Select--</option>
			<?php foreach($org_type as $k => $v){ ?>
				<option value="<?php echo $k;?>" <?php if($od_type == $k){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">Industry:</td>
		<td>
			<select name="org[od_industry]" id="od_industry">
			<option value="">--Select--</option>
			<?php foreach($org_industry as $k => $v){ ?>
				<option value="<?php echo $k;?>" <?php if($od_industry == $k){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">Country:</td>
		<td>
			<select name="org[od_country]" id="od_country" onchange="return showState();">
			<option value="">--Select--</option>
			<?php foreach($org_country as $k => $v){ ?>
				<option value="<?php echo $k;?>" <?php if($od_country == $k){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">State:</td>
		<td>
			<select name="org[od_state]" id="od_state">
			<option value="">--Select--</option>
			<?php if($org_state){
				foreach($org_state as $k => $v){ ?>
				<option value="<?php echo $k;?>" <?php if($od_state == $k){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			<?php }
			} ?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top">City:</td>
		<td><input type="text" name="org[od_city]" id="od_city" value="<?php echo $od_city;?>"/></td>
	</tr>
	<tr>
		<td align="right" valign="top">Street:</td>
		<td><input type="text" name="org[od_street]" id="od_street" value="<?php echo $od_street;?>" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Zip:</td>
		<td><input type="text" name="org[od_zip]" id="od_zip" value="<?php echo $od_zip;?>" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Contact No:</td>
		<td><input type="text" name="org[od_contact_no]" id="od_contact_no" maxlength="10" value="<?php echo $od_contact_no;?>" /></td>
	</tr>
	<tr>
		<td align="right" valign="top">Website:</td>
		<td><input type="text" name="org[od_website]" id="od_website"  value="<?php echo $od_website;?>"/></td>
	</tr>
	<tr>
		<td></td>
		<td align="right"><input type="submit" name="save" value="Save" /></td>
	</tr>
</table>
</form>
</html>