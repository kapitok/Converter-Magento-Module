<?php
/**
 * Created by PhpStorm.
 * User: lbond
 * Date: 27.02.16
 * Time: 14:20
 */
class Ml_Converter_Block_Form extends Mage_Core_Block_Template
{
    /**
     * Get request action
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('ml_converter/index/sendRequest');
    }

    /**
     * Get all allowed currencies
     *
     * @return array
     */
    public function getCurrencies()
    {
        return Mage::getModel('ml_converter/converter')->getAllCurrencies();
    }
}