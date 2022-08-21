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
namespace Aheadworks\Blog\Block;

/**
 * Link to blog page
 *
 * @method Link setHref(string $href)
 * @method Link setTitle(string $title)
 * @method Link setLabel(string $label)
 *
 * @package Aheadworks\Blog\Block
 */
class Link extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * Get href URL
     *
     * @return string
     */
    public function getHref()
    {
        return $this->getData('href');
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        return '<a ' . $this->getLinkAttributes() . ' >'
            . $this->escapeHtml($this->getLabel()) . '</a>';
    }
}
