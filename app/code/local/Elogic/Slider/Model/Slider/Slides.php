<?php
/**
 *
 * @author elogic
 */


/**
 * Class Elogic_Slider_Model_Slider_Slides
 */
class Elogic_Slider_Model_Slider_Slides extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('elogic_slider/slider_slides');
    }

    public function getParams()
    {
        return json_decode($this->getData('params'),true);
    }

    /**
     * @param bool $url
     * @return string
     *
     * get Image Path
     */

    public function getImagePath($url = true)
    {
        $filePath = '';

        if($this->getImage()){
            if($url){
                $filePath = Mage::getBaseUrl('media') . 'elogic/slider/' . $this->getSliderId() . '/' . $this->getId() . '/'. $this->getImage();
            }else{
                $filePath = Mage::getBaseDir('media'). DS . 'elogic' . DS . 'slider' . DS . $this->getSliderId() . DS . $this->getId() . DS  . $this->getImage();
            }
        }

        return $filePath;
    }
}