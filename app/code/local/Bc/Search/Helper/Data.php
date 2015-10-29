<?php
class Bc_Search_Helper_Data extends Mage_Core_Helper_Abstract{

    public function getSuggestUrl() {

        return $this->_getUrl('autocomplete/search/result', array('_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }

    public function getNoticeMessage()
    {
        return Mage::getStoreConfig('autocomplete/general/not_found_notice');
    }
}