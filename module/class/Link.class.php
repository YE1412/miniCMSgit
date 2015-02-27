<?php
	class Link extends DB{
	private $db;
	private $idLink;
	
	public function __construct($db){
		$this->db=$db;
		parent::__construct($db);
	}

	public function getAllLinks()
	{
		$reponse = parent::select('links');
		if(count($reponse)>0){
			foreach ($reponse as $key => $value) {
				$dep = array();
				$dep['pages']=$this->getDependance($value['id']);
				$reponse[$key]['pages']= $dep['pages'];
			}
			//print_r($reponse);
			return $reponse;
		}
		else
		{
			return false;
		}
	}

	public function insertNewLink($url, $desc=null, $published=0, $idPages=null, $idHeader=null, $idFooter=null){
		$cont=new Contenir($this->db);
		$params=array("url"=>$url, "description"=>$desc, "published"=>$published, "idHeader"=>$idHeader, "idFooter"=>$idFooter);
		$ret=parent::insert("links", $params);
		if($ret){
			$this->idLink=$ret;
			if($idPages && is_array($idPages)):
				foreach ($idPages as $key => $value) {
					$ret+=$this->insertDependance($value);
				}
			endif;
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function deleteLink($id){
		$clause=array("id"=>$id);
		$ret=parent::delete("links", $clause);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function updateLink($id, $url, $desc, $published=0, $idHeader=null, $idFooter=null){
		$clause=array('id'=>$id);
		$param=array("url"=>$url, "description"=>$desc, "published"=>$published, "idHeader"=>$idHeader, "idFooter"=>$idFooter);
		$ret=parent::update("links", $clause, $param);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	private function insertDependance($idPage){
		$cont=new Contenir($this->db);
		$ret=$cont->insertLink($idPage, $this->idLink);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function getDependance($idLink){
		$cont=new Contenir($this->db);
		$ret=$cont->getPages($idLink);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}
}
class Contenir extends Link{

	public function insertLink($idPage, $idLink){
		$param=array("idPage"=>$idPage, "idLink"=>$idLink);
		$ret=parent::insert("contenir", $param);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function getPages($idLink){
		$clause=array("idLink"=>$idLink);
		$ret=parent::select("contenir", $clause);
		if($ret){
			
			return $ret;
		}
		else
		{
			return false;
		}
	}
}
?>