<?php
class Bc_Search_Helper_Data extends Mage_Core_Helper_Abstract{

    public function getAjaxurl() {

        return $this->_getUrl('autocomplete/search/result', array('_secure' => Mage::app()->getFrontController()->getRequest()->isSecure()
        ));
    }
    public function getMinChar()
    {
        $minCharacters = 0;
        $minChar = Mage::getStoreConfig('autocomplete/general/minchar');
        if($minChar != NULL){
            $minCharacters = $minChar;
        }else{
            $minCharacters = 2;
        }
        return $minCharacters;
    }
    public function getNoticeMessage()
    {
        return Mage::getStoreConfig('autocomplete/general/not_found_notice')!=''?Mage::getStoreConfig('autocomplete/general/not_found_notice'):'No results.';
    }
}