<?php

class Organization_model extends Model {
	
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
	public function getOrganization($id){
		$sql = 'SELECT * FROM organization_details WHERE od_user_id= "'. $id .'"';
		//print $sql;exit;
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getOrganizationType($od_type){
		$sql = "SELECT ot_name FROM  organization_type WHERE ot_id='" . $od_type . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getOrganizationIndustry($industry_id){
		$sql = "SELECT oi_name FROM  organization_industry WHERE oi_id='" . $industry_id . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getOrganizationCountry($con_id){
		$sql = "SELECT c_name FROM  country WHERE c_id='" . $con_id . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getOrganizationState($state_id){
		$sql = "SELECT s_name FROM  state WHERE s_id='" . $state_id . "' LIMIT 1";
		return mysql_fetch_assoc(mysql_query($sql));
	}
	public function getorgType(){
		$sql = "SELECT ot_id, ot_name FROM organization_type";
		$sh = mysql_query($sql);
		while($row = mysql_fetch_assoc($sh)){
			$res[$row['ot_id']] = $row['ot_name'];
		}
		return $res;
	}
	public function getorgIndustry(){
		$sql = "SELECT oi_id, oi_name FROM organization_industry";
		$sh = mysql_query($sql);
		while($row = mysql_fetch_assoc($sh)){
			$res[$row['oi_id']] = $row['oi_name'];
		}
		return $res;
	}
	public function getCountry(){
		$sql = "SELECT c_id, c_name FROM country";
		$sh = mysql_query($sql);
		while($row = mysql_fetch_assoc($sh)){
			$res[$row['c_id']] = $row['c_name'];
		}
		return $res;
	}
	public function getstateName($cid){
		$sql = "SELECT s_id, s_name FROM  state WHERE c_id=".$cid;
		$sh = mysql_query($sql);
		while($row = mysql_fetch_assoc($sh)){
			$res[$row['s_id']] = $row['s_name'];
		}
		return $res;
	}
	public function insert($arr){
		$sql = "INSERT INTO organization_details";
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
				$sql = $sql . " (" . $fld_str_key .",od_created) VALUES(" . $fld_str_value . ",NOW())";
				$this->execute($sql);
	}
	public function update($arr) {
		$sql = "UPDATE organization_details SET ";
			foreach ($arr as $key => $value) {
				$sql .= "" . $key . "='" . $value . "',";
			}
			$new_cond ="od_user_id=".$_SESSION['ud_id'];
					$sql = trim($sql, ',');
			$sql .= " WHERE " . $new_cond;
			//print $sql;exit;
			$this->execute($sql);
    
	}
}
?>