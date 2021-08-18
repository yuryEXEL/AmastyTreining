<?php

namespace Amasty\UserName\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;

class Autocoplate extends Action
{
    /**
     * @var JsonFactory
     */
    private $jsonResultFactory;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;


    public function __construct(
        Context $context,
        JsonFactory $jsonResultFactory,
        ProductCollectionFactory $productCollectionFactory
    ){
        $this->productCollectionFactory = $productCollectionFactory;
        $this->jsonResultFactory = $jsonResultFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $data = [];
        $sku = $this->getRequest()->getParam('q');

        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToFilter('sku',
                ['attribute' => 'sku', 'like' => '%'.$sku.'%']
        );
        $productCollection->addAttributeToSelect('name');


        foreach ($productCollection as $product) {
            $data[] = $product->getSku()."  ".$product->getName();
        }

        $result = $this->jsonResultFactory->create();
        $result->setData($data);
        return $result;
    }
}
