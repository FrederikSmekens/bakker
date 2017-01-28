<?php
require_once 'data/productDAO.php';

class ProductService {

      public function getProductenLijst()
      {
          $productDAO = new productDAO();
          $lijst = $productDAO->getProducten();
          return $lijst;                
      }
      public function getProductById($id)
      {
          $productDAO = new productDAO();
          $product = $productDAO->getProductById($id);
          return $product;
      }


}
