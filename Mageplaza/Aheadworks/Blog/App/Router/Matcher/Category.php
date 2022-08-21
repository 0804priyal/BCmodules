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
namespace Aheadworks\Blog\App\Router\Matcher;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\App\Router\MatcherInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\PostFactory;
use Aheadworks\Blog\Model\CategoryFactory;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Config\Seo\UrlType;

/**
 * Class Category
 * @package Aheadworks\Blog\App\Router\Matcher
 */
class Category implements MatcherInterface
{
    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param PostFactory $postFactory
     * @param CategoryFactory $categoryFactory
     * @param Config $config
     */
    public function __construct(
        PostFactory $postFactory,
        CategoryFactory $categoryFactory,
        Config $config
    ) {
        $this->postFactory = $postFactory;
        $this->categoryFactory = $categoryFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     * @param RequestInterface|Http $request
     */
    public function match(RequestInterface $request)
    {
        $parts = explode('/', trim($request->getPathInfo(), '/'));
        list(, $categoryUrlKey, $postUrlKey) = array_merge($parts, array_fill(0, 3, null));
        if (empty($postUrlKey)) {
            $categoryPageSuffix = $this->config->getUrlSuffixForOtherPages();
            if (!empty($categoryPageSuffix) && $categoryPageSuffix != '/') {
                $categoryUrlKey = substr($categoryUrlKey, 0, strrpos($categoryUrlKey, $categoryPageSuffix));
            }
        }

        if ($categoryId = $this->getCategoryIdByUrlKey($categoryUrlKey)) {
            $controllerName = 'category';
            $params = ['blog_category_id' => $categoryId];
            $postPageSuffix = $this->config->getPostUrlSuffix();
            if (!empty($postPageSuffix) && $postPageSuffix != '/') {
                $postUrlKey = substr($postUrlKey, 0, strrpos($postUrlKey, $postPageSuffix));
            }

            $isPostUrlKeyUsed = false;
            if ($postUrlKey && $postId = $this->getPostIdByUrlKey($postUrlKey)) {
                $controllerName = 'post';
                $params['post_id'] = $postId;
                $isPostUrlKeyUsed = true;
            }

            if ($this->isParamsQtyInvalid($parts, $isPostUrlKeyUsed)) {
                return false;
            }

            $request
                ->setControllerName($controllerName)
                ->setActionName('view')
                ->setParams($params);

            return true;
        }

        return false;
    }

    /**
     * Retrieves post ID by URL-Key
     *
     * @param string $urlKey
     * @return int|null
     */
    private function getPostIdByUrlKey($urlKey)
    {
        /** @var \Aheadworks\Blog\Model\Post $postModel */
        $postModel = $this->postFactory->create();
        $postModel->load($urlKey, PostInterface::URL_KEY);
        return $postModel->getId();
    }

    /**
     * Retrieves category ID by URL-Key
     *
     * @param string $urlKey
     * @return int|null
     */
    private function getCategoryIdByUrlKey($urlKey)
    {
        /** @var \Aheadworks\Blog\Model\Category $categoryModel */
        $categoryModel = $this->categoryFactory->create();
        $categoryModel->load($urlKey, CategoryInterface::URL_KEY);
        return $categoryModel->getId();
    }

    /**
     * Check if length of parts array is invalid
     *
     * @param array $parts
     * @param bool $isPostUrlKeyUsed
     * @return bool
     */
    private function isParamsQtyInvalid($parts, $isPostUrlKeyUsed)
    {
        $expectedPartsCountForCategoryRoute = $isPostUrlKeyUsed ? 3 : 2;
        return $expectedPartsCountForCategoryRoute !== count($parts);
    }
}
