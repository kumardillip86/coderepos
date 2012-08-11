<?php

class User_model extends Model {
	
	public function getSomething($id)
	{
		$id = $this->escapeString($id);
		$result = $this->query('SELECT * FROM something WHERE id="'. $id .'"');
		return $result;
	}
	
	public function getUser($email){
		$email = $this->escapeString($email);
		$sql = "SELECT * FROM user WHERE email_id='" . $email . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	
	public function insert($arr){
		$sql = "INSERT INTO user";
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
				$sql = $sql . " (" . $fld_str_key .",created_date) VALUES(" . $fld_str_value . ",NOW())";
				//print $sql;exit;
				$this->execute($sql);
	}
	
	public function checkemail($email){
		$email = $this->escapeString($email);
		$sql = "SELECT COUNT(*) AS count FROM user WHERE email_id='".$email."'";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	
	public function getUseremailmd5($email){
		$email = $this->escapeString($email);
		$qry="SELECT * FROM user WHERE md5(email_id)='".$email."' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($qry));
	}
	
	public function setPwd($email, $newpwd){
		$email = $this->escapeString($email);
		$sql="UPDATE user SET password='" .$newpwd. "' WHERE md5(email_id)='".$email."'";
		$this->execute($sql);
	}
	
	public function setPwdAndConfirm($cpwd, $cond){
		$cpwd = $this->escapeString($cpwd);
		$sql = "UPDATE user SET password = '".md5($cpwd)."', status = 1, activate_date=NOW(), lpwd = NOW() WHERE ".$cond;
		$this->execute($sql);
	}
}

?>
