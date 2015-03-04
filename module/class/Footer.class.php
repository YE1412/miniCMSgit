<?php
	class Footer extends DB{
		private $db;
		private $idFooter;

		public function __construct($db){
			$this->db=$db;
			parent::__construct($db);
		}

		public function getAllFooters()
		{
			$reponse = parent::select('footer');
			if(count($reponse)>0){
				foreach ($reponse as $key => $value) {
					//$dep = array();
					// $dep['links']=$this->getDependance($value['id']);
					// $reponse[$key]['links']= $dep['links'];
				}
				//print_r($reponse);
				return $reponse;
			}
			else
			{
				return false;
			}
		}

		public function insertFooter($contenu, $logo=null, $published=null){
			//$cont=new Contenir($this->db);
			$params=array("contenu"=>$contenu, "logo"=>$logo, "published"=>$published);
			$ret=parent::insert("footer", $params);
			if($ret){
				/*$this->idLink=$ret;
				if($idPages && is_array($idPages)):
					foreach ($idPages as $key => $value) {
						$ret+=$this->insertDependance($value);
					}
				endif;*/
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function getDependance($idFooter){
			$clause=array("idFooter"=>$idFooter);
			$ret=parent::select("links", $clause, "id, url");
			if($ret){
				
				return $ret;
			}
			else
			{
				return false;
			}
		}
		public function getLinks()
		{
			$link=new Link($this->db);
			return $link->getAllLinks();		
		}

		public function linkIsInFooter($idLink, $idFooter)
		{
			$clause=array("idLink"=>$idLink, "idFooter"=>$idHeader);
			$ret=parent::select("contenir", $clause);
			if($ret){
				
				return true;
			}
			else
			{
				return false;
			}	
		}
	}
?>