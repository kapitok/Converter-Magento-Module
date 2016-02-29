<?php
/**
 * Created by PhpStorm.
 * User: lbond
 * Date: 27.02.16
 * Time: 14:29
 */

class Ml_Converter_Model_Converter extends Mage_Core_Model_Abstract
{
    const API_SECRET_KEY    = '46170ad9514fd40a8f3f2535b6ceb9c9';
    const API_END_POINT     = 'http://devel.farebookings.com/api/curconversor';
    const API_RESPONSE_TYPE = 'json';

    /** @var array $_currencies */
    protected $_currencies = [];

    /** @var string $fromСurrency */
    protected $_fromCurrency;
    /** @var string $toСurrency */
    protected $_toCurrency;
    /** @var double $amount */
    protected $_amount;

    /**
     * Init currencies
     */
    public function _construct()
    {
        parent::_construct();

        /** @var array _currencies */
        $this->_currencies = [
            'PLN' => 'PLN',
            'RUB' => 'RUB',
            'USD' => 'USD',
        ];
    }

    public function getAllCurrencies()
    {
        return $this->_currencies;
    }

    /**
     * Process of convertation
     *
     * @return string
     */
    public function convert()
    {
        // initialize CURL:
        $ch = curl_init (
            self::API_END_POINT
            . '/' . $this->getFromCurrency()
            . '/' . $this->getToCurrency()
            . '/' . $this->getAmount()
            . '/' . self::API_RESPONSE_TYPE
        );

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the (still encoded) JSON data:
        $json = curl_exec($ch);

        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        $result = $this->_parseResponse($conversionResult);

        if ($result) {
            return $result;
        }

        return '';

    }

    /**
     * Parse response
     *
     * @param $response
     * @return string
     */
    protected function _parseResponse($response)
    {
        if ($response[$this->_toCurrency]) {
            return $response[$this->_toCurrency];
        }
        return '';
    }

    /**
     * Validate parameters
     *
     * @return bool
     */
    public function validate()
    {
        $errors = array();

        /** @var Ml_Converter_Helper_Data $helper */
        $helper = Mage::helper('ml_converter');

        if (!Zend_Validate::is($this->getFromCurrency(), 'NotEmpty')) {
            $errors[] = $helper->__('Currency From can not be empty.');
        }

        if (!in_array($this->getFromCurrency(), $this->getAllCurrencies())) {
            $errors[] = $helper->__('Currency From is not valid.');
        }

        if (!Zend_Validate::is($this->getToCurrency(), 'NotEmpty')) {
            $errors[] = $helper->__('Currency From can not be empty.');
        }

        if (!in_array($this->getToCurrency(), $this->getAllCurrencies())) {
            $errors[] = $helper->__('Currency To is not valid.');
        }

        if (!Zend_Validate::is($this->getAmount(), 'NotEmpty')) {
            $errors[] = $helper->__('Amount can not be empty.');
        }

        if (empty($errors) || $this->getShouldIgnoreValidation()) {
            return true;
        }
        return $errors;
    }

    //TODO: Use magic method __set, __get
    public function setFromCurrency($value)
    {
        if ($value) {
            $this->_fromCurrency = $value;
        }
        return $this;
    }

    public function setToCurrency($value)
    {
        if ($value) {
            $this->_toCurrency = $value;;
        }
        return $this;
    }

    public function setAmount($value)
    {
        if ($value) {
            $this->_amount = $value;
        }
        return $this;
    }

    public function getFromCurrency()
    {
        return $this->_fromCurrency;
    }

    public function getToCurrency()
    {
        return $this->_toCurrency;
    }

    public function getAmount()
    {
        return $this->_amount;
    }

}