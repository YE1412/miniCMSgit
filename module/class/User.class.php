<?php 	
		/**
		* 
		*/
		class User extends DB 
		{
			private $data, $id;
			
			public function __construct($dbFile)
			{
				$this->loadData($dbFile);
			}
			
			public function loadData($dbFile)
			{
				$this->data = include($dbFile);
			}
			
			public function display()
			{
				print_r($this->data);
			}

			public function login($login, $password)
			{
				foreach ($this->data as $key => $value) {
					if($value['login']===$login && $value['pass']===$password) {
						$this->id = $key;
						return $value;
					}
				}
				return false;
			}

			public function setPassword($password)
			{
				if($password) 
				$this->data[$this->id]['pass'] = $password;
			}

			public function getLogin()
			{
				return $this->login;
			}
		}
?>