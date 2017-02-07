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

      public function addProduct($product,$prijs)
      {
          $productDAO = new productDAO();
          $productDAO->addProduct($product,$prijs);
      }
      
      public function deleteProduct($productId)
      {
          $productDAO = new productDAO();
          $productDAO->deleteProduct($productId);
      }
}
