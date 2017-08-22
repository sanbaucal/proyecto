<?php
class Admin_PersonController extends Zend_Controller_Action{

	public function init(){
		$sesion  = Zend_Auth::getInstance();
		if(!$sesion->hasIdentity() ){
			$this->_helper->redirector('index',"index",'default');
		}
		$login = $sesion->getStorage()->read();
		$this->sesion = $login;
	}

	public function indexAction(){
		try {
			$fm=new Admin_Form_Buscar();
			
			$dbperson=new Api_Model_DbTable_Person();
			$eid=$this->sesion->eid;
			$where = array('eid'=>$eid);
			$order = array('last_name0');
			$dataperson = $dbperson->_getFilter($where,null,$order);

			$this->view->fm=$fm;
			$this->view->dataperson = $dataperson;
		} catch (Exception $e) {
			print "Error: Person".$e->getMessage();
		}
	}

	public function getpersonAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$eid=$this->sesion->eid;
			$pid=$this->_getParam('pid');

			if ($pid) {
				$where=array('eid'=>$eid,'pid'=>$pid);
				$dbperson=new Api_Model_DbTable_Person();
				$dataperson[0]=$dbperson->_getOne($where);

			}

			$name = $this->_getParam('name');
			if($name){
				$eid = $this->sesion->eid;
				$name = trim(strtoupper($name));
				$name = mb_strtoupper($name,'UTF-8');
				$bdp=new Api_Model_DbTable_Person();
				$dataperson=$bdp->_getPersonxname($name,$eid);
			}

			$this->view->dataperson=$dataperson;
		} catch (Exception $e) {
			print "Error: get Person".$e->getMessage();
		}
	}

	public function newAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$eid=$this->sesion->eid;
			$register=$this->sesion->uid;
			$fm=new Admin_Form_Personnew();
			$this->view->fm=$fm;

			if ($this->getRequest()->isPost()){ 

				$frmdata=$this->getRequest()->getPost();

				if ($fm->isValid($frmdata)){
					
					$this->view->pid=$frmdata['pid'];
					unset($frmdata['Guardar']);
					trim($frmdata['last_name0']);
					trim($frmdata['last_name1']);
					trim($frmdata['first_name']);

					$frmdata['last_name0']=strtoupper($frmdata['last_name0']);
					$frmdata['last_name1']=strtoupper($frmdata['last_name1']);
					$frmdata['first_name']=ucwords($frmdata['first_name']);
					$frmdata['eid']=$eid;					
					$frmdata['created']=date('Y-m-d h:m:s');
					$frmdata['register']=$register;
					$frmdata['birthday']=date('Y-m-d',strtotime($frmdata['birthday']));

					$reg_= new Api_Model_DbTable_Person();
					
					if ($reg_->_save($frmdata)) {
						$this->view->valor=1;
					}
				}
			}
			
		} catch (Exception $e) {
			print "Error: new Person".$e->getMessage();
		}
	}

	public function updatepersonAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$dper=new Api_Model_DbTable_Person();
			
			$eid=$this->sesion->eid;
			$modified=$this->sesion->uid;
			$pid=$this->_getParam('pid');
			$where=array('eid'=>$eid,'pid'=>$pid);
			$data=$dper->_getOne($where);
			$fm=new Admin_Form_Personnew();
			$fm->pid->setAttrib('readonly',true);
			
			if ($this->getRequest()->isPost()) {
				$frmdata=$this->getRequest()->getPost();				
				$frmdata['pid']=$frmdata['pid'];
				if ($fm->isValid($frmdata)) {
					$this->view->pid=$frmdata['pid'];
					unset($frmdata['Actualizar']);
					trim($frmdata['last_name0']);
					trim($frmdata['last_name1']);
					trim($frmdata['first_name']);
					trim($frmdata['address']);
					$frmdata['updated']=date('Y-m-d h:m:s'); 
					$frmdata['modified']=$modified;
					$frmdata['birthday']=date('Y-m-d',strtotime($frmdata['birthday']));
					$pk['eid']=$eid;
					$pk['pid']=$pid;                   
					$reg_= new Api_Model_DbTable_Person();
					if ($reg_->_update($frmdata,$pk)) {
						$this->view->clave=1;
					}
				}
				else
				{
					echo "Ingrese nuevamente por favor";
				}
			}
			else{
				$fm->populate($data);
				$this->view->pid=$pid;
			}         
			$this->view->fm=$fm;

		} catch (Exception $e) {
			print "Error: update Person ".$e->getMessage();
		}
	}

	public function listexamAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$DBexam = new Api_Model_DbTable_Examperson();

			$eid = $this->sesion->eid;
			$pid = $this->_getParam('pid');

			$where = array('eid'=>$eid, 'pid'=>$pid);
			$dataExam = $DBexam->_getFilter($where,null,null);

			$this->view->dataExam = $dataExam;
			$this->view->pid = $pid;

		} catch (Exception $e) {
			print "Error: ".$e->getMessage();
		}
	}

	public function savexamAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$DBexam = new Api_Model_DbTable_Examperson();

			$eid = $this->sesion->eid;
			$frmdata=$this->getRequest()->getPost();
			$frmdata['description'] = trim($frmdata['description']);
			$frmdata['result'] = trim($frmdata['result']);
			$frmdata['eid'] = $eid;

			if ($DBexam->_save($frmdata)) {
				$result['status'] = true;
			}
			else{
				$result['status'] = false;
			}

			return $this->_helper->json->sendJson($result);

		} catch (Exception $e) {
			print "Error: ".$e->getMessage();
		}
	}

	public function updatedexamAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$DBexam = new Api_Model_DbTable_Examperson();

			$eid = $this->sesion->eid;
			$frmdata=$this->getRequest()->getPost();
			$frmdata['description'] = trim($frmdata['description']);
			$frmdata['result'] = trim($frmdata['result']);
			$frmdata['eid'] = $eid;

			$pk = array('eid'=>$eid,'pid'=>$frmdata['pid'],'epid'=>$frmdata['epid']);

			if ($DBexam->_update($frmdata, $pk)) {
				$result['status'] = true;
			}
			else{
				$result['status'] = false;
			}

			return $this->_helper->json->sendJson($result);

		} catch (Exception $e) {
			print "Error :".$e->getMessage();
		}
	}

	public function deletedexamAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$DBexam = new Api_Model_DbTable_Examperson();

			$eid = $this->sesion->eid;
			$frmdata=$this->getRequest()->getPost();

			$pk = array('eid'=>$eid,'pid'=>$frmdata['pid'],'epid'=>$frmdata['epid']);

			if ($DBexam->_delete($pk)) {
				$result['status'] = true;
			}
			else{
				$result['status'] = false;
			}

			return $this->_helper->json->sendJson($result);
		} catch (Exception $e) {
			print "Error: ".$e->getMessage();
		}
	}


	public function printexamAction(){
		try {
			$this->_helper->layout()->disableLayout();
			$DBexam = new Api_Model_DbTable_Examperson();
			$DBperson = new Api_Model_DbTable_Person();

			$eid = $this->sesion->eid;
			$pid = $this->_getParam('id');

			$where = array('eid'=>$eid, 'pid'=>$pid);
			$dataExam = $DBexam->_getFilter($where,null,null);
			$this->view->dataExam = $dataExam;

			// 
			$dataperson=$DBperson->_getOne($where);
			$this->view->dataPerson = $dataperson;
		} catch (Exception $e) {
			print "Error: ".$e->getMessage();
		}
	}

}