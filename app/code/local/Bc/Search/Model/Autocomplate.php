<?php
class Bc_Search_Model_Autocomplete extends Mage_Core_Model_Abstract
{
    protected function _construct(){
        $this->_init('search/autocomplete');
    }

    public function getCollection(){

        $collection = Mage::getResourceModel('catalogsearch/fulltext_collection');

        return $this->_prepareCollection($collection);
    }

    protected function _prepareCollection($collection){

        $collection->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addSearchFilter(Mage::helper('catalogsearch')->getQuery()->getQueryText())
            ->setStore(Mage::app()->getStore())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addStoreFilter()
            ->addUrlRewrite();

        if(!Mage::getStoreConfig('autocomplete/general/show_outstock_products')){

            Mage::getSingleton('cataloginventory/stock_status')->addIsInStockFilterToCollection($collection);

        }

        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInSearchFilterToCollection($collection);

        return $collection;
    }
}