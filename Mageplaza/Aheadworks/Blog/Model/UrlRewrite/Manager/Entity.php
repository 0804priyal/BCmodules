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
namespace Aheadworks\Blog\Model\UrlRewrite\Manager;

use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Aheadworks\Blog\Model\UrlRewrite\Updater\Entity as EntityUpdater;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Config;

/**
 * Class Entity
 * @package Aheadworks\Blog\Model\UrlRewrite\Manager
 */
class Entity
{
    /**
     * @var EntityUpdater
     */
    private $entityUpdater;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param EntityUpdater $entityUpdater
     * @param Config $config
     */
    public function __construct(
        EntityUpdater $entityUpdater,
        Config $config
    ) {
        $this->entityUpdater = $entityUpdater;
        $this->config = $config;
    }

    /**
     * Update entity
     *
     * @param MetadataInterface[] $metadataArray
     * @throws LocalizedException
     */
    public function update($metadataArray)
    {
        if ($this->config->getSaveRewritesHistory()) {
            foreach ($metadataArray as $metadata) {
                if ($metadata->getOriginalUrlKey() != null
                    && $metadata->getOriginalUrlKey() != $metadata->getEntityObject()->getUrlKey()
                ) {
                    foreach ($metadata->getStoreIds() as $storeId) {
                        $this->entityUpdater->update($metadata, $storeId);
                    }
                }
            }
        }
    }
}
