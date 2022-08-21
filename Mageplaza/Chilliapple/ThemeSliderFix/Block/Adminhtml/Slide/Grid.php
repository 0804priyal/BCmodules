<?php
 
namespace Chilliapple\ThemeSliderFix\Block\Adminhtml\Slide;
 
use \Magento\Backend\Block\Widget\Grid as WidgetGrid;
 
class Grid extends \Rokanthemes\SlideBanner\Block\Adminhtml\Slide\Grid
{
    
    protected $systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->systemStore = $systemStore;
        parent::__construct($context, $backendHelper,$objectManager,$data);
    }
 
    
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

			$result[$slider->getId()] = $sliderTitle;
		}
		return $result;
	}
    
}