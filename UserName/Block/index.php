<?php

namespace Amasty\UserName\Block;


use Magento\Framework\View\Element\Template;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Index extends Template
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(Template\Context $context,
                                ScopeConfigInterface $scopeConfig,
                                array $data = [])
    {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $data);
    }

    public function sayWazzupTo($name)
    {
        return 'Wazuup' . $name;
    }

    public function getGreet()
    {
        return $this->scopeConfig->getValue('user_name_config/general/greeting_text');
    }

    public function showQty()
    {
        return $this->scopeConfig->getValue('user_name_config/general/show_qty');
    }

    public function getDefaultValueQty()
    {
        return $this->scopeConfig->getValue('user_name_config/general/default_qty');
    }
}
