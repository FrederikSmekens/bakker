<?php

class Bestelling{

    public $bestelNr,$klantId,$datum;
    
    public function __construct($bestelNr,$klantId,$datum) 
    {
        $this->BestelNr = $bestelNr;
        $this->KlantId = $klantId;
        $this->Datum = $datum;   
    }
}
