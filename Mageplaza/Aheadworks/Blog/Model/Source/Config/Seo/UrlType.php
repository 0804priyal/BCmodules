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
namespace Aheadworks\Blog\Model\Source\Config\Seo;

/**
 * Class UrlType
 * @package Aheadworks\Blog\Model\Source\Config\Seo
 */
class UrlType implements \Magento\Framework\Option\ArrayInterface
{
    /**#@+
     * Constants defined for url types
     */
    const URL_EXC_CATEGORY = 'url_exclude_category';
    const URL_INC_CATEGORY = 'url_include_category';
    /**#@-*/

    /**
     * Retrieve url types as option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::URL_EXC_CATEGORY, 'label' => __('site.com/blog/article')],
            ['value' => self::URL_INC_CATEGORY, 'label' => __('site.com/blog/category/article')],
        ];
    }
}
