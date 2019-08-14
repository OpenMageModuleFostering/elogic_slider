<?php
/**
 * Slider Data helper
 */

/**
 * Class Elogic_Slider_Helper_Data
 */
class Elogic_Slider_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @return mixed
     *
     * get Id slider
     * config setup system
     */

    public function getHomeSliderId()
    {
        return Mage::getStoreConfig('elogic_slider/slider/slider_id');
    }
}