<?php
class User extends Controller {
	public function __construct($action){
		//allow action(function name), if user not logged in, means session not exist
		$this->allow = array('register','login','checkemail','generatepwd','gotoChangepwd','forgotpwd','changepwd');
		//deny action(function name), if user is logged in, means session exist
		$this->deny = array('register','login','generatepwd','gotoChangepwd','forgotpwd');
		$this->checkSession($action);
		$this->session = $this->loadHelper('Session_helper');
	}

	function index(){
		//print_r($this->action);
		$template = $this->loadView('user/login');
		$template->render();
	}
	
	function register(){
		if(isset($_REQUEST['register'])){
			$user = $this->loadModel('User_model');
			$checkemail = $user->getUser($_POST['user']['email_id']);
			if(!$checkemail){
				$arr = $_POST['user'];
				$randpwd=$this->createRandomPassword();
				$arr['password'] = md5($randpwd);
				$arr['randomno'] = $this->generateRandom();
				$user->insert($arr);
				
				$link = HTTP_ROOT."user/gotoChangepwd/".md5($arr['email_id'])."/".$arr['password']."/".$arr['randomno'];
				//print $link;exit;
				
				$to = $_POST['user']['email_id'];
				$from = "admin@tropix.com";
				
				$body = "Hi ".$_POST['user']['fname'].",<br><br>Congratulations for creating a new account !!! Below are the credentials <br><br> Email : ".$_POST['user']['email_id']."<br> password :".$randpwd."<br><br> Please <a href='".$link."' target='_blank'>Click here</a> for set your own password<br>";
				$subject = "Welcome to our site and find credentials";
				$msg = $this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
				//print $body;exit;
				header('Location:'.HTTP_ROOT.'user/regsuccess');
				exit;
			}	
		}	
		$tpl = $this->loadView('user/register');
		$tpl->render();
	}
	
	function regsuccess($chk=''){
		$tpl = $this->loadView('user/regsuccess');
		if($chk == 'gpwd'){
			$tpl->set("gpwd",1);		
		}
		if($chk == 'fpwd'){
			$tpl->set("fpwd",1);		
		}
		$tpl->render();
	}
	
	function checkemail() {
		$user = $this->loadModel('User_model');
	    $res = $user->checkemail($_POST['email_id']);
	    if ($res['count'] >= 1) {
    	    print "1";exit;
	    }
	}
	
	function generatepwd($nrdme){
		if(isset($nrdme)){
			$user = $this->loadModel('User_model');
			$rand = $this->createRandomPassword();
			$newpwd=md5($rand);
			$randomno = $this->generateRandom();
			$res = $user->getUseremailmd5($nrdme);
			$name = $res['fname'];

			$email_id = $res['email_id'];	
			$new_link = HTTP_ROOT."user/gotoChangepwd/".md5($email_id)."/".$newpwd."/".$randomno;
		
			$user->setPwd($nrdme, $newpwd);
		
			$body = "Hi ".$name.",<br><br>Below are the credentials <br><br> Email : ".$email_id."<br> password :".$rand."<br><br> Please <a href='".$new_link."' target='_blank'>Click here</a> for set your own password<br>";
			$to = $email_id;
			$from = "admin@tropix.com";
			$subject = "Request new password";
			$this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
			header('Location:'.HTTP_ROOT.'user/regsuccess/gpwd');
			exit;
			//print $body;exit;
			
		}
	}
	
	function login(){
		$tpl = $this->loadView('user/login');
		if(isset($_REQUEST['login'])){
			$user = $this->loadModel('User_model');
			$res = $user->getUser($_POST['user']['email_id']);
			//print_r($res);print md5($_REQUEST['user']['password']);exit;
			if($res){
				if((md5($_REQUEST['user']['password']) == $res['password']) && !$res['status']) {
					//set session
					$_SESSION['id'] = $res['id'];
					$_SESSION['fname'] = $res['fname'];
					$_SESSION['mname'] = $res['mname'];
					$_SESSION['lname'] = $res['lname'];
					$_SESSION['email'] = $res['email_id'];
					//$_SESSION['status'] = $res['status'];
					header('Location:'.HTTP_ROOT.'user/changepwd');
					exit;
				}else if((md5($_REQUEST['user']['password']) != $res['password']) && !$res['status']) {
					//print "click here to send the password";
					$sendpwd_link = HTTP_ROOT."user/generatepwd/".md5($res['email_id']);//create the link..
					$tpl->set("sendpwd_link", $sendpwd_link);
					
				}elseif((md5($_REQUEST['user']['password']) != $res['password']) && $res['status']){
					//$error['msg'] = "Either email or password wrong";
					$tpl->set("error","Either email or password wrong");
				}else{
					//set session
					$_SESSION['id'] = $res['id'];
					$_SESSION['fname'] = $res['fname'];
					$_SESSION['mname'] = $res['mname'];
					$_SESSION['lname'] = $res['lname'];
					$_SESSION['email'] = $res['email_id'];
					$_SESSION['status'] = $res['status'];
					$_SESSION['msg'] = "Login sucessfully";
					//redirect to welcome page
					$this->session->setFlash("Login succssfully.");
					header('Location:'.HTTP_ROOT.'user/welcome');
					exit;
				}
			}else{
				//$error['msg'] = "Either email or password wrong";
				$tpl->set("error","Either email or password wrong");
			}
		}
		$tpl->render();
	}
	
