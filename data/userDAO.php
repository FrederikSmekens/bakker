<?php

//data/UserDAO.php

require_once 'DBConfig.php';
require_once 'entities/User.php';

class UserDAO
{  
  
    public function getAllUsers()
    {   //$klantId,$email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente
        $sql ="SELECT * FROM users";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultSet = $dbh->query($sql);
        $allUsers = array();
        foreach($resultSet as $rij)
        {
            $user = User::create($rij["klantId"],$rij["email"], $rij["password"], $rij["voornaam"], $rij["familienaam"], $rij["adres"], $rij["postcode"], $rij["gemeente"],$rij["rang"]);
 
            array_push($allUsers, $user);
        }
       
        $dbh = null;
        return $allUsers;
    }

    //registreren gebruiker
    //gegevens:$email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente
    public function registreerUser($email,$password,$voornaam,$familienaam,$adres,$postcode,$gemeente){
     
        $passwordhash = password_hash($password, PASSWORD_DEFAULT);  
        $sql = "INSERT INTO users (email,password,voornaam,familienaam,adres,postcode,gemeente)
                values (:email,:password,:voornaam,:familienaam,:adres,:postcode,:gemeente)";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        
        $stmt->execute(array(            
          ':email'=>$email,
          ':password'=>  $passwordhash,
          ':voornaam'=>$voornaam,
          ':familienaam'=>$familienaam,
          ':adres'=>$adres,
          ':postcode'=>$postcode,
          ':gemeente'=>$gemeente,
          ));          
     
        $user = $this->checkLogin($email, $password);                           //check als de toevoeging succesvol is gebeurd door de nieuwe gegevens te gebruiken voor login
        $dbh = null;
        return $user;  
     
    } 
    
        public function checkLogin($email,$password){
        // Kijken als de inloggegevens kloppen, zoja worden de gegevens van die gebruiker doorgegeven
        // $username, $password      
         
        $sql = "SELECT klantId,email,password FROM users 
                WHERE email = :email";         
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array(':email' => $email));       
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);                                  //haalt de rij als resultaat van de query uit de DB
        $dbPassword = $rij['password'];
        $isJuisteWachtwoord = password_verify($password, $dbPassword);   
        
        if($isJuisteWachtwoord == false)                                                          
        {                                                                      
            return $login = false;                                              //login is mislukt
        }
        else
        { 
            $user = $this->makeLoginSession($email);
            return $user; //doorgeven van het object voor verder gebruik
        }
        
        $dbh = null; //verbreken van connectie met DB
    }
    
    public function updateUser($email,$klantId, $voornaam, $familienaam, $adres, $postcode, $gemeente)
    {       
        $sql ="UPDATE users
               SET voornaam=:voornaam, familienaam=:familienaam, adres=:adres, postcode=:postcode, gemeente=:gemeente
               WHERE klantId=:klantId
              ";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':klantId' => $klantId,            
            ':voornaam' => $voornaam,
            ':familienaam' => $familienaam,
            ':adres' => $adres,
            ':postcode' => $postcode,
            ':gemeente' => $gemeente  
        
        ));      
        //print $password; exit();
        return $this->makeLoginSession($email);   //sessie bijwerken
       
        $dbh = null;      
    }
    
    public function makeLoginSession($email)
    {          
         
        $sql = "SELECT klantId,email,voornaam,familienaam,adres,postcode,gemeente,rang FROM users 
                WHERE email = :email";         
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array(':email' => $email));       
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);                                  //haalt de rij als resultaat van de query uit de DB
        
        //maakt nieuw object user aan met al zijn gegevens uit de rij apart doorgegeven 
        $user = new user($rij["klantId"],$rij["email"], $rij["voornaam"], $rij["familienaam"], $rij["adres"], $rij["postcode"], $rij["gemeente"],$rij["rang"]);
        return $user;      
        
    }
    
    public function updateRang($klantId,$rang)
    {
        $sql ="UPDATE users
               SET rang=:rang
               WHERE klantId=:klantId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':klantId' => $klantId, 
            ':rang' => $rang));
       
        
        $dbh = null;
        
    }
    
    public function checkEmail($email)
    {
        $sql = "SELECT email FROM users WHERE email =:email";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql); 
        $stmt->execute(array(':email' => $email));       
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);                                  //haalt de rij als resultaat van de query uit de DB
        
        
        if($rij)
        {   
//            print_r($rij);
//            print 'true'; exit();
            return true;
        }
        else
        {
//            print_r($rij);
//            print 'false'; exit();
            return false;
        }
        
    }
    
   
} //einde class userDAO
