<?php
class DB{
		private $datas;
		private $host='localhost';
		private $bdd;
		private $util='root';
		private $pass='';

		public function __construct($Db_name){
			$this->bdd = new PDO('mysql:host='.$this->host.';dbname='.$Db_name, $this->util, $this->pass, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO:: FETCH_ASSOC,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
			));
		}

		public function select($table, $clause=false, $col='*'){
			$reqString='SELECT '.addslashes($col).' FROM '.addslashes($table);
			if($clause){
				$i=0;
				foreach ($clause as $key => $value) {
					$reqString.=  $i==0 ?' WHERE '.$key.' = :'.$key : ' AND '.$key.' = :'.$key;
					$i++;
				}
			}
			$req=$this->bdd->prepare($reqString);
			$req->execute($clause ? $clause : array());
			return $req->fetchAll();
		}

		public function insert($table, $col){
			if($col)
			{
				$i=0;
				$reqStringFirst='';
				$reqStringSecond='';
				foreach ($col as $key => $value) {
						$reqStringFirst.=  $i==0 ? $key : ', '.$key;
						$reqStringSecond.=  $i==0 ? ':'.$key : ', :'.$key;
						$i++;
				}
				return "INSERT INTO ".$table."(".$reqStringFirst.") VALUES(".$reqStringSecond.")";
			}
			else
			{
				return "Aucun paramètre";
			}
		}
	}
?>
