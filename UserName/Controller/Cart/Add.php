<?php

namespace Amasty\UserName\Controller\Cart;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Framework\Event\ManagerInterface as EventManager;


class Add extends Action
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CheckoutSession
     */
    private $session;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;
    /**
     * @var EventManager
     */
    private $eventManager;

    public function __construct(
        Context $context,
        ScopeConfigInterface  $scopeConfig,
        CheckoutSession $session,
        ProductRepositoryInterface $productRepository,
        ProductCollectionFactory $productCollectionFactory,
        StockItemRepository $stockItemRepository,
        EventManager $eventManager

    ){
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->productRepository = $productRepository;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->stockItemRepository = $stockItemRepository;
        $this->eventManager = $eventManager;

        parent::__construct($context);
    }

    public function execute()
    {
        $quote = $this->session->getQuote();
        if (!$quote->getId()) {
            $quote->save();
        }
        $sku = $this->getRequest()->getParam('sku');
        $qty = $this->getRequest()->getParam('qty');



        try {
         $product = $this->productRepository->get("$sku");
         $stockItemQty = $this->stockItemRepository->get($product->getId());
            if($stockItemQty->getQty()<$qty){
                $this->messageManager->addWarningMessage("Не достаточно продуктов");
                $this->_redirect('lool/');
            }
            if($product->getTypeId()=='simple'){
                $this->messageManager->addWarningMessage("Продукт не simple типа");
                $this->_redirect('lool/');
            }
            $quote->addProduct($product, $qty);
            $quote->save();

            $this->eventManager->dispatch(
                'amasty_username_check_submit_product',
                ['value_sku' => $sku]
            );

        } catch (NoSuchEntityException $e) {
            $this->messageManager->addWarningMessage("Продукт \" $sku\" не существует");
            $this->_redirect('lool/');
        }

    }
}
