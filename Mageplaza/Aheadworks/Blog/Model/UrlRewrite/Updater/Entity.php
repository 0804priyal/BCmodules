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
namespace Aheadworks\Blog\Model\UrlRewrite\Updater;

use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\UrlRewrite\Model\StorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;

/**
 * Class Entity
 * @package Aheadworks\Blog\Model\UrlRewrite\Updater
 */
class Entity
{
    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var UrlRewriteFactory
     */
    private $urlRewriteFactory;

    /**
     * @param StorageInterface $storage
     * @param UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        StorageInterface $storage,
        UrlRewriteFactory $urlRewriteFactory
    ) {
        $this->storage = $storage;
        $this->urlRewriteFactory = $urlRewriteFactory;
    }

    /**
     * Update url rewrite
     *
     * @param MetadataInterface $metadata
     * @param int $storeId
     * @return UrlRewrite
     * @throws LocalizedException
     */
    public function update($metadata, $storeId)
    {
        $urlRewrites = $this->storage->findAllByData([
            'target_path' => $metadata->getOldUrl(),
            'store_id' => $storeId,
            'entity_type' => $metadata->getEntityType()
        ]);
        $newUrlRewrite = $this->urlRewriteFactory->create()
            ->setEntityType($metadata->getEntityType())
            ->setEntityId($metadata->getEntityObject()->getId())
            ->setRequestPath($metadata->getOldUrl())
            ->setTargetPath($metadata->getNewUrl())
            ->setStoreId($storeId)
            ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);
        $newUrlRewrites[] = $newUrlRewrite;
        foreach ($urlRewrites as $urlRewrite) {
            if ($urlRewrite->getTargetPath() != $metadata->getNewUrl()) {
                /** @var $rewrite UrlRewrite */
                $newUrlRewrites[] = $urlRewrite->setTargetPath($metadata->getNewUrl());
            }
        }
        $this->storage->replace($newUrlRewrites);

        return $newUrlRewrite;
    }
}