	function gotoChangepwd($email, $pwd, $rndno){
		//$this->params
		$user = $this->loadModel('User_model');
		if(isset($rndno)){
			$res = $user->getUseremailmd5($email);
			//print_r($res);exit;//print $_REQUEST['pwd'];exit;
			if(($pwd == $res['password']) && !$res['status']) {
				$_SESSION['id'] = $res['id'];
				header('Location:' .HTTP_ROOT.'user/changepwd');
				exit;
			}
		}
	}
	
	function logout(){
		if(isset($_SESSION['id']) && $_SESSION['id']){
			$_SESSION['id'] = '';
			$_SESSION['fname'] = '';
			$_SESSION['mname'] = '';
			$_SESSION['lname'] = '';
			$_SESSION['email'] = '';
			session_unset();
			session_destroy();
			$this->session->setFlash("Logout succssfully.");
			header('Location:' .HTTP_ROOT);
			exit;
		}
	}
	
	function changepwd($el=''){
		$user = $this->loadModel('User_model');
		$tpl = $this->loadView('user/changepwd');
		if(isset($el)){
			$tpl->set("el",$el);
		}
		if(isset($_POST['changepwd'])){
			//print_r($_POST);exit;
			if($_POST['user']['password'] && $_POST['cpwd']){
				if($_POST['user']['password'] == $_POST['cpwd']){
					//print md5($_POST['cpwd']);exit;
					if(isset($_POST['elid'])){
						$cond = " md5(email_id) = '".$_POST['elid']."'";
					}else{
						$cond = " id = ".$_POST['hid'];
					}
					$user->setPwdAndConfirm($_POST['cpwd'], $cond);
					if(isset($_SESSION['status']) && $_SESSION['status']){
						header('Location:'.HTTP_ROOT.'user/welcome');
						exit;				
					}else{
						header('Location:'.HTTP_ROOT.'user/login');
						exit;
					}
				}else{
					//error message --- password mismatch
					$tpl->set($error['mismatch'],1);
					//$error['mismatch'] = 1;
				}
			}else{
				//error message -- blank validation
				//$error['blank'] = 1;
				$tpl->set($error['blank'],1);
			}
		}
		$tpl->render();
	}
	
	function forgotpwd(){
		$tpl = $this->loadView('user/forgotpwd');
		if(isset($_POST['forgotpwd'])){
			$user = $this->loadModel('User_model');
			$checkemail = $user->getUser($_POST['email']);
			if($checkemail){
				$el = md5($_POST['email']);
				$fglink = HTTP_ROOT."user/changepwd/".$el;
				$body = "Hi, <br><br> Please <a href='".$fglink."'>click here</a> to set new password.";
				$to = $_POST['email'];
				$from = "admin@tropix.com";
				$subject = "Forgot password";
				$this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
				//print $body;exit;
				header('Location:'.HTTP_ROOT.'user/regsuccess/fpwd');
				exit;
			}else{
				$tpl->set("error","This email id not exist.");
			}
		}
		$tpl->render();
	}
	
	function welcome(){
		$tpl = $this->loadView('user/welcome');
		$tpl->render();
	}
	
	function test(){
		//print_r($t);
		//print_r ($this->params);exit;
		$template = $this->loadView('test');
		$template->render();
	}
	
	function test2(){
		print"<pre>";
		print_r($_POST);
		print"</pre>";
		//exit;
		
		//$ex = $this->loadModel('Example_model');
		//$ex->getSomething(4);
		
		
		
		$template = $this->loadView('test2');
		$template->set("t",array('j'=>"juju",'k'=>"kuku"));
		$template->render();
	}
}
?>