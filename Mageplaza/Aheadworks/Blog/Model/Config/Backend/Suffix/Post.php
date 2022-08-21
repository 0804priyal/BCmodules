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
namespace Aheadworks\Blog\Model\Config\Backend\Suffix;

use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType;
use Aheadworks\Blog\Model\Config\Backend\AbstractSuffix;

/**
 * Class Post
 * @package Aheadworks\Blog\Model\Config\Backend\Suffix
 */
class Post extends AbstractSuffix
{
    /**
     * {@inheritDoc}
     */
    public function getEntityType()
    {
        return EntityType::TYPE_POST;
    }
}
