<?php
	class Link extends DB{
	private $db;
	private $idLink;
	
	public function setIdLink($idLink){
		$this->idLink=$idLink;
	}
	public function getIdLink(){
		return $this->idLink;
	}

	public function __construct($db){
		$this->db=$db;
		parent::__construct($db);
	}

	public function getAllLinks()
	{
		$reponse = parent::select('links');
		if(count($reponse)>0){
			//print_r($reponse);
			return $reponse;
		}
		else
		{
			return false;
		}
	}

	public function insertNewLink($url, $desc=null, $published=0, $idPages=false){
		$params=array("url"=>$url, "description"=>$desc, "published"=>$published);
		$ret=parent::insert("links", $params);
		if($ret){
			/*if($idPages)
			{
				$this->idLink=$ret;
				foreach ($idPages as $key => $value) {
					$ret+=$this->insertDependance($value);
				}
			}*/
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

	public function updateLink($id, $url, $desc, $published=0, $idPages=false){
		$clause=array('id'=>$id);
		$param=array("url"=>$url, "description"=>$desc, "published"=>$published);
		$ret=parent::update("links", $clause, $param);
		/*$ret+=$this->deleteDependance($id);
		if($idPages)
		{
			$this->idLink=$id;
			foreach ($idPages as $key => $value) {
				$ret+=$this->insertDependance($value);
			}
		}*/
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	private function deleteDependance($idLink){
		$cont=new Contenir($this->db);
		$ret=$cont->deleteJoin($idLink);
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
}

trait insertLinkAndDependance{
	public function insertNewLink($url, $desc=null, $published=0, $idPages=false){
		$ret=parent::insertNewLink($url, $desc, $published);
		if($ret){
			if($idPages)
			{
				parent::setIdLink($ret);
				foreach ($idPages as $key => $value) {
					$param = array("idPage"=>$value, "idLink"=>$this->getIdLink());
					$ret += parent::insert("contenir", $param);
				}
			}
			return $ret;
		}
		else
		{
			return false;
		}
	}
}

trait updateLinkAndDependance{
	public function updateLinkDep($id, $url, $desc=null, $published=0, $idPages=false){
		$ret=parent::updateLink($id, $url, $desc, $published);
		$clause=array('idLink'=>$id);
		$ret+=parent::delete("contenir", $clause);
		if($idPages)
		{
			parent::setIdLink($id);
			foreach ($idPages as $key => $value) {
				$param = array("idPage"=>$value, "idLink"=>$this->getIdLink());
				$ret += parent::insert("contenir", $param);
			}
		}
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
	use insertLinkAndDependance, updateLinkAndDependance;
	public function insertLink($url, $desc=null, $published=0, $idPages=false){
		$ret=$this->insertNewLink($url, $desc, $published, $idPages);
		return $ret ? $ret : false ;
	}
	
	public function updateLink($id, $url, $desc=null, $published=0, $idPages=false){
		$ret=$this->updateLinkDep($id, $url, $desc, $published, $idPages);
		return $ret ? $ret : false ;
	}
	/*public function deleteJoin($idLink){
		$clause=array('idLink'=>$idLink);
		$ret=parent::delete("contenir", $clause);
		if($ret){
				
			return $ret;
		}
		else
		{
			return false;
		}
	}*/
}
?>