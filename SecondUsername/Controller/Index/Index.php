<?php


namespace Amasty\SecondUsername\Controller\Index;

use Amasty\UserName\Controller\Index\Index as UserName;
use Magento\Customer\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Controller\ResultFactory;


class Index extends UserName
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CheckoutSession
     */
    private $session;
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $session
    ){
        $this->session = $session;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $scopeConfig);
    }

    public function execute()
    {
        if ($this->session->isLoggedIn()) {
            if($this->scopeConfig->getValue('user_name_config/general/enebled')) {
                return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
            }else{
                die('Sorry, go home');
            }
        } else {
            $this->_redirect('http://localhost/magento2/customer/account/login/');
        }

    }
}
