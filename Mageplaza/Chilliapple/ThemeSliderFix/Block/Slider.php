<?php
namespace Chilliapple\ThemeSliderFix\Block;


class Slider extends \Rokanthemes\SlideBanner\Block\Slider
{
    public function getBannerCollection()
	{
		$sliderId = $this->getSlider()->getId();
		if(!$sliderId)
			return [];
		$collection = $this->_bannerFactory->create()->getCollection();
        $collection->addFieldToFilter('slider_id', $sliderId);
        $collection->setOrder('slide_position','ASC');
		return $collection;
	}
   
    
	public function getSlider()
	{
		if(is_null($this->_slider)):
			$sliderId = $this->getSliderId();		
			
			$storeIds = array(0,$this->_storeManager->getStore()->getId()); // 0 is for all stores

			$collection = $this->_sliderFactory->create()->getCollection()
							   ->addFieldToFilter('slider_identifier', $sliderId)
							   ->addFieldToFilter('storeids', array('in' => $storeIds));

			if($collection->getSize()){
				$data = $collection->getFirstItem();
				$this->_slider = $this->_sliderFactory->create();
				$this->_slider->load($data->getId());
			}else{
				$this->_slider = $this->_sliderFactory->create();
				$this->_slider->load($sliderId);
			}
			
		endif;
		return $this->_slider;
	}
	
}
