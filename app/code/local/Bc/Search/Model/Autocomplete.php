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

        $selectedSearchAttributes= empty($selectedSearchAttributes)? array('name'): $selectedSearchAttributes;

        $productCollections = array();
        $maxNumProduct = Mage::helper('autocomplete')->getProductLimit();
        $_countProduct = 0;
        foreach($productDatas as $product){
            if($_countProduct > $maxNumProduct){
                break;
            }
            foreach($selectedSearchAttributes as $attribue){
                if(!in_array($product['entity_id'],$productids)){
                    if(isset($product[$attribue])){
                        if (strpos(strtolower($product[$attribue]),strtolower($searchWords)) !== false) {
                            $productids[] = $product['entity_id'];
                            $_countProduct++;
                            array_push($productCollections,$product);
                        }
                    }
                }
            }
        }

        return $productCollections;
    }

    public function getCollection(){

        $cache = Mage::app()->getCache();

        $_productDatas =  $cache->load("bc_search_ajax");
        if(!$_productDatas){

            $sortAttri = Mage::getStoreConfig('autocomplete/general/sort_attributes');

            $sortAttri = empty($sortAttri)?'name':$sortAttri;

            $products_collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addStoreFilter()
                ->addUrlRewrite()
                ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH)
                ->addAttributeToSort($sortAttri,'asc')
                ->load();

            $_productDatas = array();
            foreach($products_collection as $p){
                $_pData = $p->getData();
                $productUrl = $p->getProductUrl();
                $productThumbnailUrl = $p->getThumbnailUrl(80,80);
                $priceHTML = Mage::helper('core')->currencyByStore($p->getPrice());
                if($p->getTypeID()=='bundle'){
                    $priceHTML = 'From '.Mage::helper('core')->currencyByStore($p->getMinPrice()).' To '. Mage::helper('core')->currencyByStore($p->getMaxPrice());
                }
                $decriptionProduct = substr($p->getShortDescription(), 0,100);

                $_pData = array_merge(array('productUrl' => $productUrl,'priceHTML' => $priceHTML ,'productThumbnailUrl' => $productThumbnailUrl,'decriptionHTML'=>$decriptionProduct),$_pData);
                $_productDatas[$p->getId()] =$_pData;
            }
            $cache->save(json_encode($_productDatas), "bc_search_ajax", array("bc_search_ajax"), false);
        }
        else{
            $_productDatas = json_decode($_productDatas,true);
        }

        $searchedProductCollection = $this->searchProduct($_productDatas);

        return $searchedProductCollection;
    }

}