<?php
class Bc_Search_Block_Suggestionlist extends Mage_Catalog_Block_Product_Abstract
{
    public function __construct(){
        $this->setTemplate('bluecom/searchautocomplete/suggestionlist.phtml');
    }

    public function getProductCollection(){

        $collection = Mage::getModel('autocomplete/autocomplete')->getCollection();

        /* Start: Filter by Attribute */
        $attrSearch = Mage::getStoreConfig('autocomplete/general/search_attributes');
        if(!empty($attrSearch)){
            $selectedSearchAttributes = explode(',',$attrSearch);
            $searchArray = array();
            foreach($selectedSearchAttributes as $searchAttrib){
                $searchArray[] = array('attribute' => $searchAttrib, 'like' => '%'.Mage::helper('catalogsearch')->getQuery()->getQueryText().'%');
            }
            $collection->addAttributeToFilter($searchArray);
        }
        $sortAttri = Mage::getStoreConfig('autocomplete/general/sort_attributes');

        if(!empty($sortAttri)){
            $collection->addAttributeToSort($sortAttri,'asc');
        }


        $collection->getSelect()->limit($this->getProductLimit());
        return $collection;
    }
    public function getProductLimit()
    {
        $productLimit = Mage::getStoreConfig('autocomplete/general/product_limit');
        return $productLimit;
    }

}