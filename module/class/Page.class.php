<?php
	class Page extends DB{
		private $db;
		
		public function __construct($db){
			parent::__construct($db);
			$this->db=$db;
		}

		public function getAllPages()
		{
			$reponse = parent::select('pages');
			if(count($reponse)>0){
				return $reponse;
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

		public function insertNewPage($title, $name, $url, $published=0, $links=array(), $Header=false, $Footer=false){
			$params=array("title"=>$title, "name"=>$name, "url"=>$url, "published"=>$published);
			$ret=parent::insert("pages", $params);
			if($links)
			{	
				foreach($links as $value){
					$dependances=array("idPage"=>$ret, "idLink"=>$value);
					$ret+=parent::insert("contenir", $dependances);
				}
			}
			if($Header)
			{	
				$clause=array("id"=>$ret);
				$dependance=array("idHeader"=>$Header);
				$ret+=parent::update("pages", $clause, $dependance);
			}
			if($Footer)
			{
				$clause=array("id"=>$ret);
				$dependance=array("idFooter"=>$Footer);
				$ret+=parent::update("pages", $clause, $dependance);
			}
			if($ret){
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

		public function updatePage($id, $title, $name, $url, $published=0, $links=array(), $Header=false, $Footer=false){
			$clause=array('id'=>$id);
			$param=array("title"=>$title, "name"=>$name, "url"=>$url, "published"=>$published);
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
				$ret=parent::update("pages", $clause, $dependance);
			}
			if($Footer){
				$clause=array("id"=>$id);
				$dependance=array("idFooter"=>$Footer);
				$ret=parent::update("pages", $clause, $dependance);
			}
			if($ret){
				return $ret;
			}
			else
			{
				return false;
			}
		}

		/*public function getDependanceLinks($idPage){
			$req="SELECT c.idLink, l.url FROM contenir as c INNER JOIN links as l ON
			l.id = c.idLink AND 
			c.idPage=".$idPage;
			$ret=parent::manualQuery($req);
			if($ret){
				
				return $ret;
			}
			else
			{
				return false;
			}
		}*/

		/*public function getDependanceHeader($idPage){
			$req="SELECT c.idHeader, h.contenu FROM contenir as c INNER JOIN header as h ON
			h.id = c.idHeader AND 
			c.idPage=".$idPage;
			$ret=parent::manualQuery($req);
			if($ret){
				
				return $ret;
			}
			else
			{
				return false;
			}
		}*/

		/*public function getDependanceFooter($idPage){
			$req="SELECT c.idFooter, f.contenu FROM contenir as c INNER JOIN links as f ON
			f.id = c.idFooter AND 
			c.idPage=".$idPage;
			$ret=parent::manualQuery($req);
			if($ret){
				
				return $ret;
			}
			else
			{
				return false;
			}
		}*/

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