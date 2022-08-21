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

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Blog\Model\UrlRewrite\Metadata\CreatorInterface;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterfaceFactory;

/**
 * Class Author
 *
 * @package Aheadworks\Blog\Model\UrlRewrite\Metadata\Creator\EntityType
 */
class Author implements CreatorInterface
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
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param MetadataInterfaceFactory $metadataFactory
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        MetadataInterfaceFactory $metadataFactory,
        Config $config,
        StoreManagerInterface $storeManager
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Create metadata
     *
     * @param AuthorInterface $entity
     * @param string $originalUrlKey
     * @return MetadataInterface[]
     */
    public function create($entity, $originalUrlKey)
    {
        $metadataArray = [];

        if ($entity instanceof AuthorInterface) {
            $stores = $this->storeManager->getStores();
            foreach ($stores as $store) {
                $suffix = $this->config->getAuthorUrlSuffix($store->getId());
                $authorRoute = $this->config->getRouteToAuthors($store->getId());
                $routeToBlog = $this->config->getRouteToBlog($store->getId());

                $urlKey = $authorRoute . '/' . $entity->getUrlKey();
                $originalUrl = $authorRoute . '/' . $originalUrlKey;

                $newUrl = $routeToBlog . '/' . $urlKey . $suffix;
                $oldUrl = $routeToBlog . '/' . $originalUrl . $suffix;

                /** @var MetadataInterface $metadata */
                $metadata = $this->metadataFactory->create();
                $metadata->setEntityObject($entity)
                    ->setEntityType(EntityType::TYPE_AUTHOR)
                    ->setOriginalUrlKey($originalUrlKey)
                    ->setOldUrl($oldUrl)
                    ->setNewUrl($newUrl)
                    ->setStoreIds([$store->getId()]);
                $metadataArray[] = $metadata;
            }
        }

        return $metadataArray;
    }
}
