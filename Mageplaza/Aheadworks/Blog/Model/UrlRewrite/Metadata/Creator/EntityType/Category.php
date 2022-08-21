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
namespace Aheadworks\Blog\Model\UrlRewrite\Metadata\Creator\EntityType;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType;
use Aheadworks\Blog\Model\UrlRewrite\Metadata\CreatorInterface;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterfaceFactory;
use Aheadworks\Blog\Model\Resolver\StoreIds as StoreIdsResolver;

/**
 * Class Category
 *
 * @package Aheadworks\Blog\Model\UrlRewrite\Metadata\Creator\EntityType
 */
class Category implements CreatorInterface
{
    /**
     * @var MetadataInterfaceFactory
     */
    private $metadataFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreIdsResolver
     */
    private $storeIdsResolver;

    /**
     * @param MetadataInterfaceFactory $metadataFactory
     * @param Config $config
     * @param StoreIdsResolver $storeIdsResolver
     */
    public function __construct(
        MetadataInterfaceFactory $metadataFactory,
        Config $config,
        StoreIdsResolver $storeIdsResolver
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->config = $config;
        $this->storeIdsResolver = $storeIdsResolver;
    }

    /**
     * Create metadata
     *
     * @param CategoryInterface $entity
     * @param string $originalUrlKey
     * @return MetadataInterface[]
     */
    public function create($entity, $originalUrlKey)
    {
        $metadataArray = [];

        if ($entity instanceof CategoryInterface) {
            $urlKey = $entity->getUrlKey();
            $originalUrl = $originalUrlKey;
            foreach ($this->storeIdsResolver->getStoreIds($entity) as $storeId) {
                $suffix = $this->config->getUrlSuffixForOtherPages($storeId);
                $routeToBlog = $this->config->getRouteToBlog($storeId);

                $newUrl = $routeToBlog . '/' . $urlKey . $suffix;
                $oldUrl = $routeToBlog . '/' . $originalUrl . $suffix;

                /** @var MetadataInterface $metadata */
                $metadata = $this->metadataFactory->create();
                $metadata->setEntityObject($entity)
                    ->setEntityType(EntityType::TYPE_CATEGORY)
                    ->setOriginalUrlKey($originalUrlKey)
                    ->setOldUrl($oldUrl)
                    ->setNewUrl($newUrl)
                    ->setStoreIds([$storeId]);
                $metadataArray[] = $metadata;
            }
        }

        return $metadataArray;
    }
}
