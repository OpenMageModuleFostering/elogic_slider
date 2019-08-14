<?php
/**
 * Slider admin edit container
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Block_Adminhtml_Slider_Edit_Container
 */
class Elogic_Slider_Block_Adminhtml_Slider_Edit_Container extends Mage_Adminhtml_Block_Widget_Form_Container
{
    protected $_slider;
    protected $_slides = null;
    protected $_formKey = null;

    /**
     * Elogic_Slider_Block_Adminhtml_Slider_Edit_Container constructor.
     */

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('elogic/slider/container.phtml');
        $this->_slider = Mage::registry('elogic_slider_data');
        $this->_updateButton('delete', 'label', Mage::helper('elogic_slider')->__('Delete Slider'));
        $this->_updateButton('save', 'onclick', '');
        $this->_updateButton('save', 'class', 'save-slider');
        $this->_removeButton('reset');
        $this->_formKey = Mage::getSingleton('core/session')->getFormKey();
    }

    /**
     * @return Mage_Core_Block_Abstract
     */

    protected function _prepareLayout()
    {
        $this->setChild('edit_content_form',
            $this->getLayout()->createBlock('elogic_slider/adminhtml_slider_edit_form')
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */

    public function getContentFormHtml()
    {
        return $this->getChildHtml('edit_content_form');
    }

    /**
     * @return string
     */
    protected function _getHeader()
    {
        return Mage::helper('elogic_slider')->__("Slider Content");
    }

    /**
     * URL delete
     * @return mixed
     * @throws Exception
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', array($this->_objectId => $this->getRequest()->getParam($this->_objectId)));
    }

    /**
     * Add slide URL
     *
     * @return mixed
     * @throws Exception
     */

    public function getAddSlideUrl()
    {
        return $this->getUrl('*/*/addSlide', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'form_key' => $this->_formKey));
    }

    /**
     * Uppload Thumb URL
     *
     * @return mixed
     * @throws Exception
     */

    public function getUploadThumbUrl()
    {
        return $this->getUrl('*/*/uploadThumb', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'form_key' => $this->_formKey));
    }

    /**
     * SAve Slider URL
     *
     * @return mixed
     * @throws Exception
     */

    public function getSaveSliderUrl()
    {
        return $this->getUrl('*/*/saveSlider', array($this->_objectId => $this->getRequest()->getParam($this->_objectId), 'form_key' => $this->_formKey));
    }

    /**
     * @return mixed
     */
    public function getSlider()
    {
        return $this->_slider;
    }


    /**
     * Slides
     *
     */
    public function getSlides()
    {
        if (!$this->_slides) {
            $slides = $this->getSlider()->getSlides();

            if ($slides) {
                $slideBlock = $this->getLayout()->createBlock('elogic_slider/adminhtml_slider_edit_slide');

                foreach ($slides as $slide) {
                    $slideBlock->setSlide($slide);
                    $this->_slides[] = array(
                        'row' => $slideBlock->getSlideRow(),
                        'html' => $slideBlock->toHtml()
                    );
                }
            }
        }
        return $this->_slides;
    }

    /**
     * @return mixed
     */

    public function getJsonSliderConfig()
    {
        $config = array('slides' => []);
        $sliderData = $this->getSlider()->getData();

        if (!empty($sliderData)) {
            unset($sliderData['slides']);
            $config = array_merge($config, $sliderData);
        }
        $slides = $this->getSlider()->getSlides();
        if ($slides) {
            foreach ($slides as $slide) {
                $slideData = array();
//                { ["id"]=> string(1) "1" ["slider_id"]=> string(1) "4" ["position"]=> string(1) "0" ["title"]=> string(7) "Slide 1"
//                foreach($slide->getData() as $key => $val){
//                    $slideDataTemp[$key] = $val;
//                }
                $slideData = array_merge($slideData, $slide->getData());
                $slideData['image'] = $slide->getImagePath();
                $slideData['blocks'] = $slide->getParams() ?: array();
                unset($slideData['params']);
                $config['slides'][$slide->getId()] = $slideData;
            }
        }
        return Mage::helper('core')->jsonEncode($config);
    }

}