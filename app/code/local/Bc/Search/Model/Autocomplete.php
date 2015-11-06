<?php
class Bc_Search_Model_Autocomplete extends Mage_Core_Model_Abstract
{
    protected function _construct(){
        $this->_init('autocomplete/autocomplete');
    }

    public function searchProduct($productDatas){

        $searchWords = Mage::helper('catalogsearch')->getQuery()->getQueryText();

        $productids = array();

        $searchAttributes = Mage::getStoreConfig('autocomplete/general/search_attributes');

        $selectedSearchAttributes = explode(',',$searchAttributes);

        $selectedSearchAttributes= empty($selectedSearchAttributes)? array('name','description','meta_keyword','meta_description'): $selectedSearchAttributes;

       foreach($productDatas as $product){
            foreach($selectedSearchAttributes as $attribue){
                if(isset($product[$attribue])){

                    if (strpos(strtolower($product[$attribue]),strtolower($searchWords)) !== false) {
                        $productids[] = $product['entity_id'];
                    }
                }
            }
        }
       $productids = array_unique($productids);
       $productids = empty($productids)? null :$productids;
       $productCollections= Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*')->addFieldToFilter('entity_id',array('in'=>array($productids)))->setPageSize(Mage::helper('autocomplete')->getProductLimit());
        return $productCollections;
    }

    public function getCollection(){

        $cache = Mage::app()->getCache();

        $_productDatas =  $cache->load("bc_search_ajax");
        if(!$_productDatas){

            $products_collection = Mage::getModel('catalog/product')->getCollection();

            $_productDatas = array();
            foreach($products_collection as $prod){
                $product = Mage::getModel('catalog/product')->load($prod->getId());
                $_productDatas[$prod->getId()] = $product->getData();
            }
            $cache->save(json_encode($_productDatas), "bc_search_ajax", array("bc_search_ajax"), 60*60);
        }
        else{
            $_productDatas = json_decode($_productDatas,true);
        }

        $searchedProductCollection = $this->searchProduct($_productDatas);

        return $searchedProductCollection;
    }

}