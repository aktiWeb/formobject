<?php namespace FormObject\Support\Laravel;

use FormObject\Validator\ValidatorAdapterInterface;
use FormObject\Form;

class ValidatorAdapter implements ValidatorAdapterInterface{

    protected $validator;
    protected $form;

    public function __construct(Form $form, $validator){
        $this->validator = $validator;
        $this->form = $form;
    }

    public function getValidator(){
        return $this->validator;
    }

    public function setValidator($validator){
        $this->validator = $validator;
        return $this;
    }

    public function hasErrors($fieldName){
        if(!$this->form->needsValidation()){
            return FALSE;
        }
        if($messages = $this->validator->messages()){
            return (bool)count($messages->get($fieldName));
        }
        return FALSE;
    }

    public function getMessages($fieldName){
        if(!$this->form->needsValidation()){
            return array();
        }
        else{
            if($messages = $this->validator->messages()){
                return $messages->get($fieldName);
            }
        }
        return array();
    }

    public function getRuleNames($fieldName){

        $ruleNames = array();

        foreach($this->validator->getRules() as $key=>$rules){
            if($key == $fieldName){
                foreach($rules as $rule){
                    $ruleNames[] = $this->ruleToName($rule);
                }
                break;
            }
        }
        return $ruleNames;
    }

    protected function ruleToName($rule){
        return preg_replace('/[^a-zA-Z0-9]+/u', '-', $rule);
    }

} 
