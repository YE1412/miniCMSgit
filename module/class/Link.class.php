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
				return $reponse;
			}else{
				return false;
			}
		}

		public function insertNewLink($url, $desc=null, $published=0, $idPages=null, $idHeader=null, $idFooter=null){
			$cont=new Contenir($this->db);
			$params=array("url"=>$url, "description"=>$desc, "published"=>$published, "idHeader"=>$idHeader, "idFooter"=>$idFooter);
			$ret=parent::insert("links", $params);
			if($ret){
				$this->idLink=$ret;
				$ret+=$cont->insertLink($idPages, $this->idLink);
				return $ret;
			}else{
				return false;
			}
		}

		public function deleteLink($id){
			$clause=array("id"=>$id);
			$ret=parent::delete("pages", $clause);
			if($ret){
				return $ret;
			}else{
				return false;
			}
		}

		public function updatePage($id, $title, $name, $url, $published=0){
			$clause=array('id'=>$id);
			$param=array("title"=>$title, "name"=>$name, "url"=>$url, "published"=>$published);
			$ret=parent::update("pages", $clause, $param);
			if($ret){
				return $ret;
			}else{
				return false;
			}
		}

		public function insertDependance($idPage){
			$cont=new Contenir($this->db);
			$ret=$cont->insertLink($idPage, $this->idLink);
			if($ret){
				return $ret;
			}else{
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
			}else{
				return false;
			}
		}
	}
?>