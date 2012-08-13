<?php

class User_model extends Model {
	
	public function getUser($email){
		$email = $this->escapeString($email);
		$sql = "SELECT * FROM user_details WHERE ud_email_id='" . $email . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getUserListing(){
		$sql = "SELECT * FROM user_details WHERE ud_type=2 AND ud_parent_id='".$_SESSION['ud_id']."'";
		$sh = mysql_query($sql);
		while($row = mysql_fetch_assoc($sh)){
			$res[] = $row;
		}
		return $res;
		//return mysql_fetch_assoc(mysql_query($sql));
	}
	
	public function insert($arr){
		$sql = "INSERT INTO user_details";
				$fld_str_key = "";
				$fld_str_value = "";
				foreach ($arr as $key => $value) {
					$fld_str_key .= $key . ",";
				}
				$fld_str_key = rtrim($fld_str_key, ',');
				foreach ($arr as $key => $value) {
					if (!isset($value) || $value == "") {
						$fld_str_value .= "NULL,";
					} else {
						$fld_str_value .= "'" . $value . "',";
					}
				}
				$fld_str_value = rtrim($fld_str_value, ',');
				$sql = $sql . " (" . $fld_str_key .",ud_created_date) VALUES(" . $fld_str_value . ",NOW())";
				//print $sql;exit;
				$this->execute($sql);
	}
	public function deleteUser($id){
		$sql = "DELETE FROM  user_details WHERE ud_id=" . $id . "";
		$this->execute($sql);	
	}
	public function checkemail($email){
		$email = $this->escapeString($email);
		$sql = "SELECT COUNT(*) AS count FROM user_details WHERE ud_email_id='".$email."'";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function updateStatus($status,$id){
		$sql="UPDATE user_details SET ud_status='" .$status. "' WHERE ud_id='".$id."'";
		$this->execute($sql);
	}
	public function updateresetpwd($reset_pwd,$id){
		$sql="UPDATE user_details SET ud_password='" .$reset_pwd. "' WHERE ud_id=".$id;
		$this->execute($sql);
	}
	
	public function getUseremailmd5($email){
		$email = $this->escapeString($email);
		$qry="SELECT * FROM user_details WHERE md5(ud_email_id)='".$email."' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($qry));
	}
	
	public function setnewPwd($email, $newpwd){
		$email = $this->escapeString($email);
		$sql="UPDATE user_details SET ud_password='" .$newpwd. "' WHERE md5(ud_email_id)='".$email."'";
		$this->execute($sql);
	}
	
	public function setPwdAndConfirm($cpwd, $cond){
		$cpwd = $this->escapeString($cpwd);
		$sql = "UPDATE user_details SET ud_password = '".md5($cpwd)."', ud_status = 1, ud_activate_date=NOW(), ud_lpwd_date = NOW() WHERE ".$cond;
		$this->execute($sql);
	}
	
	public function setPwd($cpwd, $cond){
		$cpwd = $this->escapeString($cpwd);
		$sql = "UPDATE user_details SET ud_password = '".md5($cpwd)."', ud_lpwd_date = NOW() WHERE ".$cond;
		$this->execute($sql);
	}
}

?>
