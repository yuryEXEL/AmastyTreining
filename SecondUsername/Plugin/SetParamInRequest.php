<?php


namespace Amasty\SecondUsername\Plugin;

use Magento\Catalog\Api\ProductRepositoryInterface;


class SetParamInRequest
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ){
        $this->productRepository = $productRepository;
    }

    public function beforeExecute(
        $subject
    ){
        $product = $this->productRepository->get($subject->getRequest()->getParam('sku'));

        return [$subject->getRequest()->setParam('product', $product->getId())];
    }

}
