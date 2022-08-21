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

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType;
use Aheadworks\Blog\Model\UrlRewrite\DataProvider as UrlRewriteDataProvider;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\UrlRewrite\Model\StorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;

/**
 * Class Route
 * @package Aheadworks\Blog\Model\UrlRewrite\Updater
 */
class Route
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
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var UrlRewriteDataProvider
     */
    private $urlRewriteDataProvider;

    /**
     * @param StorageInterface $storage
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param Config $config
     * @param UrlRewriteDataProvider $urlRewriteDataProvider
     */
    public function __construct(
        StorageInterface $storage,
        UrlRewriteFactory $urlRewriteFactory,
        DataObjectProcessor $dataObjectProcessor,
        Config $config,
        UrlRewriteDataProvider $urlRewriteDataProvider
    ) {
        $this->storage = $storage;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->config = $config;
        $this->urlRewriteDataProvider = $urlRewriteDataProvider;
    }

    /**
     * Update url rewrite blog route
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @throws LocalizedException
     */
    public function updateBlogRoute($storeId, $metadata)
    {
        $newUrlRewrites[] = $this->addNewBlogUrlRewrite($storeId, $metadata);
        $urlRewrites = $this->urlRewriteDataProvider->getRewrites(EntityType::getEntityArray(), $storeId);

        foreach ($urlRewrites as $urlRewrite) {
            $newRoute = str_replace(
                $metadata->getOldValue() . '/',
                $metadata->getCurrentValue() . '/',
                $urlRewrite->getTargetPath()
            );

            if ($urlRewrite->getRequestPath() != $newRoute) {
                $newUrlRewrites[] = $urlRewrite->setTargetPath($newRoute);
            } else {
                $this->storage->deleteByData(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $urlRewrite,
                        UrlRewrite::class
                    )
                );
            }
        }

        $this->storage->replace($newUrlRewrites);
    }

    /**
     * Update url rewrite author route
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @throws LocalizedException
     */
    public function updateAuthorRoute($storeId, $metadata)
    {
        $newUrlRewrites[] = $this->addNewAuthorUrlRewrite($storeId, $metadata);
        $urlRewrites = $this->urlRewriteDataProvider->getRewrites([EntityType::TYPE_AUTHOR], $storeId);

        foreach ($urlRewrites as $urlRewrite) {
            $newRoute = str_replace(
                '/' . $metadata->getOldValue() . '/',
                '/' . $metadata->getCurrentValue() . '/',
                $urlRewrite->getTargetPath()
            );

            if ($urlRewrite->getRequestPath() != $newRoute) {
                $newUrlRewrites[] = $urlRewrite->setTargetPath($newRoute);
            } else {
                $this->storage->deleteByData(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $urlRewrite,
                        UrlRewrite::class
                    )
                );
            }
        }

        $this->storage->replace($newUrlRewrites);
    }

    /**
     * Add new blog url rewrite
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @return UrlRewrite
     */
    private function addNewBlogUrlRewrite($storeId, $metadata)
    {
        return $this->urlRewriteFactory->create()
            ->setEntityType($metadata->getRouteType())
            ->setEntityId(0)
            ->setRequestPath($metadata->getOldValue())
            ->setTargetPath($metadata->getCurrentValue())
            ->setStoreId($storeId)
            ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);
    }

    /**
     * Add new author url rewrite
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @return UrlRewrite
     */
    private function addNewAuthorUrlRewrite($storeId, $metadata)
    {
        return $this->urlRewriteFactory->create()
            ->setEntityType($metadata->getRouteType())
            ->setEntityId(0)
            ->setRequestPath(
                $this->config->getRouteToBlog($storeId) . '/' .
                $metadata->getOldValue() .
                $this->config->getUrlSuffixForOtherPages($storeId)
            )
            ->setTargetPath(
                $metadata->getBlogRoute() . '/' .
                $metadata->getCurrentValue() .
                $this->config->getUrlSuffixForOtherPages($storeId)
            )
            ->setStoreId($storeId)
            ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);
    }
}
