<?php
class Bc_Search_SearchController extends Mage_Core_Controller_Front_Action{

    public function resultAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
}