<?php
class Main extends Controller {
	public function __construct($action){
		$this->checkSession($action);
	}
	function index(){
		$template = $this->loadView('main_view');
		$template->render();
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