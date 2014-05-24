<?php namespace FormObject;

use Collection\StringDictionary;

class Attributes extends StringDictionary{

    public $rowDelimiter = " ";
    public $keyValueDelimiter = '=';
    public $prefix = '';
    public $suffix = '';

    public static function valueEncode($string){
        $search = array('"','&');
        $replace = array('&quot;','&amp;');
        return trim(strip_tags(str_replace($search,$replace,$string)));
    }

    public function __toString(){
        $rows = array();
//         var_dump($this);
//         try{
            foreach($this as $key=>$value){
                $rows[] = "{$key}{$this->keyValueDelimiter}\"" . self::valueEncode("$value") . '"';
            }
//         }
//         catch(\Exception $e){
//             echo "HHIIIAAA: ".$e->getMessage() . $e->getLine().$e->getFile();
//         }
        return $this->prefix . implode($this->rowDelimiter, $rows) . $this->suffix;
    }

}