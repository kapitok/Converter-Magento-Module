<?php
/**
 * Created by PhpStorm.
 * User: lbond
 * Date: 27.02.16
 * Time: 14:19
 */

class Ml_Converter_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sendRequestAction()
    {
        if (! $this->_validateFormKey()) {
            Mage::getSingleton('customer/session')->addError($this->__('Form key is not valid'));
        }

        $isAjax = Mage::app()->getRequest()->isAjax();
        if ($isAjax) {
            $request = $this->getRequest()->getParams();

            /** @var Ml_Converter_Model_Converter $converter */
            $converter = Mage::getModel('ml_converter/converter');

            $converter
                ->setFromCurrency($request['currency_from'])
                ->setToCurrency($request['currency_to'])
                ->setAmount($request['amount']);

            if ($converter->validate() === true) {
                $this->getResponse()->setHeader('Content-type', 'application/json');
                $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(array('result' => $converter->convert())));
            }
        }

    }

}