<?php
class DB{
		private $datas;
		private $host='localhost';
		private $bdd;
		private $pass='';
		private $util='root';
	
		public function __construct($Db_name){
			$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$Db_name, $this->util, $this->pass, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			));
		}

		public function select($table, $clause=false, $col='*'){
			$reqString='SELECT '.addslashes($col).' FROM '.addslashes($table);
			//die(var_dump($clause));
			if($clause){
				$i=0;
				foreach ($clause as $key => $value) {
					$reqString.=  $i==0 ?' WHERE '.$key.' = :'.$key : 'AND '.$key.' = :'.$key;
					$i++;
				}
			}
			$req=$this->bdd->prepare($reqString);
			if($clause)
			{
				$req->execute($clause?);	
			}
			else
			{

			}
			return $req->fetchAll();
		}

		public function insert($table, $datas=false, $col='*'){
			$reqString='INSERT INTO('.addslashes($col).') FROM '.addslashes($table);
			//die(var_dump($clause));
			if($datas){
				$i=0;
				foreach ($clause as $key => $value) {
					$reqString.=  $i==0 ?' WHERE '.$key.' = :'.$key : 'AND '.$key.' = :'.$key;
					$i++;
				}
			}
			else
			{
				return false;
			}
			/*$req=$this->bdd->prepare($reqString);
			$req->execute($clause);*/
			return $reqString;
			//return $req->fetchAll();
		}
	}
?>