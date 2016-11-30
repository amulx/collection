    public function objarray_to_array($obj){
        $ret = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == 'object') {
                $ret[$key] = objarray_to_array($value);
            } else {
                $ret[$key] = $value;
            }
            
        }
        return $ret;
    }
