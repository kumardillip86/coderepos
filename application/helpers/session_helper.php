<?php
class Session_helper {

	function set($key, $val)
	{
		$_SESSION["$key"] = $val;
	}
	
	function get($key)
	{
		return $_SESSION["$key"];
	}
	
	function destroy()
	{
		session_destroy();
	}
	
	public function setFlash($flashmsg){
		if(isset($flashmsg) && $flashmsg){
			session_start();
			$_SESSION['flash_message'] = $flashmsg; 
		}
	}
	
	public function flash($type='success'){
		if(isset($_SESSION['flash_message']) && $_SESSION['flash_message']){
			if($type == 'error'){
				$msg = "<font color='red'>".$_SESSION['flash_message']."</font>";
			}else{
				$msg = "<font color='green'>".$_SESSION['flash_message']."</font>";
			}
			$_SESSION['flash_message'] = '';
			return $msg;
		}	
	}
}
?>