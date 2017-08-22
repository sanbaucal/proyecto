<?php

class IndexController extends Zend_Controller_Action {

	public function init(){
		$options = array(
			'layout' => 'login',
		);
		Zend_Layout::startMvc($options);
	}

	public function indexAction()
	{
		try{
		$sesion1  = Zend_Auth::getInstance();
		if($sesion1->hasIdentity()){
			$sesion = $sesion1->getStorage()->read();
			$this->_helper->redirector('index','index',($sesion->rol['module']));
		}

		$form = new Default_Form_Login();
		$this->view->form = $form;
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			if ($form->isValid($formData)) {
				$eid = "1";
				$rid = "AD";
				$prefix = "AD";
				$uid = $form->getValue('usuario');
				$clave = $form->getValue('clave');
				$pass = md5($clave);
				$dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter();
				$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter,'base_users','uid','password','rid');
				$authAdapter->getDbSelect()->where("state = 'A' and eid='$eid' and rid='$rid'");
				$authAdapter->setIdentity($uid)->setCredential($pass);
				$auth = Zend_Auth::getInstance();
				$result = $auth->authenticate($authAdapter);

				if ($result->isValid()) {
					// set value Period System
					$data  = $authAdapter->getResultRowObject(array('eid','uid','pid','rid'));
					
					// Begin Var
					$data->infouser    = new stdClass();
					$data->rol         = new stdClass();

					// Set info User
					$user = new Api_Model_DbTable_Users();
					$row = $user->_getInfoUser(array("eid"=>$eid,"uid"=>$data->uid,"pid"=>$data->pid));
					$full = $row[0]['last_name0']." ".$row[0]['last_name1'].", ".$row[0]['first_name'];
					$row[0]['fullname']=$full;
					$data->sex=$row[0]['sex'];
					$data->infouser=$row[0];

					$rols_ = new Api_Model_DbTable_Rol();
					$rol_ = $rols_->_getOne(array("eid"=>$data->eid,"rid"=>$data->rid));
					if ($rol_)
						$data->rol = $rol_;
					else{
						$msg = "Error no se encontro un rol epecifico el usuario";
						$this->_redirect("/error/msg/msg/'$msg'");
					}

					// Register access
					// $clientIp = $this->getRequest()->getClientIp();
					// $log = new Api_Model_DbTable_Logs();
					// $aleatorio = rand(10,100);
					// $datalog['tokenid']= time()+$aleatorio;
					// $data->tokenid=$datalog['tokenid'];
					// $datalog['year']= date('Y');
					// $datalog['ip']= $clientIp;
					// $datalog['eid']= $data->eid;
					// $datalog['oid']= $data->oid;
					// $datalog['uid']= $data->uid;
					// $datalog['pid']= $data->pid;
					// $datalog['rid']= $data->rid;
					// $datalog['datestart']= date(DATE_RFC822);
					// $datalog['dateend']= date(DATE_RFC822);
					// $datalog['state']= 'A';
					// $datalog['keysession']= $datalog['tokenid'];

					// $userAgent = new Zend_Http_UserAgent();
					// $device = $userAgent->getDevice();
					// $datalog['browse'] = $device->getBrowser();
					// $datalog['vbrowser'] = $device->getBrowserVersion();
					// $datalog['browserinfo'] = $device->getUserAgent();
					// if ($log->_save($datalog)){
					// 	$auth->getStorage()->write($data);
					// 	//Verify unique user connect
					// 	$logdata=null;
					// 	$logs = new Api_Model_DbTable_Logs();
					// 	$logdata['eid']=$eid;
					// 	$logdata['oid']=$oid;
					// 	$logdata['uid']=$cod;
					// 	$rlogs = $logs->_getConnect($logdata);
					// 	if (count($rlogs)>2){
					// 		//echo "Existe otra sesion abierta en algun otro lugar";exit();
					// 		//$this->_redirect("/default/index/salir");
					// 	}
					// 	//$passn= base64_encode($clavecampus);
					// 	//$urllogin  = "key/$passn/mod/".$data->rol['module'];
					// 	//$urllogin  = array("key"=>$passn, "mod" => $data->rol['module']);
					// 	//if (trim($data->rid)=='AL' || $data->rid=='DC')
					// 	//  $this->_forward("ajax", "index", "default", $urllogin );
					// 	//else
					// }
					$auth->getStorage()->write($data);
					$urlmod = $data->rol['module'];
					$this->_redirect($urlmod);
				}else {
					switch ($result->getCode()) {
						case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
							$this->view->msgerror="El usuario que ingresó no existe";
							break;
						case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
							$this->view->msgerror="Su contraseña es incorrecta";
							break;
						default:
							$this->view->msgerror="Se produjo un error al ingreso, intentelo nuevamente";
							break;
					}
				}
			}else{
				$form->populate($formData);
			}
		}
		}  catch (Exception $ex){
			print "Error Login access ".$ex->getMessage();
		}
	}

	public function salirAction(){
		$sesion  = Zend_Auth::getInstance();
		$sesion_ = $sesion->getStorage()->read();
		
		// $log = new Api_Model_DbTable_Logs();
		// $data['eid'] =$sesion_->eid;
		// $data['oid']=$sesion_->oid;
		// $data['tokenid']=$sesion_->tokenid;
		// $data['uid']=$sesion_->uid;
		// $data['pid']=$sesion_->pid;
		// $r=$log->_getOne($data);
		// if ($r){
		// 	$datalog['dateend']= date(DATE_RFC822);
		// 	$datalog['state']= 'C';
		// 	$log->_update($datalog,$data);
			Zend_Auth::getInstance()->clearIdentity();
			$this->_redirect("/");
		// }
		// $this->_redirect("/");
	}

}
