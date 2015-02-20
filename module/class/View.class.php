<?php 
	/****/ 
	class View
	{
		
		private $file;

		public function __construct($file)
		{
			$this->file = $file;
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

		public function setFile($file)
		{
			$this->file=$file;
		}
		public function getFile()
		{
			return $this->file;
		}
	}
?>