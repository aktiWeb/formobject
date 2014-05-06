<?php namespace FormObject\Field;

use Collection\Iterator\CastableIterator;
use Collection\Map\Extractor;

class BooleanRadioField extends BooleanField implements Selectable{

    const TRUE_FALSE = 1;
    const FALSE_TRUE = 2;

    protected $src;

    protected $manualExtractor;

    public $trueString = 'Yes';

    public $falseString = 'No';

    public $order = self::TRUE_FALSE;

    public function __construct($name=NULL, $title=NULL){
        parent::__construct($name, $title);

        $this->manualExtractor = new Extractor(Extractor::KEY, Extractor::VALUE);
    }

    public function isItemSelected(SelectableProxy $item){
        $key = $item->getKey();
        if($key == 0){
            if($this->value){
                return FALSE;
            }
            else{
                return TRUE;
            }
        }
        elseif($key == 1){
            if($this->value){
                return TRUE;
            }
            else{
                return FALSE;
            }
        }
    }

    public function getSrc(){
        if($this->order == self::TRUE_FALSE){
            $src = array( 1 => $this->trueString, 0 => $this->falseString);
        }
        else{
            $src = array($this->falseString, $this->trueString );
        }
        return $src;
    }

    public function setSrc($src, $extractor=NULL){
        $this->src = $src;
        if(!is_null($extractor)){
            $this->manualExtractor = $extractor;
        }
        return $this;
    }

    public function getIterator(){
        return SelectableHelper::createIterator($this->getSrc(),
                                                $this,
                                                $this->manualExtractor);
    }

    public function isMultiple(){
        return FALSE;
    }

}
