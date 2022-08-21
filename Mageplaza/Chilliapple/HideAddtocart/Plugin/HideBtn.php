<?php
/**
 * Created By : David Chilliapple
 */
namespace Chilliapple\HideAddtocart\Plugin;

class HideBtn
{               
    protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $logger){
      $this->logger = $logger;
    }

    public function afterIsSaleable(\Magento\Catalog\Model\Product $product, $result)
    {

        return $result;
        /*$this->logger->debug("David====================");
        $this->logger->debug("David product ID:" . $product->getId() );        

        $this->logger->debug("manufacturer Info:");
        $this->logger->debug(print_r($product->getAttributeText('manufacturer'),1));*/


        //if($product->getId() == 168)
        if($product->getAttributeText('manufacturer') == 'Wenger')
        {
            return false; // For hide button
        } else {
            return true; // For display button
        }        
    }
}