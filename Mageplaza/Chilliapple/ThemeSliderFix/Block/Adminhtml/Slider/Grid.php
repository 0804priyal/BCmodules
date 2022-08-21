<?php
 
namespace Chilliapple\ThemeSliderFix\Block\Adminhtml\Slider ;
 
use \Magento\Backend\Block\Widget\Grid as WidgetGrid;
 
class Grid extends \Rokanthemes\SlideBanner\Block\Adminhtml\Slider\Grid
{

     /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'slider_id',
            [
                'header' => __('ID'),
                'type' => 'number',
                'index' => 'slider_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
		$this->addColumn(
            'slider_identifier',
            [
                'header' => __('Identifier'),
                'type' => 'text',
                'index' => 'slider_identifier',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
		$this->addColumn(
            'slider_title',
            [
                'header' => __('Title'),
                'type' => 'text',
                'index' => 'slider_title',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
        $this->addColumn('storeids', array(
            'header'        => __('Store View'),
            'index'         => 'storeids',
            'type'          => 'store',
            'store_all'     => true,
            'store_view'    => true,
            'sortable'      => false,
            'filter_condition_callback'
                            => array($this, '_filterStoreCondition'),
        ));
		$this->addColumn(
            'slider_status',
            [
                'header' => __('Status'),
                'type' => 'options',
                'index' => 'slider_status',
				'options'=> [1=>__('Enable'), 2=>__('Disable')],
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );
		$block = $this->getLayout()->getBlock('grid.bottom.links');
        if ($block) {
            $this->setChild('grid.bottom.links', $block);
        }
        return parent::_prepareColumns();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }

        $this->getCollection()->addStoreFilter($value);
    }
}