<?php

class Validatie 
{
    protected $errorHandler;
    protected $rules = ['required','email', 'emailExists'];
    public $messages = 
    [          
        'required' => ':veld is niet ingevuld', 
        'email' => 'Dit is geen geldig e-mailadres',        
        'emailExists' => 'Dit e-mailadres is al in gebruik'
    ];    
    
    public function __construct(ErrorHandler $errorHandler)
    {
        $this->errorHandler = $errorHandler;
    }
    //controlleer of de gegeven items de regels respecteren
    //en zend errors terug in geval van fouten  
    public function check($velden, $rules) 
    {  
        //print_r($rules);exit();
        //
        //$velden = enkelvoudige array
        //vb: Array ( [email] => s [password] => s [voornaam] => s [familienaam] => s [adres] => s [postcode] => s [gemeente] => s [registreer] => registreer )
       
        //$rules = array van arrays
        //vb: Array ( [email] => Array ( [verplicht] => 1 [email] => 1 ) )
     
        
        //overloop alle velden en haal de waarde er uit
        foreach ($velden as $veld => $value) 
            {            
            //print $veld;
            // print $value;
            
            //kijk of  of het veld (bv email) voorkomt in de doorgegeven regels            
            if (in_array($veld, array_keys($rules))) 
                {      
                    $this->validate([
                        'veld' => $veld,
                        'value' => $value,
                        'rules' => $rules[$veld]
                    ]);
                }
        }
        return $this;
    }
       
    public function fails() 
    {
        return $this->errorHandler->hasErrors();
    }
    
    public function errors() 
    {
        return $this->errorHandler;
    }
    
   
    
    protected function validate($item) 
    {
        //print_r($item); exit();
   
        $veld = $item['veld'];
        
        foreach ($item['rules']as $rule=>$satisfier) 
        {
            //print $rule; exit();
          
            if (in_array($rule, $this->rules)) 
            {
                if (!call_user_func_array([$this, $rule], [$veld, $item['value'],$satisfier])) 
                {
                    $this->errorHandler->addError(str_replace([':veld'],[$veld],$this->messages[$rule]),$veld);
                }
            }
        }        
    }

  
    protected function required($veld, $value,$satisfier)
    {
        return !empty(trim($value));
    }
    
    protected function email($veld,$email,$satisfier) 
    {        
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    protected function emailExists($veld,$email)
    {
        //print $email; exit();
        $userSvc = new UserService();     
        return !($userSvc->checkEmail($email));
    }

}
