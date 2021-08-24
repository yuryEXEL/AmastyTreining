<?php


namespace Amasty\UserName\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;


class InstallSchema implements InstallSchemaInterface
{
    const TABLE_MAME = 'amasty_username_blacklist';

    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ){
        $setup->startSetup();
        $table = $setup->getConnection()
            ->newTable($setup->getTable(self::TABLE_MAME))
            ->addColumn(
                'blacklist_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'unsigned' => true,
                    'nullable' => false,
                    'primary' => true
                ],
                'Blacklist ID'
            )
            ->addColumn(
                'sku',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false,
                    'default' => ''
                ],
                'SKU text'
            )
            ->addColumn(
                'qty',
                Table::TYPE_INTEGER,
                null,
                [
                    'nullable' => false,
                    'default' => 0
                ],
                'Qty sku'
            )->addIndex(
                $setup->getIdxName(
                    $setup->getTable(self::TABLE_MAME),
                    ['sku'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                'sku',
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )
            ->setComment('Blacklist table');
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}
