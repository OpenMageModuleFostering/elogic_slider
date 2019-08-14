<?php
/**
 * Slider admin edit form block
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Block_Adminhtml_Slider_Edit_Form
 */
class Elogic_Slider_Block_Adminhtml_Slider_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Form action
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     * @throws Exception
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('slider', array('legend' => Mage::helper('elogic_slider')->__('Slider')));
        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('elogic_slider')->__('Name'),
            'name' => 'name',
            'required' => true,
            'class' => 'required-entry validate-no-html-tags',
        ));
        $fieldset->addField('store_id', 'select', array(
            'label' => Mage::helper('elogic_slider')->__('Sore'),
            'name' => 'store_id',
            'required' => true,
            'class' => 'required-entry validate-no-html-tags',
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm()
        ));
        $fieldset->addField('is_active', 'select', array(
            'label' => Mage::helper('elogic_slider')->__('Is active'),
            'name' => 'is_active',
            'required' => true,
            'class' => 'required-entry',
            'values' => array(
                array(
                    'value' => 0,
                    'label' => $this->__('No')
                ),
                array(
                    'value' => 1,
                    'label' => $this->__('Yes')
                )
            )
        ));

        if (Mage::getSingleton('adminhtml/session')->getFormData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getFormData());
            Mage::getSingleton('adminhtml/session')->setFormData(null);
        } elseif (Mage::registry('elogic_slider_data')) {
            $form->setValues(Mage::registry('elogic_slider_data')->getData());
        }

        $form->setMethod('post');
        $form->setId('edit_slider_form');
        $form->setUseContainer(true);
        $form->setAction($this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))));
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
