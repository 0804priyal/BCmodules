<?php
 
namespace Chilliapple\ThemeSliderFix\Block\Adminhtml\Slide\Edit\Tab;

use \Magento\Backend\Block\Widget\Form\Generic;
use \Magento\Backend\Block\Widget\Tab\TabInterface;
use \Magento\Backend\Block\Template\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\Data\FormFactory;
use \Magento\Cms\Model\Wysiwyg\Config;
 
class Info extends \Rokanthemes\SlideBanner\Block\Adminhtml\Slide\Edit\Tab\Info
{
   
    protected $systemStore;
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Config $wysiwygConfig,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory,$wysiwygConfig,$objectManager,$data);
    }

   // Magento\Store\Model\SystemStore

   // getStoreName
 
	protected function _getSliderOptions()
	{
		$result = [];
		$collection = $this->_objectManager->create('Rokanthemes\SlideBanner\Model\Slider', [])->getCollection();
		foreach($collection as $slider)
		{
            $storename = '';
            
            if($slider->getData('storeids')){
                $storeIds = explode(',',$slider->getData('storeids'));
                $storeName = [];
                foreach($storeIds as $sotoreId){
                    $storeName[] = $this->systemStore->getStoreName($sotoreId);
                }
                $storename = implode(', ',$storeName);
            }
           
            $sliderTitle = $slider->getSliderTitle();
            if($storename!=''){
                $sliderTitle = $slider->getSliderTitle().' ('.$storename.' Store)';
            }
            
           
			$result[] = array('value'=>$slider->getId(), 'label'=>$sliderTitle);
		}
		return $result;
	}
   
}