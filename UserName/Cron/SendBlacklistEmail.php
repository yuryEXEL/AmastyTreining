<?php


namespace Amasty\UserName\Cron;

use Amasty\UserName\Model\Blacklist;
use Amasty\UserName\Model\BlacklistFactory;
use Amasty\UserName\Model\ResourceModel\Blacklist as BlacklistResource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;

class SendBlacklistEmail
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var BlacklistFactory
     */
    protected $blacklistFactory;
    /**
     * @var BlacklistResource
     */
    protected $blacklistResource;
    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;
    /**
     * @var TransportBuilder $transportBuilder
     */
    protected $transportBuilder;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource,
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder
    ){
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
    }
    public function execute()
    {
        /**
         * @var \Amasty\UserName\Model\Blacklist $blacklist
         */
        $blacklist = $this->blacklistFactory->create();

        $this->blacklistResource->load(
            $blacklist,
            1,
            'blacklist_id'
        );

        $temlateId = 'user_name_config_general_template';
        $senderName = 'Admin';
        $senderEmail = $this->scopeConfig->getValue('user_name_config/general/email_sender');
        $toEmail = 'user@amasty.com';
        $tamplateVars = [
            'blacklist' => $blacklist,
            'sku' => $blacklist->getSku(),
            'qty' => $blacklist->getQty()
        ];
        $storeId = $this->storeManager->getStore()->getId();
        $from = [
            'email' => $senderEmail,
            'name' => $senderName
        ];
        /**@var  \Magento\Email\Model\Transport $transport  */
        $transport = $this->transportBuilder->setTemplateIdentifier($temlateId, ScopeInterface::SCOPE_STORE)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $storeId
                ]
            )->setTemplateVars($tamplateVars)
            ->setFromByScope($from)
            ->addTo($toEmail)
            ->getTransport();
        $message = $transport->getMessage();
        $messageText = $message->getBodyText();

        $blacklist->setEmail_body( $messageText);
        $blacklist->save();
    }
}
