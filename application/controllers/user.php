<?php
class User extends Controller {
	public function __construct($action){
		//allow action(function name), if user not logged in, means session not exist
		$this->allow = array('register','login','checkemail','generatepwd','gotoChangepwd','forgotpwd','changepwd','regsuccess');
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
			$checkemail = $user->getUser($_POST['user']['ud_email_id']);
			if(!$checkemail){
				$arr = $_POST['user'];
				$randpwd=$this->createRandomPassword();
				$arr['ud_password'] = md5($randpwd);
				$arr['ud_randomno'] = $this->generateRandom();
				$user->insert($arr);
				
				$link = HTTP_ROOT."user/gotoChangepwd/".md5($arr['ud_email_id'])."/".$arr['ud_password']."/".$arr['ud_randomno'];
				//print $link;exit;
				
				$to = $_POST['user']['ud_email_id'];
				$from = "admin@tropix.com";
				
				$body = "Hi ".$_POST['user']['ud_fname'].",<br><br>Congratulations for creating a new account !!! Below are the credentials <br><br> Email : ".$_POST['user']['ud_email_id']."<br> password :".$randpwd."<br><br> Please <a href='".$link."' target='_blank'>Click here</a> for set your own password<br>";
				$subject = "Welcome to our site and find credentials";
				$msg = $this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
				//print $body;exit;
				$this->redirect('user/regsuccess');
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
	function updateStatus(){
		if($_POST['status'] == 1){
			$status=0;
		}else{
			$status=1;
		}
		$user = $this->loadModel('User_model');
		$res = $user->updateStatus($status,$_POST['id']);
		exit;
	}
	
	function generatepwd($nrdme){
		if(isset($nrdme)){
			$user = $this->loadModel('User_model');
			$rand = $this->createRandomPassword();
			$newpwd=md5($rand);
			$randomno = $this->generateRandom();
			$res = $user->getUseremailmd5($nrdme);
			$name = $res['ud_fname'];

			$email_id = $res['ud_email_id'];	
			$new_link = HTTP_ROOT."user/gotoChangepwd/".md5($email_id)."/".$newpwd."/".$randomno;
		
			$user->setnewPwd($nrdme, $newpwd);
		
			$body = "Hi ".$name.",<br><br>Below are the credentials <br><br> Email : ".$email_id."<br> password :".$rand."<br><br> Please <a href='".$new_link."' target='_blank'>Click here</a> for set your own password<br>";
			$to = $email_id;
			$from = "admin@tropix.com";
			$subject = "Request new password";
			$this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
			$this->redirect('user/regsuccess/gpwd');
			//print $body;exit;
			
		}
	}
	function userlisting(){	
		$user = $this->loadModel('User_model');
		$ulisting = $user->getUserListing();
		$temp = $this->loadView('user/userlisting');
		if(isset($_POST['usave'])){
			$arr = $_POST['user'];
			$randpwd=$this->createRandomPassword();
			$arr['ud_password'] = md5($randpwd);
			$arr['ud_parent_id']=$_SESSION['ud_id'];
			$arr['ud_type']=2;
			$arr['ud_status']=1;
			$rand_num = $this->generateRandom();
			$user->insert($arr);
			$link = HTTP_ROOT;
			
			$to = $_POST['user']['ud_email_id'];
			$from = "admin@tropix.com";
			$body = "Hi ".$_POST['user']['ud_fname'].",<br><br>Congratulations!!! You have been added as a user to this site.
				Please find below credentials for access<br><br> 
				Email : ".$_POST['user']['ud_email_id']."<br> 
				password :".$randpwd."<br><br> 
				Please <a href='".$link."' target='_blank'>Click here</a> for login.<br>";
				$subject = "Welcome to you as a user";
				$msg = $this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
				//print $body;exit;
			$this->session->setFlash("New user created succssfully.");
			$this->redirect('user/userlisting');
		}
		$temp->set('ulisting', $ulisting);
		$temp->render();
		
	}
	function deleteUser(){
	$id=$this->params[0];
		$user = $this->loadModel('User_model');
		$user->deleteUser($id);
		$this->session->setFlash("New user deleted succssfully.");
		$this->redirect('user/userlisting');	
	}
	
	function resetpwd(){
		$user = $this->loadModel('User_model');
		$name = $_POST['name'];
		$email = $_POST['email'];
		$id = $_POST['id'];
		$reset_randpwd = $this->createRandomPassword();
		$reset_pwd = md5($reset_randpwd);
		$result = $user->updateresetpwd($reset_pwd,$id);
		$link = HTTP_ROOT;
			
		$to = $_POST['email'];
		$from = "admin@tropix.com";
		$body = "Hi ".$name.",<br><br>Your password have been changed.
			Please find below credentials for access<br><br> 
			Email : ".$_POST['email']."<br> 
			password :".$reset_randpwd."<br><br> 
			Please <a href='".$link."' target='_blank'>Click here</a> for login.<br>";
		$subject = "Reset password";
		$msg = $this->sendmail($to, $subject, $body, $from, $cc = '', $bcc = ''); // also u can pass  $cc,$bcc
		print $body;exit;
		if($result){
			print "1";exit;
		}
		
	}
	
	function login(){
		$tpl = $this->loadView('user/login');
		if(isset($_REQUEST['login'])){
			$user = $this->loadModel('User_model');
			$res = $user->getUser($_POST['user']['ud_email_id']);
			//print_r($res);print md5($_REQUEST['user']['password']);exit;
			if($res){
				if((md5($_REQUEST['user']['ud_password']) == $res['ud_password']) && !$res['ud_status']) {
					if($res['ud_type']==1){
						//set session
						$_SESSION['ud_id'] = $res['ud_id'];
						$this->redirect('user/changepwd');
					}else{
						$tpl->set("error","Your A/C has been blocked, Please contact with Admin.");
					}
				}else if((md5($_REQUEST['user']['ud_password']) != $res['ud_password']) && !$res['ud_status']) {
					//print "click here to send the password";
					$sendpwd_link = HTTP_ROOT."user/generatepwd/".md5($res['ud_email_id']);//create the link..
					$tpl->set("sendpwd_link", $sendpwd_link);
					
				}elseif((md5($_REQUEST['user']['ud_password']) != $res['ud_password']) && $res['ud_status']){
					//$error['msg'] = "Either email or password wrong";
					$tpl->set("error","Either email or password wrong");
				}else{
					//set session
					$_SESSION['ud_id'] = $res['ud_id'];
					$_SESSION['ud_fname'] = $res['ud_fname'];
					$_SESSION['ud_mname_initial'] = $res['ud_mname_initial'];
					$_SESSION['ud_lname'] = $res['ud_lname'];
					$_SESSION['ud_email_id'] = $res['ud_email_id'];
					$_SESSION['ud_status'] = $res['ud_status'];
					$_SESSION['ud_type'] = $res['ud_type'];
					//redirect to welcome page
					$this->session->setFlash("Login succssfully.");
					$this->redirect('user/welcome');
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
			if(($pwd == $res['ud_password']) && !$res['ud_status']) {
				$_SESSION['ud_id'] = $res['ud_id'];
				$this->redirect('user/changepwd');
			}
		}
	}
	
	function logout(){
		if(isset($_SESSION['ud_id']) && $_SESSION['ud_id']){
			$_SESSION['ud_id'] = '';
			$_SESSION['ud_fname'] = '';
			$_SESSION['ud_mname_initial'] = '';
			$_SESSION['ud_lname'] = '';
			$_SESSION['ud_email_id'] = '';
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
						$cond = " md5(ud_email_id) = '".$_POST['elid']."'";
						$user->setPwd($_POST['cpwd'], $cond);
					}else{
						$cond = " ud_id = ".$_POST['hid'];
						
						if(isset($_SESSION['ud_status']) && $_SESSION['ud_status']){
							$user->setPwd($_POST['cpwd'], $cond);
						}else{
							$user->setPwdAndConfirm($_POST['cpwd'], $cond);
						}
						
					}

					if(isset($_SESSION['ud_status']) && $_SESSION['ud_status']){
						$this->redirect('user/welcome');
					}else{
						$this->redirect('user/login');
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
				$this->redirect('user/regsuccess/fpwd');
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