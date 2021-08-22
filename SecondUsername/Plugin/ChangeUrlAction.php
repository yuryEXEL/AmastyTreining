<?php

namespace Amasty\SecondUsername\Plugin;

class ChangeUrlAction
{
    public function afterGetFormAction(
        $subject,
        $result
    ){
        return $result = 'http://localhost/magento2/checkout/cart/add';
    }

}
