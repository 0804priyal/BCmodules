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
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType;
use Aheadworks\Blog\Model\Source\UrlRewrite\RouteType;
use Aheadworks\Blog\Model\UrlRewrite\DataProvider as UrlRewriteDataProvider;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\UrlRewrite\Model\StorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Service\V1\Data\UrlRewriteFactory;
use Magento\UrlRewrite\Model\OptionProvider as UrlRewriteOptionProvider;

/**
 * Class Suffix
 * @package Aheadworks\Blog\Model\UrlRewrite\Updater
 */
class Suffix
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
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @var array
     */
    private $entityInsertedFlag = [];

    /**
     * @param StorageInterface $storage
     * @param UrlRewriteFactory $urlRewriteFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param Config $config
     * @param UrlRewriteDataProvider $urlRewriteDataProvider
     * @param PostRepositoryInterface $postRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(
        StorageInterface $storage,
        UrlRewriteFactory $urlRewriteFactory,
        DataObjectProcessor $dataObjectProcessor,
        Config $config,
        UrlRewriteDataProvider $urlRewriteDataProvider,
        PostRepositoryInterface $postRepository,
        CategoryRepositoryInterface $categoryRepository,
        AuthorRepositoryInterface $authorRepository
    ) {
        $this->storage = $storage;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->config = $config;
        $this->urlRewriteDataProvider = $urlRewriteDataProvider;
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->authorRepository = $authorRepository;
    }

    /**
     * Update url rewrite suffix
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @throws LocalizedException
     */
    public function update($storeId, $metadata)
    {
        $newUrlRewrites = [];
        $urlRewrites = $this->urlRewriteDataProvider->getRewrites([$metadata->getEntityType()], $storeId);

        if ($metadata->getEntityType() == EntityType::TYPE_CATEGORY) {
            $newUrlRewrites = $this->getAuthorsPageUrlRewrites($storeId, $metadata);
        }

        foreach ($urlRewrites as $urlRewrite) {
            switch ($metadata->getEntityType()) {
                case EntityType::TYPE_POST:
                    $entity = $this->postRepository->get($urlRewrite->getEntityId());
                    $entityUrlKey = $entity->getUrlKey();
                    break;
                case EntityType::TYPE_CATEGORY:
                    $entity = $this->categoryRepository->get($urlRewrite->getEntityId());
                    $entityUrlKey = $entity->getUrlKey();
                    break;
                case EntityType::TYPE_AUTHOR:
                    $entity = $this->authorRepository->get($urlRewrite->getEntityId());
                    $entityUrlKey = $this->config->getRouteToAuthors($storeId) . '/' . $entity->getUrlKey();
                    break;
            }
            $routeToBlog = $this->config->getRouteToBlog($storeId);

            $newUrl = $routeToBlog . '/' . $entityUrlKey . $metadata->getCurrentValue();
            $oldUrl = $routeToBlog . '/' . $entityUrlKey . $metadata->getOldValue();

            /** Adding a redirect to new url for each entity */
            if (!isset($this->entityInsertedFlag[$urlRewrite->getEntityId()])) {
                $newUrlRewrites[] = $this->urlRewriteFactory->create()
                    ->setEntityType($metadata->getEntityType())
                    ->setEntityId($urlRewrite->getEntityId())
                    ->setRequestPath($oldUrl)
                    ->setTargetPath($newUrl)
                    ->setStoreId($storeId)
                    ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);
                $this->entityInsertedFlag[$urlRewrite->getEntityId()] = true;
            }

            if ($path = $urlRewrite->getRequestPath() != $newUrl) {
                $this->notUnique($newUrlRewrites, $path)
                    ?: $newUrlRewrites[] = $urlRewrite->setTargetPath($newUrl);
            } else {
                $this->storage->deleteByData(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $urlRewrite,
                        UrlRewrite::class
                    )
                );
            }
        }

        $this->entityInsertedFlag = [];
        $this->storage->replace($newUrlRewrites);
    }

    /**
     * Get authors page url rewrites
     *
     * @param int $storeId
     * @param MetadataInterface $metadata
     * @return UrlRewrite[]
     */
    public function getAuthorsPageUrlRewrites($storeId, $metadata)
    {
        $newUrlRewrites = [];
        $urlRewrites = $this->urlRewriteDataProvider->getRewrites([RouteType::TYPE_AUTHOR], $storeId);
        $routeToBlog = $this->config->getRouteToBlog($storeId);
        $oldUrl = $routeToBlog . '/' . $this->config->getRouteToAuthors($storeId) . $metadata->getOldValue();
        $newUrl = $routeToBlog . '/' . $this->config->getRouteToAuthors($storeId) . $metadata->getCurrentValue();

        $newUrlRewrites[] = $this->urlRewriteFactory->create()
            ->setEntityType(RouteType::TYPE_AUTHOR)
            ->setEntityId(0)
            ->setRequestPath($oldUrl)
            ->setTargetPath($newUrl)
            ->setStoreId($storeId)
            ->setRedirectType(UrlRewriteOptionProvider::PERMANENT);

        foreach ($urlRewrites as $urlRewrite) {
            if ($path = $urlRewrite->getRequestPath() != $newUrl) {
                 $this->notUnique($newUrlRewrites, $path)
                     ?: $newUrlRewrites[] = $urlRewrite->setTargetPath($newUrl);
            } else {
                $this->storage->deleteByData(
                    $this->dataObjectProcessor->buildOutputDataArray(
                        $urlRewrite,
                        UrlRewrite::class
                    )
                );
            }
        }

        return $newUrlRewrites;
    }

    /**
     * New request path not unique.
     *
     * @param array $newUrlRewrites
     * @param string $requestPath
     * @return bool
     */
    private function notUnique($newUrlRewrites, $requestPath)
    {
        $notUnique = array_filter($newUrlRewrites, function ($rewrite) use ($requestPath) {
            return $rewrite->getRequestPath() == $requestPath;
        });

        return (bool)count($notUnique);
    }
}
