<?php


namespace Amasty\UserName\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;


class Blacklist extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(
            \Amasty\UserName\Setup\InstallSchema::TABLE_MAME,
            'blacklist_id'
        );
    }
}
