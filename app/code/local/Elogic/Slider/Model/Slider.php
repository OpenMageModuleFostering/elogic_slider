<?php

/**
 * Slider item model
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Model_Slider
 */
class Elogic_Slider_Model_Slider extends Mage_Core_Model_Abstract
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const DEFAULT_SLIDES_LIMIT = 5;
    const CONFIG_SLIDES_LIMIT = 'elogic_slider/slider/limit';

    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('elogic_slider/slider');
    }

    /**
     * @return mixed
     *
     * collection slides
     */

    public function getSlides()
    {
        if($this->getData('slides') == null){
            $collectionSlides = Mage::getModel('elogic_slider/slider_slides')->getCollection()
                ->addFieldToFilter('slider_id',array('eq'=> $this->getId()))
                ->setOrder('position','ASC');

            $this->setData('slides',$collectionSlides);
        }

        return $this->getData('slides');
    }

    /**
     * @param int $storeId
     * @return int
     */

    public function getSlidesLimit($storeId = 0)
    {
        $maxSlides = Mage::app()->getConfig(self::CONFIG_SLIDES_LIMIT,$storeId);
        return $maxSlides?: self::DEFAULT_SLIDES_LIMIT;
    }
}