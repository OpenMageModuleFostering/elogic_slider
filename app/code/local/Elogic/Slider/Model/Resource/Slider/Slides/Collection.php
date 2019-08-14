<?php
/**
 * Slider collection
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Model_Resource_Slider_Slides_Collection
 */
class Elogic_Slider_Model_Resource_Slider_Slides_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * constructor
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('elogic_slider/slider_slides');
    }
}