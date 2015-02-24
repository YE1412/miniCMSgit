<?php
	class Page extends DB{
		public function __construct($db){
			parent::__construct($db);
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

		public function insertNewPage($title, $name, $url, $published=0){
			$params=array("title"=>$title, "name"=>$name, "url"=>$url, "published"=>$published);
			$ret=parent::insert("pages", $param);
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
	}
?>