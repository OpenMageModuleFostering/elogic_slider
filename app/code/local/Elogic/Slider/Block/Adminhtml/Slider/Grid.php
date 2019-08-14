<?php
/**
 * Slider admin grid container
 *
 * @author elogic
 */

/**
 * Class Elogic_Slider_Block_Adminhtml_Slider_Grid
 */
class Elogic_Slider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Collection for Grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('elogic_slider/slider_collection');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Return row URL
     * @param $row
     * @return mixed
     */

    public function getRowUrl($row)
    {
        return $this->getUrl('elogic_slider/adminhtml_slider/edit', array('id' => $row->getId()));
    }

    /**
     * Grid Columns
     *
     * @return $this
     * @throws Exception
     */

    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header' => $this->_getHelper()->__('ID'),
            'type' => 'number',
            'index' => 'id',
        ));

        $this->addColumn('name', array(
            'header' => $this->_getHelper()->__('Name'),
            'type' => 'text',
            'index' => 'name',
        ));

        $this->addColumn('store_id', array(
            'header' => $this->_getHelper()->__('Store id'),
            'type' => 'store',
            'index' => 'store_id',
            'width' => '200px',
        ));

        $this->addColumn('is_active', array(
            'header' => $this->_getHelper()->__('is Active'),
            'type' => 'checkbox',
            'width' => '100px',
            'index' => 'is_active',
        ));

        return parent::_prepareColumns();
    }

    /**
     * @return Mage_Core_Helper_Abstract
     */

    protected function _getHelper()
    {
        return Mage::helper('elogic_slider');
    }

}