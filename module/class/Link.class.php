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
			if($idPages)
			{
				$this->idLink=$ret;
				foreach ($idPages as $key => $value) {
					$ret+=$this->insertDependance($value);
				}
			}
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
		$ret+=$this->deleteDependance($id);
		if($idPages)
		{
			$this->idLink=$id;
			foreach ($idPages as $key => $value) {
				$ret+=$this->insertDependance($value);
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
		// echo $req="INSERT INTO contenir(idPage, idLink) VALUES(".$idPage.", ".$idLink.")";
		// $ret=parent::manualQuery($req);
		if($ret){
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function getPages($idLink){
		$req="SELECT c.idPage as idpage, p.name FROM contenir as c INNER JOIN pages as p ON
			p.id = c.idPage AND 
			c.idLink=".$idLink;
		$ret=parent::manualQuery($req);
		if($ret){
				
			return $ret;
		}
		else
		{
			return false;
		}
	}

	public function deleteJoin($idLink){
		$clause=array('idLink'=>$idLink);
		$ret=parent::delete("contenir", $clause);
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