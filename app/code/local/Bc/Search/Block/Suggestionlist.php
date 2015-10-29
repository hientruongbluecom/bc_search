<?php
class Bc_Search_Block_Suggestionlist extends Mage_Core_Block_Template
{

    public function _prepareLayout() {

        return parent::_prepareLayout();
    }


    public function suggestListProduct() {

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

        if (Mage::getStoreConfig('autocomplete/general/product_limit')) {

            $result->setPageSize(Mage::getStoreConfig('autocomplete/general/product_limit'));
        } else {
            $result->setPageSize(5);
        }
        $result->addAttributeToSelect('sku');
        $result->addAttributeToSelect('description');
        $result->addAttributeToSelect('short_description');
        $result->addAttributeToSelect('name');
        $result->addAttributeToSelect('thumbnail');
        $result->addAttributeToSelect('small_image');
        $result->addAttributeToSelect('url_key');

        return $result;
    }
}