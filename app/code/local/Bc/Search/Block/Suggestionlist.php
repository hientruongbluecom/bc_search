<?php
class Bc_Search_Block_Suggestionlist extends Mage_Catalog_Block_Product_Abstract
{
    public function __construct(){
        $this->setTemplate('bluecom/searchautocomplete/suggestionlist.phtml');
    }

    public function getProductCollection(){

        $collection = Mage::getModel('autocomplete/autocomplete')->getCollection();
        return $collection;
    }

}