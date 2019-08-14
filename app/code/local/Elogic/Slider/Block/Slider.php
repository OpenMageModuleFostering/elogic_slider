<?php
/**
 * Slider block
 *
 * @author elogic
 *
 */

/**
 * Class Elogic_Slider_Block_Slider
 */
class Elogic_Slider_Block_Slider extends Mage_Core_Block_Template
{
    /**
     * @var null
     */
    protected $_sliderId = null;
    protected $_slider = null;

    /**
     * default speed slider
     */
    const default_speed = '5000';


    public function setSliderId($sliderId)
    {
        $this->_sliderId = (int)$sliderId;
    }

    /**
     * slider collection
     *
     * @return Elogic_Slider_Model_Resource_Slider_Collection
     */

    public function getSlider()
    {
        if (!$this->_slider && !empty($this->_sliderId)) {
            $collection = Mage::getModel('elogic_slider/slider')->getCollection()
                ->addFieldToFilter('store_id', array('eq' => Mage::app()->getStore()->getId()))
                ->addFieldToFilter('id', array('eq' => $this->_sliderId))
                ->addFieldToFilter('is_active', array('eq' => 1));
            if ($collection) {
                $this->_slider = $collection->getFirstItem();
            }
        }

        return $this->_slider;
    }

    /**
     * speed slider
     *
     * @return mixed|string
     */


    public function speedSlider()
    {
        $speed_slider = Mage::getStoreConfig('elogic_slider/slider/speed');
        if ($speed_slider) {
            return $speed_slider;
        } else {
            return self::default_speed;
        }
    }
}
