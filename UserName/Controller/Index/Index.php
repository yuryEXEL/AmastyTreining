<?php

declare(strict_types=1);

namespace Amasty\UserName\Controller\Index;


use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;


class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ){
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function execute()
    {
        if($this->scopeConfig->getValue('user_name_config/general/enebled')) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        }else{
            die('Sorry, go home');
        }
    }
}
