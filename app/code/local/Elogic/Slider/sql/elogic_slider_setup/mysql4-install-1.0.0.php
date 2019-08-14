<?php
/**
 * News installation script
 *
 * @author elogic
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */

$installer = $this;

/**
 * Creating table elogic_slider_slides
 */

$installer->startSetup();

$installer->getConnection()->dropTable($installer->getTable('elogic_slider/slider'));
$table = $installer->getConnection()
    ->newTable($installer->getTable('elogic_slider/slider'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'auto_increment' => true,
    ))
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ))
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'length' => 255
    ))
    ->addColumn('is_active', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ));
$installer->getConnection()->createTable($table);

$installer->getConnection()->dropTable($installer->getTable('elogic_slider/slider_slides'));
$table = $installer->getConnection()
    ->newTable($installer->getTable('elogic_slider/slider_slides'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'auto_increment' => true,
    ))
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ))
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => false,
        'unsigned' => true,
    ))
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'length' => 25,
    ))
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'length' => 35,
    ))
    ->addColumn('params', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false,
        'length' => 240,
    ))
    ->addForeignKey($installer->getFkName('elogic_slider/slider_slides', 'slider_id', 'elogic_slider/slider', 'id'),
        'slider_id', $installer->getTable('elogic_slider/slider'), 'id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_NO_ACTION
    );
$installer->getConnection()->createTable($table);


$installer->endSetup();