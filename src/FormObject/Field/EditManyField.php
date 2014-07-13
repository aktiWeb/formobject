<?php namespace FormObject\Field;

use Collection\Iterator\CastableIterator;
use Collection\Map\Extractor;
use Collection\ColumnList;
use FormObject\Field;
use FormObject\Attributes;
use OutOfBoundsException;
use FormObject\Form;
use IteratorAggregate;
use Iterator;
use ArrayAccess;
use BadMethodCallException;
use DomainException;
use FormObject\Validator\ProxyValidatorAdapter;

class EditManyField extends Field implements Iterator, ArrayAccess{

    protected $src;

    protected $manualExtractor;

    protected $columns;

    protected $itemForm;

    protected $iteratorPosition = 0;

    protected $currentForm;

    protected $itemForms;

//     public function __construct($name=NULL, $title=NULL){
//         parent::__construct($name, $title);
// 
//         $this->manualExtractor = new Extractor(Extractor::KEY, Extractor::VALUE);
//     }

    public function getItemForm(){
        return $this->itemForm;
    }

    public function setItemForm(Form $form){
        $this->itemForm = $form;
        return $this;
    }

    public function getFields(){
        return $this->itemForm->fields;
    }

    public function getColumns(){
        return $this->columns;
    }

    public function setColumns($columns){
        $this->columns = $columns;
        return $this;
    }

    public function updateAttributes(Attributes $attributes){

        parent::updateAttributes($attributes);

        try{
            unset($attributes['value']);
        }
        catch(OutOfBoundsException $e){

        }

    }

    public function setValue($value){
        if(!$value){
            $this->value = array();
        }
        else{
            $this->value = $value;
        }
        return $this;
    }

    public function rewind(){
        $this->iteratorPosition = 0;
//         print_r($this->value); die();
    }

    public function current(){
        if($this->currentForm === NULL){
            $this->currentForm = $this->createForm($this->iteratorPosition);
        }
        return $this->currentForm;
    }

    public function key(){
        return $this->iteratorPosition;
    }

    public function valid(){
        return isset($this->value[$this->iteratorPosition]);
    }

    public function next(){
        $this->currentForm = NULL;
        $this->iteratorPosition++;
    }

    public function isMultiple(){
        return TRUE;
    }

    protected function createForm($idx){

        if(!$this->itemForm){
            throw new DomainException("Assign a itemForm via setItemForm before using EditManyField");
        }

        $itemForm = $this->itemForm->copy();

        $srcAdapter = $this->itemForm->getValidatorAdapter();

        $proxyAdapter = new ProxyValidatorAdapter($itemForm, $srcAdapter);

        $itemForm->setValidatorAdapter($proxyAdapter);

        $values = array();

        foreach($this->itemForm->dataFields as $field){

            $fieldName = $field->getName();

            if($this->columns){
                if(!in_array($fieldName, $this->columns)){
                    continue;
                }
            }

            $copy = $field->copy();
            $fieldName = $field->getName();
            $newFieldName = $this->name . "[$this->iteratorPosition][$fieldName]";

            $proxyAdapter->map($fieldName, $newFieldName);

            $copy->setName($newFieldName);

            if(isset($this->value[$this->iteratorPosition]) && isset($this->value[$this->iteratorPosition][$fieldName])){
                $values[$newFieldName] = $this->value[$this->iteratorPosition][$fieldName];
            }

            $itemForm->fields->push($copy);
        }

        if($form = $this->getForm()){
            if($form->wasSubmitted()){

                // Fake a Form submit
                foreach($this->itemForm->actions as $action){
                    $values[$action->getAction()] = $action->getValue();
                }
                $itemForm->fillByRequestArray($values);
            }
            else{
                $itemForm->fillByArray($values);
            }
        }

        return $itemForm;

    }

    protected function getOrCreateForm($idx){
        if(!isset($this->itemForms[$idx])){
            if(!isset($this->value[$idx])){
                throw new OutOfBoundsException("No data for key $idx");
            }
            $this->itemForms[$idx] = $this->createForm($idx);

        }
        return $this->itemForms[$idx];
    }

    public function offsetGet($offset){
        if(is_numeric($offset)){
            return $this->getOrCreateForm($offset);
        }
    }

    public function offsetSet($offset, $value){
        
    }

    public function offsetUnset($offset){
        
    }

    public function offsetExists($offset){
        
    }

}
