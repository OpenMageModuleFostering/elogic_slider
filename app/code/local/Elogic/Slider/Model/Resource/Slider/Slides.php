<?php
/**
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Model_Resource_Slider_Slides
 */

class Elogic_Slider_Model_Resource_Slider_Slides  extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('elogic_slider/slider_slides', 'id');
    }
}