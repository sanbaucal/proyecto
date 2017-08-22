<?php

class Api_Model_DbTable_Examperson extends Zend_Db_Table_Abstract
{
	protected $_name = 'base_exam_person';
	protected $_primary = array("eid","pid","epid");
	protected $_secuency = 's_exam_person';

	public function _save($data){
		try{
			if ($data['eid']=='' || $data['pid']=='' ) return false;
			return $this->insert($data);
			return false;
		}catch (Exception $e){
				print "Error: Save exam person ".$e->getMessage();
		}
	}
	
	public function _update($data,$pk){
		try{
			if ($pk['pid']=='' || $pk['eid']=='' || $pk['epid']=='' ) return false;
			$where = "eid = '".$pk['eid']."' and pid = '".$pk['pid']."' and epid = '".$pk['epid']."'";
			return $this->update($data, $where);
			return false;
		}catch (Exception $e){
			print "Error: Update exam person ".$e->getMessage();
		}
	}
	
	public function _delete($data=null){
		try{
			if ($data['pid']==''|| $data['eid']==''|| $data['epid']=='') return false;
			$where = "eid = '".$data['eid']."' and pid = '".$data['pid']."' and epid = '".$data['epid']."'";
			return $this->delete($where);
			return false;
		}catch (Exception $e){
			print "Error: Delete exam person ".$e->getMessage();
		}
	}
	
	public function _getOne($where=array()){
		try{
			if ($where['eid']=="" || $where['pid']=="" || $where['epid']=="" ) return false;
			$wherestr="eid = '".$where['eid']."' and pid = '".$where['pid']."'  and epid = '".$where['epid']."'";
			$row = $this->fetchRow($wherestr);
			if($row) return $row->toArray();
			return false;
		}catch (Exception $e){
			print "Error: Read One exam person ".$e->getMessage();
		}
	}

	public function _getFilter($where=null,$attrib=null,$orders=null){
		try{
			if($where['eid']=='') return false;
				$select = $this->_db->select();
				if ($attrib=='') $select->from("base_exam_person");
				else $select->from("base_exam_person",$attrib);
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
			print "Error: Read Filter exam person ".$e->getMessage();
		}
	}
}
