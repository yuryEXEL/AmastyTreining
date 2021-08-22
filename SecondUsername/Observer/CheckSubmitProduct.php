<?php


namespace Amasty\SecondUsername\Observer;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class CheckSubmitProduct implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    private $session;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    public function __construct(
        ScopeConfigInterface  $scopeConfig,
        CheckoutSession $session,
        ProductRepositoryInterface $productRepository
    ){
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    public function execute(Observer $observer)
    {
        $sku = $observer->getData('value_sku');
        $promo_sku = $this->scopeConfig->getValue('second_username_config/general/promo_sku');
        $for_sku = $this->scopeConfig->getValue('second_username_config/general/for_sku');
        $for_skus = explode(",", $for_sku);

        $quote = $this->session->getQuote();
        if (!$quote->getId()) {
            $quote->save();
        }

        if ($sku){
            if($for_skus){
                foreach($for_skus as $value){
                    if(($value == $sku)and(!empty($promo_sku))){
                        $product = $this->productRepository->get("$promo_sku");
                        $quote->addProduct($product, 1);
                        $quote->save();
                    }

                }
            }
        }
    }
}
