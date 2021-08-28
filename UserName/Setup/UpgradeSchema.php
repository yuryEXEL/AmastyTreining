<?php


namespace Amasty\UserName\Setup;



use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;


class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ){
        $setup->startSetup();
        if (version_compare($context->getVersion(), '0.0.2', '<')) {
            $setup->getConnection()->addColumn(
                $setup->getTable(InstallSchema::TABLE_MAME),
                'email_body',
                [
                    'type' => Table::TYPE_TEXT,
                    500,
                    'default' => '',
                    'nullable' => false,
                    'comment' => 'Is body email text'
                ]
            );
        }
        $setup->endSetup();
    }
}
