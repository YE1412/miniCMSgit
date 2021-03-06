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
		public function manualQuery($query){
			$req=$this->bdd->prepare($query);
			$req->execute();
			return $req->fetchAll();
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
				$sql="INSERT INTO ".$table."(".$reqStringFirst.") VALUES(".$reqStringSecond.")";
				$req=$this->bdd->prepare($sql);
				$req->execute($col);
				if($table!='contenir')
				{
					return $this->bdd->lastInsertId();
				}
				return $req->rowCount();
			}
			else
			{
				return "Aucun paramètre";
			}
		}

		public function update($table, $clause=false, $apply){	
			if($apply)
			{
				$i=0;
				$reqString="UPDATE ".$table;
				foreach ($apply as $key => $value) {
					$reqString.=  $i==0 ?' SET '.$key.' = :'.$key : ', '.$key.' = :'.$key;
					$i++;
				}
				if($clause)
				{
					$i=0;
					$reqString.=" WHERE ";
					foreach ($clause as $key => $value) {
						$reqString.=  $i==0 ? $key.' = :'.$key : ' AND '.$key.' = :'.$key;
						$i++;
					}
				}
				//print_r(array_merge($apply, $clause));
				$req=$this->bdd->prepare($reqString);
				$req->execute($clause ? array_merge($apply, $clause) : $apply);
				return $req->rowCount();
			}
			else
			{
				return "Aucun paramètre";
			}
		}

		public function delete($table, $clause=1){
			$req='DELETE FROM '.$table.' WHERE ';
			if(is_array($clause))
			{
				$i=0;
				foreach ($clause as $key => $value) {
						$req.=  $i==0 ? $key.' = :'.$key : 'AND '.$key.' = :'.$key;
						$i++;
				}
			}
			elseif($clause==1)
			{
				$req.=$clause;
			}
			else
			{
				return "Aucun paramètre";
			}
			$req=$this->bdd->prepare($req);
			$req->execute(is_array($clause) ? $clause : array());

			/*Mise à jour de l'auto-increment*/
			if($table!='contenir')
			{
				$this->resetNumberIdBase($table);
			}
			return $req->rowCount();
		}

		private function resetNumberIdBase($table){
			$req='ALTER TABLE '.$table;
			$id=$this->select($table, $clause=false, $col='MAX(id) as id');
			if($id)
			{
				$req.=' auto_increment = '.($id[0]['id']+1);
			}
			else
			{	
				$req.=' auto_increment = 1';
			}
			$req=$this->bdd->prepare($req);
			$req->execute();
			//return $req->rowCount();
		}
	}
?>
