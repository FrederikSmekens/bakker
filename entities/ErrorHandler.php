<?php

class ErrorHandler {
    
    protected $errors=[];  
    
    public function addError($error, $key = null) 
    {
        if ($key) 
        {
            $this->errors[$key][] = $error;
        } 
        else 
        {
            $this->errors[] = $error;
        }
    }    
    
    //kijkt of er errors zijn en geeft true of false terug
    public function hasErrors() 
    {
        return count($this->alleErrors()) ? true : false;  
    }
    
    //geeft alle errors terug van dde gegeven key
    public function alleErrors($key=null)
    {
         return isset($this->errors[$key]) ? $this->errors[$key]:$this->errors;
    }  
    
    
    public function eersteError($key)
    {
        //als er een error is geef hem terug 
        if(isset($this->alleErrors()[$key][0]))
        {
            return $this->alleErrors()[$key][0];
        }    
    }
 }
