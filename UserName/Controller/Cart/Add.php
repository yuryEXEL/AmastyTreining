<?php

namespace Amasty\UserName\Controller\Cart;

use Amasty\UserName\Model\Blacklist;
use Amasty\UserName\Model\BlacklistFactory;
use Amasty\UserName\Model\ResourceModel\Blacklist as BlacklistResource;
use Amasty\UserName\Model\ResourceModel\Blacklist\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\CatalogInventory\Model\Stock\StockItemRepository;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\NoSuchEntityException;



class Add extends Action
{
    /**
     * @var BlacklistFactory
     */
    protected $blacklistFactory;
    /**
     * @var BlacklistResource
     */
    protected $blacklistResource;
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var StockItemRepository
     */
    private $stockItemRepository;
    /**
     * @var CheckoutSession
     */
    private $session;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var EventManager
     */
    private $eventManager;

    protected $blacklistSkuQty;
    protected $quoteQty;

    public function __construct(
        BlacklistFactory $blacklistFactory,
        BlacklistResource $blacklistResource,
        CollectionFactory $collectionFactory,
        Context $context,
        ScopeConfigInterface  $scopeConfig,
        CheckoutSession $session,
        ProductRepositoryInterface $productRepository,
        StockItemRepository $stockItemRepository,
        EventManager $eventManager



    ){
        $this->blacklistFactory = $blacklistFactory;
        $this->blacklistResource = $blacklistResource;
        $this->collectionFactory = $collectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->session = $session;
        $this->productRepository = $productRepository;
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

        $blacklistCollection = $this->collectionFactory->create();
        $blacklistCollection->addFieldToFilter(
            'sku',
            ['eq' => $sku]
        );
        /**
         * @var \Amasty\UserName\Model\Blacklist $blacklist
         */
        if($blacklistCollection){
            foreach ($blacklistCollection as $blacklist) {
                $this->blacklistSkuQty = $blacklist->getQty();
            }
        }
        $product = $this->productRepository->get("$sku");
        $stockItemQty = $this->stockItemRepository->get($product->getId());

        if(!$this->blacklistSkuQty){
            try {

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

            } catch (NoSuchEntityException $e) {
                $this->messageManager->addWarningMessage("Продукт \" $sku\" не существует");
                $this->_redirect('lool/');
            }
        }else{
            $items = $quote->getAllItems();
            if ($items){
                foreach($items as $item){
                    if($item->getSku() ==$sku){
                       $this->quoteQty = $item->getQty();
                    }
                }
            }
            if(($this->quoteQty+$qty) < $this->blacklistSkuQty){
                $quote->addProduct($product, $qty);
                $quote->save();
                $this->_redirect('lool/');
            }elseif (($this->quoteQty+$qty) > $this->blacklistSkuQty){
                $totalqty = $this->quoteQty + $qty - $this->blacklistSkuQty;
                $quote->addProduct($product, $totalqty);
                $quote->save();
                $this->messageManager->addWarningMessage("Добавлено в корзину в количестве \" $totalqty\" единиц");
                $this->_redirect('lool/');
            }

        }


    }
}
