<?php
class ConfigLoader
{
	private $dimensions = array();
	private $probabilite = 100;
	private $departs = array();
	
	public function __construct()
    {
        $this->loadConfig();
    }
	

	public function loadConfig(){
		$lines = explode("\r\n",file_get_contents("config.txt"));
		foreach($lines as $line){
			if(substr($line,0,12) == "[dimensions]"){
				$tags = ["[dimensions]","[","]"];
				$empty = ["","",""];
				$this->dimensions = explode(",",str_replace($tags,$empty,$line));
			}elseif(substr($line,0,13) == "[probabilite]"){
				$this->probabilite = str_replace("[probabilite]","",$line);				
			}elseif(substr($line,0,9) == "[departs]"){
				$tags = ["[departs]","][","[","]"];
				$empty = [""," ","",""];
				$tab = explode(" ",str_replace($tags,$empty,$line));				
				foreach($tab as $location){
					if($location != ""){
						$this->departs[] = explode(",",$location);
					}
				}
			}
		}	
	}
	
	public function getDimensions(){
		return $this->dimensions;
	}
	
	public function getProbabilite(){
		return $this->probabilite;
	}
	
	public function getDeparts(){
		return $this->departs;
	}
	
}	