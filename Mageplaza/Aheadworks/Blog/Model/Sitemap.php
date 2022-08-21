<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://ecommerce.aheadworks.com/end-user-license-agreement/
 *
 * @package    Blog
 * @version    2.7.4
 * @copyright  Copyright (c) 2020 Aheadworks Inc. (http://www.aheadworks.com)
 * @license    https://ecommerce.aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Blog\Model;

use Magento\Framework\DataObject;

/**
 * Class Sitemap
 * @package Aheadworks\Blog\Model
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    /**
     * {@inheritdoc}
     */
    protected function _initSitemapItems()
    {
        parent::_initSitemapItems();
        $this->_eventManager->dispatch('aw_sitemap_items_init', ['object' => $this]);
    }

    /**
     * Add sitemap items
     *
     * @param DataObject[] $items
     * @return $this
     */
    public function appendSitemapItems($items)
    {
        $this->_sitemapItems = array_merge($this->_sitemapItems, $items);
        return $this;
    }
}
