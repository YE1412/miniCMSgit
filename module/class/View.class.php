<?php 
	/****/ 
	class View
	{
		
		private $file;
		private $files;

		public function __construct($file)
		{
			$this->file = $file;
			$this->files[] = $file;
		}

		public function render($data)
		{
			$callback=function ($matches) use($data)
			{
            		$words = explode(".", $matches[1]);
    				$ret=$data;
            		foreach($words as $word){
            			if(is_array($ret))
            			{
            				$ret=array_key_exists($word, $ret)?$ret[$word]:"";
            			}
            			else
            			{
							return "";
            			}
            		}
            		return $ret;
        	};
			return preg_replace_callback("/(?:{{)((?:[a-z]+)(?:\.(?:[a-z]+))*)(?:}})/", $callback, 
        		file_get_contents($this->file));
		}

		public function renderList($data, $files)
		{
			if($files)
			{
				$this->setFiles($files);
				$retour="";
				$i=0;
				foreach($data as $ind=>$value)
				{	
					
					$this->file=$this->files[$ind];
					//die(var_dump($this->file));
					$retour.=$this->render($value);
					$i++;
				}
				return $retour;
			}
			else
			{
				return 'ERROR ... Render List Must Not Take an Empty Array for The Second Argument';
			}
		}

		public function setFiles($files)
		{
			foreach ($files as $key => $value) {
				$this->files[]=$value;
			}
			$this->file=$this->files[0];
		}
		public function getFile()
		{
			return $this->file;
		}
	}
?>