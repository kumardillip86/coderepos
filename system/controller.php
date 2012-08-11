<?php
class Controller {
	var $allow = array();
	var $deny = array();
	public function checkSession($action){
		if(isset($_SESSION['id']) && isset($_SESSION['status'])){
			//action in the deny array
			//redirect to welcome page
			if(in_array($action, $this->deny)){
				$this->redirect('user/welcome');
			}
		}else{
			//if action in the allow array
			//allow to function render the page
			//else redirect to login page
			if(!in_array($action, $this->allow)){
				$this->redirect('user/login');
			}
		}
	}
	
	public function loadModel($name)
	{
		require(APP_DIR .'models/'. strtolower($name) .'.php');

		$model = new $name;
		return $model;
	}
	
	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}
	
	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}
	
	public function loadHelper($name)
	{
		require(APP_DIR .'helpers/'. strtolower($name) .'.php');
		$helper = new $name;
		return $helper;
	}
	
	public function redirect($loc)
	{
		global $config;
		//header('Location: '. $config['base_url'] . $loc); //commented by Peejay
		header('Location: '. HTTP_ROOT. $loc);
		exit;
	}
	
	public function getRealIpAddress(){
		if (!empty($_SERVER['HTTP_CLIENT_IP'])){
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		}
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	public function getLocationByIp($ip){
		//$ip = "119.226.151.75";
		$url='http://api.hostip.info/get_html.php?ip='.$ip.'&position=true';
		$data=file_get_contents($url);
		$a=array();
		$keys=array('Country','City','Latitude','Longitude','IP');
		$keycount=count($keys);
		for ($r=0; $r < $keycount ; $r++){
			$sstr= substr ($data, strpos($data, $keys[$r]), strlen($data));
			if ( $r < ($keycount-1))
				$sstr = substr ($sstr, 0, strpos($sstr,$keys[$r+1]));
			$s=explode (':',$sstr);
			$a[$keys[$r]] = trim($s[1]);
		}
		return $a;
	}
	public function isEmail($email){
		if(eregi("^[^@ ]+@[^@ ]+\.[^@ ]+$", $email)){
			return true;
		}
		else{
			return false;
		}
	}
	public function sendEmail($to,$from,$subject,$message){
		$bcc1 = "mail2pjena@gmail.com";
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers.= 'From:' .$from."\r\n";
		$headers.= 'BCC:' .$bcc1."\r\n";
		if(mail($to,$subject,$message,$headers)){
			return true;
		}
		else{
			return false;
		}
	}
	public function imageExists($dir,$image){
		if($image && file_exists($dir.$image)){
			return true;
		}
		else{
			return false;
		}
	}
	
	public function formatText($value) {
		$value = str_replace("“","\"",$value);
		$value = str_replace("”","\"",$value);
		$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
		$value = stripslashes($value);
		$value = html_entity_decode($value, ENT_QUOTES);
		$trans = get_html_translation_table(HTML_ENTITIES, ENT_QUOTES);
		$value = strtr($value, $trans);
		$value = stripslashes(trim($value));
		return $value;
	}
	public function setURL($name){
		$name = formatText($name);
		if(strstr($name," ")){
			$value = str_replace(" ","-",$name);
		}
		$value = urlencode($value);
		return $value;
	}
	public function formatCms($value) {
		$value = stripslashes(trim($value));
		$value = str_replace("“","\"",$value);
		$value = str_replace("”","\"",$value);
		$value = preg_replace('/[^(\x20-\x7F)\x0A]*/','', $value);
		$value = str_replace("~","&#126;",$value);
		$value = str_replace("a href=","a target='_blank' href=",$value);
		return stripslashes($value);
	}
	public function shortLength($value, $len) {
		$value_format = formatText($value);
		$value_raw = html_entity_decode($value_format, ENT_QUOTES);
		
		if(strlen($value_raw) > $len){
			$value_strip = substr($value_raw,0,$len);
			$value_strip = formatText($value_strip);
			$lengthvalue = "<span title='".$value_format."'>".$value_strip."...</span>";
		}
		else{
			$lengthvalue = $value_format;
		}
		return $lengthvalue;
	}
	public function formatPrice($value) {
		$value = stripslashes(trim($value));
		$val = substr($value,-2);
		
		if($val == 0) {
			return "$".substr($value,0,-3);
		}
		else {
			return "$".$value;
		}
	}
	public function dateDisplay($datetime)
	{
		if($datetime != "" && $datetime != "NULL" && $datetime != "0000-00-00 00:00:00") {
			return date("d/m/Y", strtotime($datetime));
		}
		else{
			return false;
		}
	}
	public function timeDisplay($datetime)
	{
		if($datetime != "" && $datetime != "NULL" && $datetime != "0000-00-00 00:00:00") {
			return date("g:i a", strtotime($datetime));
		}
		else{
			return false;
		}
	}
	#Saturday 29th of October 2011
	public function dateInsert($date)
	{
		if($date != "" && $date != "NULL" && $date != "0000-00-00") {
			return date("d/m/Y", strtotime($date));
		}
		else{
			return false;
		}
	}
	public function uploadBanner($uploadedfile,$image,$image_path,$filSize)
	{
		if($image)
		{
			$image = strtolower($image);
			$extname = substr(strrchr($image, "."), 1);
			if(($extname !='gif') && ($extname !='jpg') && ($extname !='jpeg') && ($extname !='png') && ($extname !='bmp'))
			{
				return "EXT";
			}
			else
			{
				$inkb= $filSize/1024;
				if($inkb > 1024)
				{
					return "SIZE";
				}
				else
				{
					$t=time();
					$filename_new = $t.".".$extname;
					$target_path = $image_path.$filename_new;
					move_uploaded_file($uploadedfile, $target_path);
					return $filename_new;
				}
			}
		}
	}
	
	public function nextDate($givenDateTime,$value,$type)
	{
		if($givenDateTime)
		{
			$dat = explode(" ",$givenDateTime);
			$dat1 = explode("-",$dat[0]);
			$dat2 = explode(":",$dat[1]);
			if($type == "day")
			{
				$next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1], $dat1[2]+$value, $dat1[0]);
			}
			if($type == "month")
			{
				$next_dt = mktime($dat2[0], $dat2[1], $dat2[2], $dat1[1]+$value, $dat1[2], $dat1[0]);
			}
			$datetime = date("Y-m-d H:i:s", $next_dt);
			return $datetime;
		}
		else
		{
			return "";
		}
	}
	public function fullDateDiff($time1, $time2, $precision = 6) 
	{
		if(!is_int($time1)) 
		{
			$time1 = strtotime($time1);
		}
		if(!is_int($time2)) 
		{
			$time2 = strtotime($time2);
		}
		if ($time1 > $time2) 
		{
			$ttime = $time1;
			$time1 = $time2;
			$time2 = $ttime;
		}
	
		$intervals = array('year','month','day');
		$diffs = array();
		
		foreach ($intervals as $interval) 
		{
			$diffs[$interval] = 0;
			$ttime = strtotime("+1 " . $interval, $time1);
			while ($time2 >= $ttime) 
			{
				$time1 = $ttime;
				$diffs[$interval]++;
				$ttime = strtotime("+1 " . $interval, $time1);
			}
		}
		$count = 0;
		$times = array();
		foreach ($diffs as $interval => $value) 
		{
			if($count >= $precision) 
			{
				break;
			}
			if($value > 0) 
			{
				if ($value != 1) 
				{
					$interval .= "s";
				}
				$times[] = $value . " " . $interval;
				$count++;
			}
		}
		return implode(", ", $times);
	}
	public function pagingShowRecords($total_records,$page_limit,$page) {
		$numofpages = $total_records / $page_limit;
		for($j = 1; $j <= $numofpages; $j++) { }
		$start = $page*$page_limit - $page_limit;
		if($page == $j) {
			$start1 = $start+1;
			$retRec = $start1." - ".$total_records." of ".$total_records;
		}
		else {
			$start1 = $start+1;
			$retRec = $start1." - ".$page*$page_limit." of ".$total_records;
		}
		return $retRec;
	}
	public function pagingNumbers($total_records,$page_limit,$page,$curpage) {
		$data = "";
		if($page_limit < $total_records)
		{
			if($page != 1)
			{
				$pageprev = $page-1;
				$data.="&lt;&nbsp;<a href=\"$curpage?page=$pageprev$urlvalue\" style='text-decoration:none'><span class=\"active_box\">Prev</span></a></span>&nbsp;";
			}
			else
			{
				$data.="<span class=\"inactive_box\" >&lt;&nbsp;Prev</span>";
			}
			$numofpages = $total_records / $page_limit;
	
			for($i = 1; $i <= $numofpages; $i++)
			{
				if($i == $page)
				{
					$data.= "&nbsp;<span class='inactive_box'>".$i."</span>&nbsp;";
				}
				else
				{
					$data.="&nbsp;<a href=\"$curpage?page=$i$search_string$urlvalue\" style='text-decoration:none'><span class=\"active_box\">$i</span></a> ";
				}
			}
			if(($total_records % $page_limit) != 0)
			{
				if($i == $page)
				{
					$data.="<span class='inactive_box'>".$i."</span>";
				}
				else
				{
					$data.="<a href=\"$curpage?page=$i$search_string$urlvalue\" style='text-decoration:none'><span class=\"active_box\">$i</span></a> ";
				}
			}
			if(($total_records - ($page_limit * $page)) > 0)
			{
				$pagenext = $page+1;
				$data.="&nbsp;<a href=\"$curpage?page=$pagenext$urlvalue\" style='text-decoration:none'><span class=\"active_box\" >Next</span></a>&nbsp;&gt;";
				
			}
			else
			{
				$data.="&nbsp;<span class=\"inactive_box\">Next&nbsp;&gt;</span>";
			}
		}
		return $data;
	}
	
	//////////////////////////////////////////////////////////////////////
				/*Random password generation*/
	/////////////////////////////////////////////////////////////////////
	
	public function createRandomPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= 7) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
		
	//////////////////////////////////////////////////////////////////////
				/*Generate random number*/
	//////////////////////////////////////////////////////////////////////
	
	public function generateRandom() {
	   $rand = rand(10000, 99999);
		return  $rand;
	}
	//////////////////////////////////////////////////////////////////////
				/*Sending mail*/
	//////////////////////////////////////////////////////////////////////
	
	public function sendmail($to, $subject, $body, $from = '', $cc = '', $bcc = '') {
		$headers = "";
		if ($from) {
			$headers .= "From:$from\n";
		}
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		if ($to) {
	//			$headers .= "To: $to\n";
		}
		if ($cc) {
			$headers .= "Cc:$cc\n";
		}
		if ($bcc) {
			$headers .= "Bcc:$bcc\n";
		}
		$headers .= "Message-ID: <" . time() . rand(1, 1000) . "@" . $_SERVER['SERVER_NAME'] . ">" . "\n";
		ini_set("sendmail_from", $from);
		$msg = mail($to, $subject, stripslashes($body), $headers, "-f{$to}");
		return $msg;
	}

    
}

?>