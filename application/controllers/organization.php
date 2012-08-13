<?php
class Organization extends Controller {
	public function __construct($action){
		$this->checkSession($action);
		$this->session = $this->loadHelper('Session_helper');
	}

	function index(){
		$tpl = $this->loadView('organization/orgdetails');
		$ot = $this->loadModel('Organization_model');
		if(isset($_SESSION['ud_id'])){
			$id=$_SESSION['ud_id'];
			$org_details = $ot->getOrganization($id);
			$tpl->set('org_details', $org_details);
			
			/*$org_type_name = $ot->getOrganizationType($org_details['od_type']);
			$tpl->set('org_type_name', $org_type_name['ot_name']);
			$org_industry_name = $ot->getOrganizationIndustry($org_details['od_industry']);
			$tpl->set('org_industry_name', $org_industry_name['oi_name']);
			$org_country_name = $ot->getOrganizationCountry($org_details['od_country']);
			$tpl->set('org_country_name', $org_country_name['c_name']);
			$org_state_name = $ot->getOrganizationState($org_details['od_country']);
			$tpl->set('org_state_name', $org_state_name['s_name']);*/
			if(isset($org_details['od_country']) && $org_details['od_country']){
				$tpl->set('org_state',$ot->getstateName($org_details['od_country']));
			}
		}
		$tpl->set('org_type', $ot->getorgType());
		$tpl->set('org_industry',$ot->getorgIndustry());
		$tpl->set('org_country',$ot->getCountry());		
		$tpl->render();
	}
	function getState(){
		$tpl = $this->loadView('organization/state');
		$ot = $this->loadModel('Organization_model');
		$cid = $_POST['country_id'];
		$tpl->set('org_state',$ot->getstateName($cid));
		$tpl->render();
	}
	function insert(){
		if(isset($_POST['save'])){
				$arr = $_POST['org'];
				$arr['od_user_id'] = $_SESSION['ud_id'];
				$ot = $this->loadModel('Organization_model');
				$ot->insert($arr);
				$this->session->setFlash("Organization details successfully inserted.");
				$this->redirect('organization');
		}	
	}
	
	function update(){
		if(isset($_POST['save'])){
				$arr = $_POST['org'];
				$ot = $this->loadModel('Organization_model');
				$ot->update($arr);
				$this->session->setFlash("Organization details successfully updated.");
				$this->redirect('organization');
		}			
	}
	function insertContact(){
		if(isset($_POST['save'])){
				$arr = $_POST['contact'];
				$arr['od_user_id'] = $_SESSION['ud_id'];
				$ot = $this->loadModel('Organization_model');
				$ot->insert($arr);
				$this->session->setFlash("Primary contact details successfully inserted.");
				$this->redirect('organization/pcontactdetail');
		}	
	}
	function updateContact(){
		if(isset($_POST['save'])){
				$arr = $_POST['contact'];
				$ot = $this->loadModel('Organization_model');
				$ot->update($arr);				
				$this->session->setFlash("Primary contact details successfully updated.");
				$this->redirect('organization/pcontactdetail');
		}			
	}
	function pcontactdetail(){
		$position=array("CEO" => "CEO","MD" => "MD");	
		$organization_details = $this->loadModel('Organization_model');
		$tpl = $this->loadView('organization/contactdetail');
		$orgdetails = $organization_details ->getOrganization($_SESSION['ud_id']);	
		$tpl->set('orgdetails',$orgdetails);
		$tpl->set('position',$position);		
		$tpl->render();
	}
}
?>