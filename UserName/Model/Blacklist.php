<?php


namespace Amasty\UserName\Model;

use Magento\Framework\Model\AbstractModel;

class Blacklist extends AbstractModel
{
    /**
     * Class Blacklist
     * @method string getSku()
     * @method integer getQty()
     */

    protected function _construct()
    {
        $this->_init(
            ResourceModel\Blacklist::class
        );
    }
}
