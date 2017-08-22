<?php

class Api_Model_DbTable_Users extends Zend_Db_Table_Abstract
{
	protected $_name = 'base_users';
	protected $_primary = array("eid","uid","pid");

	public function _save($data){
		try{
			if ($data['eid']=='' || $data['uid']=='' || $data['pid']=='' || $data['password']=='' ) return false;
			return $this->insert($data);
			return false;
		}catch (Exception $e){
				print "Error: Save User ".$e->getMessage();
		}
	}
	
	public function _update($data,$pk){
		try{
			if ($pk['eid']=='' || $pk['pid']==''  || $pk['uid']=='') return false;
			$where = "eid = '".$pk['eid']."' and pid='".$pk['pid']."' and uid='".$pk['uid']."'";
			return $this->update($data, $where);
			return false;
		}catch (Exception $e){
			print "Error: Update User".$e->getMessage();
		}
	}
	
	public function _delete($data){
		try{
			if ($data['eid']=='' || $data['uid']=='' || $data['pid']=='' ) return false;
			$where = "eid = '".$data['eid']."' and uid='".$data['uid']."' and pid='".$data['pid']."'";			
			return $this->delete($where);
			return false;
		}catch (Exception $e){
			print "Error: Delete User".$e->getMessage();
		}
	}
	
	public function _getInfoUser($where=null)
	{
		try{
			if ($where['eid']=="" || $where['uid']=="" || $where['pid']=="") return false;
			
			$select = $this->_db->select()
			->from(array('p' => 'base_person'),array('p.pid','p.last_name0','p.last_name1','p.first_name','p.birthday','p.photografy','p.sex'))
				->join(array('u' => 'base_users'),'u.eid= p.eid and u.pid=p.pid', array('u.uid','u.uid'))
				->where('u.eid = ?', $where['eid'])->where('u.uid = ?', $where['uid'])
				->where('u.pid = ?', $where['pid'])
				->order('last_name0');

			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;
		}catch (Exception $e){
			print "Error: Read UserInfo ".$e->getMessage();
		}
		
	}
	public function _getUserXRidXPid($where=null){
		try{
			if ($where['pid']=="" || $where['rid']=="" || $where['eid']=="" ) return false;
			
			$select = $this->_db->select()
			->from(array('p' => 'base_person'),array('p.pid','p.last_name0','p.last_name1','p.first_name','p.birthday','p.photografy'))
				->join(array('u' => 'base_users'),'u.eid= p.eid and u.pid=p.pid', array('u.uid','u.eid'))
				->where('u.eid = ?', $where['eid'])
				->where('u.pid = ?', $where['pid'])
				->where('u.rid = ?', $where['rid'])
				->order('last_name0');
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;         
		}  catch (Exception $ex){
			print "Error: Obteniendo datos de un usuario de acuerdo a su DNI y a su rol".$ex->getMessage();
		}
	}

	public function _getUserXRidXUid($where=null){
		try{
			if ($where['uid']=="" || $where['rid']=="" || $where['eid']=="" ) return false;
			
			$select = $this->_db->select()
			->from(array('p' => 'base_person'),array('p.pid','p.last_name0','p.last_name1','p.first_name','p.birthday','p.photografy'))
				->join(array('u' => 'base_users'),'u.eid= p.eid and u.pid=p.pid', array('u.uid','u.eid'))
				->where('u.eid = ?', $where['eid'])
				->where('u.uid = ?', $where['uid'])
				->where('u.rid = ?', $where['rid'])
				->order('last_name0');
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;         
		}  catch (Exception $ex){
			print "Error: Obteniendo datos de un usuario de acuerdo a su codigo y a su rol".$ex->getMessage();
		}
	}


	public function _getUserXUid($where=null){
		try{
			if ($where['uid']=="" || $where['eid']=="" ) return false;
			
			$select = $this->_db->select()
				->from(array('p' => 'base_person'),array('p.pid','p.last_name0','p.last_name1','p.first_name','p.birthday','p.photografy','p.sex','p.mail_person','p.mail_work','p.cellular','p.address','p.register','p.created','p.civil','p.phone'))
				->join(array('u' => 'base_users'),'u.eid= p.eid and u.pid=p.pid', array('u.uid','u.eid','u.rid','u.state'))
				->where('u.eid = ?', $where['eid'])
				->where('u.uid = ?', $where['uid'])
				->order('last_name0');
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;         
		}  catch (Exception $ex){
			print "Error: Obteniendo datos de un usuario de acuerdo a su codigo".$ex->getMessage();
		}
	}
	public function _getUserXUid_state($where=null){
		try{
			if ($where['uid']=="" || $where['eid']=="" || $where['state'] =='') return false;
			
			$select = $this->_db->select()
			->from(array('p' => 'base_person'),array('p.pid','p.last_name0','p.last_name1','p.first_name','p.birthday','p.photografy'))
				->join(array('u' => 'base_users'),'u.eid= p.eid and u.pid=p.pid', array('u.uid','u.eid','u.rid','u.state'))
				->where('u.eid = ?', $where['eid'])
				->where('u.uid = ?', $where['uid'])
				->where('u.state = ?', $where['state'])
				->order('last_name0');
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;         
		}  catch (Exception $ex){
			print "Error: Obteniendo datos de un usuario deacuerdo a su codigo y a su estado".$ex->getMessage();
		}
	}
	
	public function _getUsuarioXNombreProAll($where=null){
		try{
			$eid=$where['eid'];
			$cad=$where['name'];
			// print_r($whereds);
		  $sql=$this->_db->query("
				select  last_name0 || ' ' || last_name1 || ' ' || first_name as full_name
						,u.uid, u.rid, u.eid, u.pid, p.first_name, p.last_name0, p.last_name1, u.state 
						from base_users as u
						inner join base_person as p
						on u.pid=p.pid and u.eid=p.eid
						where u.eid='$eid' and upper(last_name0) || ' ' || upper(last_name1) || ', ' || upper(first_name) like '%$cad%'
			");
			$row=$sql->fetchAll();
		   return $row;  
		}catch (Exception $ex) { 
			print "Error: Retornando los datos del alumno de acuerdo a una palabra ingresada".$ex->getMessage();
		}
	}

	public function _getFilter($where=null,$attrib=null,$orders=null){
		try{
			if($where['eid']=='') return false;
				$select = $this->_db->select();
				if ($attrib=='') $select->from("base_users");
				else $select->from("base_users",$attrib);
				foreach ($where as $atri=>$value){
					$select->where("$atri = ?", $value);
				}
				if ($orders<>null || $orders<>"") {
					if (is_array($orders))
						$select->order($orders);
				}
				$results = $select->query();
				$rows = $results->fetchAll();
				if ($rows) return $rows;
				return false;
		}catch (Exception $e){
			print "Error: Read Filter Users ".$e->getMessage();
		}
	}
}

