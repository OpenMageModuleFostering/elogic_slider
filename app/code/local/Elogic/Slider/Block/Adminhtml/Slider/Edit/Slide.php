<?php
/**
 * Slider admin template container
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Block_Adminhtml_Slider_Edit_Slide
 */
class Elogic_Slider_Block_Adminhtml_Slider_Edit_Slide extends Mage_Adminhtml_Block_Template
{

    protected $_slide = null;
    protected $_slider;
    protected $_uniqId = null;

    /**
     * set template
     */
    protected function _construct()
    {
        $this->setTemplate('elogic/slider/slide.phtml');
        parent::_construct();
    }

    /**
     * @param $slide
     */

    public function setSlide($slide)
    {
        $this->_slide = $slide;
    }

    public function getSlide()
    {
        return $this->_slide;
    }

    public function setSlider($slider)
    {
        $this->_uniqId = null;
        $this->_slider = $slider;
    }

    /**
     * @return mixed
     */

    public function getSlider()
    {
        if (!$this->_slider) {
            $this->_slider = Mage::registry('elogic_slider_data');
        }

        return $this->_slider;
    }

    /**
     * @return null|string
     */

    public function getUniqId()
    {
        if ($this->getSlide()) {
            $this->_uniqId = $this->getSlide()->getId();
        }
        if (!$this->_uniqId) {
            $this->_uniqId = 'new' . uniqid();
        }

        return $this->_uniqId;
    }

    /**
     * @return string html
     */

    public function getSlideRow()
    {
        $rowHtml = '<li class="slide-row" id="' . $this->getUniqId() . '"';

        if ($slide = $this->getSlide()) {
            $rowHtml .= ' data-id="' . $slide->getId() . '"><i class="icon-move"></i>';
            $rowHtml .= $slide->getTitle() ?: $this->__('Slide %s', $slide->getId());
        } else {
            $rowHtml .= '><i class="icon-move"></i>' . $this->__('New Slide');
        }

        $rowHtml .= '<i class="icon-delete"> - </i>';
        $rowHtml .= '</li>';
        return $rowHtml;
    }
}