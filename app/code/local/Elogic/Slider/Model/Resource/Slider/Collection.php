<?php
/**
 * Slider collection
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Model_Resource_Slider_Collection
 *
 */
class Elogic_Slider_Model_Resource_Slider_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('elogic_slider/slider');
    }
}