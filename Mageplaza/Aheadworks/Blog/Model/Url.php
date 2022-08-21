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
namespace Aheadworks\Blog\Model;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\App\Router\Matcher\Tag;
use Magento\Framework\UrlInterface;
use Aheadworks\Blog\Model\Url\Canonical\Category as CanonicalCategory;

/**
 * Class Url
 * @package Aheadworks\Blog\Model
 */
class Url
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CanonicalCategory
     */
    private $canonicalCategory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @param Config $config
     * @param UrlInterface $urlBuilder
     * @param CanonicalCategory $canonicalCategory
     */
    public function __construct(
        Config $config,
        UrlInterface $urlBuilder,
        CanonicalCategory $canonicalCategory
    ) {
        $this->config = $config;
        $this->canonicalCategory = $canonicalCategory;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve url for the direct route
     *
     * @param $route
     * @return string
     */
    private function getDirectUrl($route)
    {
        return $this->urlBuilder->getDirectUrl($route);
    }

    /**
     * Retrieves blog home url
     *
     * @return string
     */
    public function getBlogHomeUrl()
    {
        return $this->getDirectUrl($this->config->getRouteToBlog());
    }

    /**
     * Retrieves authors url
     *
     * @return string
     */
    public function getAuthorsPageUrl()
    {
        return $this->getDirectUrl(
            $this->config->getRouteToBlog()
            . '/' . $this->config->getRouteToAuthors() . $this->config->getUrlSuffixForOtherPages()
        );
    }

    /**
     * Retrieves post url
     *
     * @param PostInterface $post
     * @param CategoryInterface|null $category
     * @return string
     */
    public function getPostUrl(PostInterface $post, CategoryInterface $category = null)
    {
        $parts = [$this->config->getRouteToBlog()];
        if ($category) {
            $parts[] = $category->getUrlKey();
        }
        $parts[] = $post->getUrlKey();

        return $this->getDirectUrl(
            implode('/', $parts) . $this->config->getPostUrlSuffix()
        );
    }

    /**
     * @param PostInterface $post
     * @param int|null $storeId
     * @return string
     */
    public function getPostRoute(PostInterface $post, $storeId = null)
    {
        return $this->config->getRouteToBlog($storeId)
            . '/' . $post->getUrlKey() . $this->config->getPostUrlSuffix($storeId);
    }

    /**
     * Retrieves category url
     *
     * @param CategoryInterface $category
     * @return string
     */
    public function getCategoryUrl(CategoryInterface $category)
    {
        return $this->getDirectUrl(
            $this->getCategoryRoute($category)
        );
    }

    /**
     * @param CategoryInterface $category
     * @param int|null $storeId
     * @return string
     */
    public function getCategoryRoute(CategoryInterface $category, $storeId = null)
    {
        return $this->config->getRouteToBlog($storeId)
            . '/' . $category->getUrlKey() . $this->config->getUrlSuffixForOtherPages($storeId);
    }

    /**
     * Retrieve author url
     *
     * @param AuthorInterface $author
     * @return string
     */
    public function getAuthorUrl(AuthorInterface $author)
    {
        return $this->getDirectUrl(
            $this->getAuthorRoute($author)
        );
    }

    /**
     * Retrieve author route
     *
     * @param AuthorInterface $author
     * @param int|null $storeId
     * @return string
     */
    public function getAuthorRoute(AuthorInterface $author, $storeId = null)
    {
        return $this->config->getRouteToBlog($storeId)
            . '/' . $this->config->getRouteToAuthors($storeId)
            . '/' . $author->getUrlKey() . $this->config->getAuthorUrlSuffix($storeId);
    }

    /**
     * Retrieves search by tag url
     *
     * @param TagInterface|string $tag
     * @return string
     */
    public function getSearchByTagUrl($tag)
    {
        $tagName = $tag instanceof TagInterface ? $tag->getName() : $tag;

        return $this->getDirectUrl(
            $this->config->getRouteToBlog()
            . '/' . Tag::TAG_KEY
            . '/' . urlencode($tagName) . $this->config->getUrlSuffixForOtherPages()
        );
    }

    /**
     * Get canonical URL of post
     *
     * @param PostInterface $post
     * @return string
     */
    public function getCanonicalUrl(PostInterface $post)
    {
        if ($category = $this->canonicalCategory->getCanonicalCategory($post)) {
             return $this->getPostUrl($post, $category);
        }
        return $this->getPostUrl($post);
    }
}
