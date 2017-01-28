<?php

//data/UserDAO.php

require_once 'DBConfig.php';
require_once 'entities/Product.php';

class productDAO {

    public function getProducten()
    {
        $sql = "SELECT * FROM producten";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $resultset = $dbh->query($sql);
        $lijst = array();

        foreach ($resultset as $rij) 
        {
            $product = new Product($rij["ProductId"], $rij["Product"], $rij["Prijs"]);
            array_push($lijst, $product);
        }
        $dbh = null;
        return $lijst;
    }
    
    public function getProductById($id)
    {
        //print $id;
        //haalt product met prijs uit db
        $sql = "SELECT * 
                FROM producten 
                WHERE ProductId=:ProductId";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(
            ':ProductId' => $id
        ));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);
        $product = new Product($rij["ProductId"],$rij["Product"],$rij["Prijs"]);
        $dbh = null;
        return $product;
    }

}

//einde class broodDAO
