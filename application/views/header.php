<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coderepos</title>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo JS_PATH;?>jquery.validate.js"></script>
<style type="text/css">
.error{
color:#FF0000;
font:verdana 12px;}
</style>
</head>
<body>
	<center><h1>Header</h1></center>
	<?php if(isset($_SESSION['id']) && isset($_SESSION['status'])){ ?>
		<div>
			<div style="float:left">
				Welcome <?php echo $_SESSION['fname']; ?>
			</div>
			<div style="float:right">
				<a href="<?php echo HTTP_ROOT; ?>user/changepwd">Change Password</a>&nbsp;||&nbsp;
				<a href="<?php echo HTTP_ROOT; ?>user/logout">Logout</a>
			</div>
		</div>
	<?php }else{ ?>
		<a href="<?php echo HTTP_ROOT;?>user/register">Register</a>&nbsp;||&nbsp;
		<a href="<?php echo HTTP_ROOT;?>user/login">Login</a>
	
	<?php }?>
	<div style="clear:both"></div>
	<hr/>
	<br/><br/>
	<?php echo $this->session->flash();?>