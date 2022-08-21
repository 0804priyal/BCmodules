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
namespace Aheadworks\Blog\Model\UrlRewrite;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Model\StorageInterface;

/**
 * Class DataProvider
 * @package Aheadworks\Blog\Model\UrlRewrite
 */
class DataProvider
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @param StorageInterface $storage
     */
    public function __construct(
        StorageInterface $storage
    ) {
        $this->storage = $storage;
    }

    /**
     * Get rewrites by types and store id
     *
     * @param array $entityTypes
     * @param int $storeId
     * @return UrlRewrite[]
     */
    public function getRewrites($entityTypes, $storeId)
    {
        $rewrites = [];
        foreach ($entityTypes as $entityType) {
            $rewrites = array_merge($rewrites, $this->storage->findAllByData([
                'store_id' => $storeId,
                'entity_type' => $entityType,
            ]));
        }

        return $rewrites;
    }
}
