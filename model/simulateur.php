<?php
class Simulateur
{
	private $uid = "";
	private $dimensions = array();
	private $feux = array();
	private $deja_brules = array();
	
	public function __construct()
    {
        
    }
	
	public function getUid()
    {
        return $this->uid;
    }
	
	public function startNewSimulation(array $dimensions,array $feux)
    {
        $this->uid = uniqid();
        $this->dimensions = $dimensions;
        $this->feux = $feux;
		$this->save();
    }
	
	public function getDimensions(){
		return $this->dimensions;
	}
	
	public function getFeux(){
		return $this->feux;
	}
	
	public function getDejaBrules(){
		return $this->deja_brules;
	}
	private function save(){
		$next_step = $this->getNextStep();
		
		$dimensions_content = "[dimensions][".implode(",",$this->dimensions)."]";
		$feux_contents = "[feux]";
		foreach($this->feux as $feu){
			$feux_contents .= "[".implode(",",$feu)."]";
		}
		$deja_brules_contents = "[deja_brules]";
		foreach($this->deja_brules as $deja_brule){
			$deja_brules_contents .= "[".implode(",",$deja_brule)."]";
		}
		
		$content = $dimensions_content."\r\n".$feux_contents."\r\n".$deja_brules_contents;
		file_put_contents("simulations/".$this->uid."-".$next_step.".txt", $content);
	}
	
	public function load(string $uid){
		$this->uid = $uid;
		$next_step = $this->getCurrentStep();
		
		$lines = explode("\r\n",file_get_contents("simulations/".$this->uid."-".$next_step.".txt"));
		foreach($lines as $line){
			if(substr($line,0,12) == "[dimensions]"){
				$tags = ["[dimensions]","[","]"];
				$empty = ["","",""];
				$this->dimensions = explode(",",str_replace($tags,$empty,$line));
			}elseif(substr($line,0,6) == "[feux]"){
				$tags = ["[feux]","][","[","]"];
				$empty = [""," ","",""];
				$tab = explode(" ",str_replace($tags,$empty,$line));				
				foreach($tab as $location){
					if($location != ""){
						$this->feux[] = explode(",",$location);
					}
				}
			}elseif(substr($line,0,3) == "[deja_brules]"){
				$tags = ["[deja_brules]","][","[","]"];
				$empty = [""," ","",""];
				$tab = explode(" ",str_replace($tags,$empty,$line));				
				foreach($tab as $location){
					if($location != ""){
						$this->deja_brules[] = explode(",",$location);
					}
				}
			}
		}	
	}
	
	private function getCurrentStep(){
		$current_step = "";
		$files = scandir("simulations");
		
		foreach($files as $file){
			if(!in_array($file,[".",".."])){
				$name_parts = explode("-",$file);
				if($name_parts[0] == $this->uid){
					$step = explode(".",$name_parts[1])[0];
					if(intval($current_step==""?"000":$current_step) <= $step){
						$current_step = $step;
					}
				}
			}
		}
		return $current_step;
	}
	
	private function getNextStep(){
		$current_step = $this->getCurrentStep();
		$next_step = "000";
		if($current_step != ""){
			$next_step = str_pad(intval($current_step)+1,3,"0", STR_PAD_LEFT);
		}
		return $next_step;
	}
	
	
	public function etapeSuivante(int $probabilite){
		$feux_avant = [];
		$nouv_feux = [];
		$brules = [];
		
		
		foreach($this->feux as $feu){
			$feux_avant[$feu[0]] = intval($feu[1]);
		}
		foreach($this->deja_brules as $deja_brule){
			$brules[$deja_brule[0]][] = intval($deja_brule[1]);
		}
		foreach($feux_avant as $key => $value){
			if($key - 1 >= 0){
				$allumage = rand(1,100) <= $probabilite;
				if($allumage && !isset($brules[$key - 1][$value])){
					$nouv_feux[$key - 1][] = $value;
				}				
			}
			if($key + 1 < $this->dimensions[0]){
				$allumage = rand(1,100) <= $probabilite;
				if($allumage && !isset($brules[$key + 1][$value])){
					$nouv_feux[$key + 1][] = $value;
				}				
			}
			if($value - 1 >= 0){
				$allumage = rand(1,100) <= $probabilite;
				if($allumage && !isset($brules[$key][$value - 1])){
					$nouv_feux[$key][] = $value - 1;
				}				
			}
			if($value + 1 < $this->dimensions[1]){
				$allumage = rand(1,100) <= $probabilite;				
				if($allumage && !isset($brules[$key][$value + 1])){
					$nouv_feux[$key][] = $value + 1;
				}				
			}
			$brules[$key][] = $value;
		}
		$feux = [];
		$deja_brules = [];
		foreach($nouv_feux as $key => $values){
			foreach($values as $value){
				$feux[] = [$key,$value];
			}
		}
		foreach($brules as $key => $values){
			foreach($values as $value){
				$deja_brules[] = [$key,$value];
			}
		}
        $this->feux = $feux;
        $this->deja_brules = $deja_brules;
		
		$this->save();
	}
}	