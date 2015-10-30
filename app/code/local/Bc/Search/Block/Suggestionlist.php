<?php
class Bc_Search_Block_Suggestionlist extends Mage_Catalog_Block_Product_Abstract
{
    public function __construct(){
        $this->setTemplate('bluecom/searchautocomplete/suggestionlist.phtml');
    }

    public function getProductCollection(){

        $query = Mage::helper('catalogsearch')->getQuery();

        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getRedirect()) {

            $query->save();
        } else {
            $query->prepare();
        }
        Mage::helper('catalogsearch')->checkNotes();

        $result = $query->getResultCollection();

        $result->addAttributeToFilter('visibility', array('neq' => 1));

        $attrSearch = Mage::getStoreConfig('autocomplete/general/search_attributes');
        if(!empty($attrSearch)){
            $selectedSearchAttributes = explode(',',$attrSearch);
            $searchArray = array();
            foreach($selectedSearchAttributes as $searchAttrib){
                $searchArray[] = array('attribute' => $searchAttrib, 'like' => '%'.Mage::helper('catalogsearch')->getQuery()->getQueryText().'%');
            }
            $result->addAttributeToFilter($searchArray);
        }
        $result->addAttributeToFilter('visibility', array('neq' => 1));

        $sortAttri = Mage::getStoreConfig('autocomplete/general/sort_attributes');

        if(!empty($sortAttri)){
            $result->addAttributeToSort($sortAttri,'asc');
        }

        $_litmitProduct = $this->getProductLimit();
        if ($_litmitProduct) {

            $result->setPageSize($_litmitProduct);
        } else {
            $result->setPageSize(5);
        }

        return $result;
    }
    public function getProductLimit()
    {
        $productLimit = Mage::getStoreConfig('autocomplete/general/product_limit');
        return $productLimit;
    }

}