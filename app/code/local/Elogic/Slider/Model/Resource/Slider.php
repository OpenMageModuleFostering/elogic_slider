<?php
/**
 * Slider item resource model
 *
 * @author elogic
 */


/**
 * Class Elogic_Slider_Model_Resource_Slider
 */
class Elogic_Slider_Model_Resource_Slider extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */

    protected function _construct()
    {
        $this->_init('elogic_slider/slider', 'id');
    }
}