<?php

/**
 * Slider admin block
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Block_Adminhtml_Slider
 */
class Elogic_Slider_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    protected function _construct()
    {
        parent::_construct();

        $this->_blockGroup = 'elogic_slider';
        $this->_controller = 'adminhtml_slider';
        $this->_headerText = Mage::helper('elogic_slider')->__('Sliders');

    }

    /**
     * create url
     *
     * @return mixed
     */
    public function getCreateUrl()
    {
        return $this->getUrl('elogic_slider/adminhtml_slider/new');
    }
}