<?php defined ('_YEXEC')  or  die();

class yBase {
	public $owner;
        
    public function __call($func_name, $args) {
                $vars_args = get_class_vars(get_class($this));
                $key_args = array_keys($vars_args);
                $functionName = substr($func_name,0,3);
                $propertyName = lcfirst(substr($func_name,3));
                $propertyValue = $args[0];
                
                if(!in_array($propertyName, $key_args)){
                    return NULL;
                };
                
                switch ($functionName){
                    case 'get':
                        return $this->$propertyName;
                    case 'set':
                        $this->$propertyName = $propertyValue;
                        return $this;
                    default: 
                        return NULL;
                }
        }
		
	public function setProperty($key, $value) {
		$this->$key = $value;
		return $this;
	}
}

?>