<?php


namespace Amasty\UserName\Model\ResourceModel\Blacklist;

use Amasty\UserName\Model\Blacklist;
use Amasty\UserName\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            Blacklist::class,
            BlacklistResource::class
        );
    }
}
