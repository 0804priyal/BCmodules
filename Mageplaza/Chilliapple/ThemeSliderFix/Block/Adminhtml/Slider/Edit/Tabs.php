<?php
 
namespace Chilliapple\ThemeSliderFix\Block\Adminhtml\Slider\Edit;
use \Magento\Backend\Block\Widget\Tabs as WidgetTabs;
 
class Tabs extends \Rokanthemes\SlideBanner\Block\Adminhtml\Slider\Edit\Tabs
{
  
 
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'slider_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Chilliapple\ThemeSliderFix\Block\Adminhtml\Slider\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );
        $this->addTab(
            'slider_setting',
            [
                'label' => __('Setting Slider'),
                'title' => __('Setting Slider'),
                'content' => $this->getLayout()->createBlock(
                    'Rokanthemes\SlideBanner\Block\Adminhtml\Slider\Edit\Tab\Setting'
                )->toHtml(),
                'active' => false
            ]
        );
        $this->addTab(
            'slide_info',
            [
                'label' => __('Banner List'),
                'title' => __('Banner List'),
                'content' => $this->getLayout()->createBlock(
                    'Rokanthemes\SlideBanner\Block\Adminhtml\Slider\Edit\Tab\Banner'
                )->toHtml(),
                'active' => false
            ]
        );
 
        return parent::_beforeToHtml();
    }
}