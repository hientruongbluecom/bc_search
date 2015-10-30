<?php
class Bc_Search_SearchController extends Mage_Core_Controller_Front_Action
{

    public function resultAction() {
        if($this->getRequest()->isAjax()){
            if(Mage::getSingleton('catalog/session')->getLastViewedProductTypeId()){
                Mage::getSingleton('catalog/session')->unsetData(last_viewed_product_type_id);
            }
            if(Mage::getSingleton('catalog/session')->getLastViewedProductBrandId()){
                Mage::getSingleton('catalog/session')->unsetData(last_viewed_product_brand_id);
            }
            $query = Mage::helper('catalogsearch')->getQuery();
            /* @var $query Mage_CatalogSearch_Model_Query */
            $query->setStoreId(Mage::app()->getStore()->getId());

            if($query->getQueryText() != ''){
                if (Mage::helper('catalogsearch')->isMinQueryLength()){
                    $query->setId(0)
                        ->setIsActive(1)
                        ->setIsProcessed(1);
                }
                else{
                    if($query->getId()){
                        $query->setPopularity($query->getPopularity()+2);
                    }else{
                        $query->setPopularity(1);
                    }
                    if($query->getRedirect()){
                        $query->save();
                        $this->getResponse()->setRedirect($query->getRedirect());
                        return;
                    }else{
                        $query->prepare();
                    }
                }
                Mage::helper('catalogsearch')->checkNotes();
                if(!Mage::helper('catalogsearch')->isMinQueryLength()){
                    $query->save();
                }
            }
            $block = $this->getLayout()->createBlock('autocomplete/Suggestionlist')->toHtml();
            $this->getResponse()->setBody($block);
        }
        else{
            $this->_redirect();
        }
    }
}