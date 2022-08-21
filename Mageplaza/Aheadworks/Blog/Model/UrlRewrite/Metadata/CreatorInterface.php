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
namespace Aheadworks\Blog\Model\UrlRewrite\Metadata;

use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Interface CreatorInterface
 *
 * @package Aheadworks\Blog\Model\UrlRewrite\Metadata
 */
interface CreatorInterface
{
    /**
     * Create metadata
     *
     * @param mixed $entity
     * @param string $originalUrlKey
     * @return MetadataInterface[]
     * @throws LocalizedException
     */
    public function create($entity, $originalUrlKey);
}
