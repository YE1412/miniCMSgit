<?php
	class Page extends DB{
		private $db;
		private $idPage;


		public function __construct($db){
			parent::__construct($db);
			$this->db=$db;
		}

		public function setIdPage($id){
			$this->idPage=$id;
		}

		public function getIdPage(){
			return $this->idPage;
		}

		public function getHeader()
		{
			$req="SELECT p.idHeader, h.logo, h.contenu FROM pages as p INNER JOIN header as h ON
			h.id = p.idHeader AND 
			p.id=".$this->idPage;
			$ret=parent::manualQuery($req);
			if($ret){	
				return $ret;
			}
			else
			{
				return false;
			}
		}
		public function getFooter()
		{
			$req="SELECT p.idFooter, f.logo, f.contenu FROM pages as p INNER JOIN footer as f ON
			f.id = p.idFooter AND 
			p.id=".$this->idPage;
			$ret=parent::manualQuery($req);
			if($ret){	
				return $ret;
			}
			else
			{
				return false;
			}
		}
		
		public function getAllPages()
		{
			$req="SELECT * FROM pages ORDER BY orderDisplay ASC";
			$reponse = parent::manualQuery($req);
			if(count($reponse)>0){
				return $reponse;
			}else{
				return false;
			}
		}

		public function getAllPagesOnline(){
			$req="SELECT * FROM pages WHERE published=1 
				ORDER BY orderDisplay";
			$ret=parent::manualQuery($req);
			if($ret){	
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function updateOrder($idPage, $order){
			$clause=array('id'=>$idPage);
			$param=array('orderDisplay'=>$order);
			$ret=parent::update("pages", $clause, $param);
			if($ret){
				return $ret;
			}else{
				return false;
			}
		}

		public function updateHashTable($idPage, $arrayData)
		{
			$existingHashArray=array();
			$updatedHashJson="";
			$this->setIdPage($idPage);
			$clause=array('id'=>$this->idPage);
			$retour=0;
			foreach($arrayData as $key => $value)
			{
				$existingHashArray[]=$value;
			}
			$updatedHashJson=json_encode($existingHashArray);
			$param=array("hashtable"=>$updatedHashJson);
			$retour+=parent::update("pages", $clause, $param);
			return $retour;
		}

		public function addItemHashTable($idPage, $arrayData)
		{
			$existingHashArray=array();
			$updatedHashJson="";
			$this->setIdPage($idPage);
			$clause=array('id'=>$this->idPage);
			$ret=parent::select("pages", $clause, "hashtable");
			$retour=0;
			if($ret):
				$existingHashJson=$ret[0]["hashtable"];
				$existingHashArray=json_decode($existingHashJson, true);
				$existingHashArray[]=$arrayData;
				$updatedHashJson=json_encode($existingHashArray);
				$param=array("hashtable"=>$updatedHashJson);
				$retour+=parent::update("pages", $clause, $param);
			else:
				$existingHashArray[]=$arrayData;
				$updatedHashJson=json_encode($existingHashArray);
				$param=array("hashtable"=>$updatedHashJson);
				$retour+=parent::update("pages", $clause, $param);
			endif;
			print_r($existingHashArray);
			return $retour;
		}

		public function getHashTable($idPage)
		{
			$this->setIdPage($idPage);
			$clause=array('id'=>$this->idPage);
			$ret=parent::select("pages", $clause, "hashtable");
			if($ret):
				$existingHashJson=$ret[0]["hashtable"];
				$existingHashArray=json_decode($existingHashJson, true);
			endif;
			if(isset($existingHashArray)):
				return $existingHashArray;
			else:
				return false;
			endif;
		}

		public function deleteItemHashTable($idPage, $idItem)
		{
			$this->setIdPage($idPage);
			$clause=array('id'=>$this->idPage);
			$ret=parent::select("pages", $clause, "hashtable");
			if($ret):
				$existingHashJson=$ret[0]["hashtable"];
				$existingHashArray=json_decode($existingHashJson, true);
			endif;
			if(isset($existingHashArray)):
				$newHashJson="";
				foreach($existingHashArray as $key=>$val):
					if($idItem == $val['item']['id'])
					{
						unset($existingHashArray[$key]); 
					}
				endforeach;
				
				$newHashJson=json_encode($existingHashArray);
				$param=array("hashtable"=>$newHashJson);
				$retour=parent::update("pages", $clause, $param);
				//return print_r($existingHashArray);
				return $retour;
			else:
				return false;
			endif;
		}


		public function getPage(){
			$clause=array("id"=>$this->idPage);
			$ret=parent::select("pages", $clause);
			if(count($ret)>0){
				return $ret;
			}else{
				return false;
			}
		}

		public function getLinks()
		{
			$link=new Link($this->db);
			return $link->getAllLinks();		
		}

		public function getHeaders()
		{
			$header=new Header($this->db);
			return $header->getAllHeaders();		
		} 

		public function getFooters()
		{
			$footer=new Footer($this->db);
			return $footer->getAllFooters();		
		} 

		public function insertNewPage($title, $name, $contenu="", $url, $published=0, $links=array(), $Header=false, $Footer=false, $imageName){
			$params=array("title"=>$title, "name"=>$name, "contenu"=>$contenu, "url"=>$url, "published"=>$published, "image"=>$imageName);
			$ret=parent::insert("pages", $params);
			if($links)
			{	
				foreach($links as $value){
					$dependance=array("idPage"=>$ret, "idLink"=>$value);
					$retLinks=parent::insert("contenir", $dependance);
				}
			}
			if($Header)
			{	
				$clause=array("id"=>$ret);
				$dependance=array("idHeader"=>$Header);
				$retHeader=parent::update("pages", $clause, $dependance);
			}
			if($Footer)
			{
				$clause=array("id"=>$ret);
				$dependance=array("idFooter"=>$Footer);
				$retFooter=parent::update("pages", $clause, $dependance);
			}
			if($ret){
				$clause=array("id"=>$ret);
				$dependance=array("orderDisplay"=>$ret);
				$retOrder=parent::update("pages", $clause, $dependance);
				return $ret;
			}else{
				return false;
			}
		}

		public function deletePage($id){
			$clause=array("id"=>$id);
			$ret=parent::delete("pages", $clause);
			if($ret){
				return $ret;
			}else{
				return false;
			}
		}

		public function updatePage($id, $title, $name, $contenu=null, $url, $published=0, $links=false, $Header=false, $Footer=false, $imageName){
			$clause=array('id'=>$id);
			$param=array();
			if(is_array($contenu)):
				$param=array("title"=>$title, "name"=>$name, "contenu"=>$contenu[0], "url"=>$url, "published"=>$published, "image"=>$imageName);	
			else:
				$param=array("title"=>$title, "name"=>$name, "contenu"=>$contenu, "url"=>$url, "published"=>$published, "image"=>$imageName);
			endif;
			$ret=parent::update("pages", $clause, $param);
			if($links)
			{
				$clause=array('idPage'=>$id);
				$ret+=parent::delete("contenir", $clause);
				foreach ($links as $key => $value) {
					$dependance=array("idPage"=>$id, "idLink"=>$value);
					$ret+=parent::insert("contenir", $dependance);
				}
			}
			if($Header)
			{
				$clause=array("id"=>$id);
				$dependance=array("idHeader"=>$Header);
				$ret+=parent::update("pages", $clause, $dependance);
			}
			if($Footer){
				$clause=array("id"=>$id);
				$dependance=array("idFooter"=>$Footer);
				$ret+=parent::update("pages", $clause, $dependance);
			}
			if($ret){
				return $ret;
			}
			else
			{
				return false;
			}
		}

		public function linkIsInPage($idLink, $idPage)
		{
			$clause=array("idLink"=>$idLink, "idPage"=>$idPage);
			$ret=parent::select("contenir", $clause);
			if($ret){
				
				return true;
			}
			else
			{
				return false;
			}	
		}

		public function headerIsInPage($idHeader, $idPage)
		{
			$clause=array("idHeader"=>$idHeader, "id"=>$idPage);
			$ret=parent::select("pages", $clause);
			if($ret){
				
				return true;
			}
			else
			{
				return false;
			}	
		}

		public function footerIsInPage($idFooter, $idPage)
		{
			$clause=array("idFooter"=>$idFooter, "id"=>$idPage);
			$ret=parent::select("pages", $clause);
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