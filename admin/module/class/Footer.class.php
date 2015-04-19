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

		public function insertFooter($contenu, $logo=null, $published=null, $idPages=false){
			//$cont=new Contenir($this->db);
			$params=array("contenu"=>$contenu, "logo"=>$logo, "published"=>$published);
			$ret=parent::insert("footer", $params);
			if($ret){
				if($idPages)
				{
					$this->idFooter=$ret;
					foreach($idPages as $valuePage)
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

		public function deleteFooter($id){
			$clause=array("id"=>$id);
			$ret=parent::delete("footer", $clause);
			if($ret){
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function updateFooter($id, $contenu, $logo, $published=0, $idPages=false){
			$clause=array('id'=>$id);
			$param=array("contenu"=>$contenu, "logo"=>$logo, "published"=>$published);
			$ret=parent::update("footer", $clause, $param);	
			$ret+=$this->deleteDependance($id);
			if($idPages)
			{
				$this->idFooter=$id;
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

		private function deleteDependance($idFooter){
			$clause=array('idFooter'=>$idFooter);
			$param=array('idFooter'=>null);
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
			$param=array('idFooter'=> $this->idFooter);
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