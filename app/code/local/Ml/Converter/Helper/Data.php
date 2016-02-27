<?php
/**
 * Created by PhpStorm.
 * User: lbond
 * Date: 27.02.16
 * Time: 14:12
 */ 
class Ml_Converter_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Strip tags recursively
     *
     * @param array $data
     * @return array
     */
    public function stripTagsRecursivelly(array $data)
    {
        if (count($data) !== 0) {
            foreach ($data as $item) {
                if (is_string($item)) {
                    $this->stripTags($item);
                } elseif (is_array($item)) {
                    $this->stripTagsRecursivelly($item);
                }

            }
        }

        return $data;
    }
}