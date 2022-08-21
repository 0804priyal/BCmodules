<?php

namespace Chilliapple\GoldBlocking\Block;

use Magento\Framework\View\Element\Template;

use Magento\Checkout\Model\Cart as CustomerCart;
use \Chilliapple\Core\Logger\Logger;

class GoldBlocking extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    protected $customerCart;

    protected $logger;

    protected $chilliHelper;

    protected $priceHelper;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerCart $customerCart,
        Logger $logger,
          \Chilliapple\GoldBlocking\Helper\Data $chilliHelper,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,

        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->chilliHelper = $chilliHelper;
        $this->priceHelper = $priceHelper;
        
        $this->customerCart = $customerCart;
        $this->registry = $registry;
        $this->logger = $logger;
    }

    public function getProduct(){
        return $this->registry->registry('product');
    }

    public function getDataFromQuote(){ 
        if(!$this->getRequest()->getParam('product_id')){
            return;
        }
        $id = (int)$this->getRequest()->getParam('id'); 
     
        $quote = $this->customerCart->getQuote();
        if($id){
            $quoteItem = $quote->getItemById($id);   
            if($quoteItem){
                $buyRequest = $quoteItem->getAdditionalData();              
                return $buyRequest;
            }else{
                return [];        
            }
        }
        return [];
    }

     public function getHelper(){
        return $this->chilliHelper;
    }
   

    public function getFormattedPrice($price){
        return $this->priceHelper->currency($price, true, false);
    }


}