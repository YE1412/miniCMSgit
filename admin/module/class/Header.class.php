<?php
	class Header extends DB{
		private $db;
		private $idHeader;

		public function __construct($db){
			$this->db=$db;
			parent::__construct($db);
		}

		public function getAllHeaders()
		{
			$reponse = parent::select('header');
			if(count($reponse)>0){
				return $reponse;
			}
			else
			{
				return false;
			}
		}

		public function insertHeader($contenu, $logo=null, $published=null, $idPage=false){
			//$cont=new Contenir($this->db);
			$params=array("contenu"=>$contenu, "logo"=>$logo, "published"=>$published);
			$ret=parent::insert("header", $params);
			if($ret){
				if($idPage)
				{
					$this->idHeader=$ret;
					foreach($idPage as $valuePage)
					{
						$ret=$this->insertDependance($valuePage);	
					}
				}
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function deleteHeader($id){
			$clause=array("id"=>$id);
			$ret=parent::delete("header", $clause);
			if($ret){
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function updateHeader($id, $contenu, $logo, $published=0, $idPages=false){
			$clause=array('id'=>$id);
			$param=array("contenu"=>$contenu, "logo"=>$logo, "published"=>$published);
			$ret=parent::update("header", $clause, $param);	
			$ret+=$this->deleteDependance($id);
			if($idPages)
			{
				$this->idHeader=$id;
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

		private function deleteDependance($idHeader){
			$clause=array('idHeader'=>$idHeader);
			$param=array('idHeader'=>null);
			$ret=parent::update("pages", $clause, $param);
			if($ret){
				return $ret;
			}
			else
			{
				return false;
			}
		}

		private function insertDependance($idPage){
			$clause=array('id'=> $idPage);
			$param=array('idHeader'=> $this->idHeader);
			$ret=parent::update("pages", $clause, $param);
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