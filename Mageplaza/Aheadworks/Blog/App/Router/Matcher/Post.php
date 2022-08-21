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

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\App\Router\MatcherInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\PostFactory;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Config\Seo\UrlType;

/**
 * Class Post
 * @package Aheadworks\Blog\App\Router\Matcher
 */
class Post implements MatcherInterface
{
    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param PostFactory $postFactory
     * @param Config $config
     */
    public function __construct(
        PostFactory $postFactory,
        Config $config
    ) {
        $this->postFactory = $postFactory;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     * @param RequestInterface|Http $request
     */
    public function match(RequestInterface $request)
    {
        $parts = explode('/', trim($request->getPathInfo(), '/'));
        if ($this->isParamsQtyInvalid($parts)) {
            return false;
        }
        list(, $urlKey) = array_merge($parts, array_fill(0, 3, null));
        $postPageSuffix = $this->config->getPostUrlSuffix();
        if (!empty($postPageSuffix) && $postPageSuffix != '/') {
            $urlKey = substr($urlKey, 0, strrpos($urlKey, $postPageSuffix));
        }

        if ($postId = $this->getPostIdByUrlKey($urlKey)) {
            $request
                ->setControllerName('post')
                ->setActionName('view')
                ->setParams(['post_id' => $postId]);
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
     * Check if length of parts array is invalid
     *
     * @param array $parts
     * @return bool
     */
    private function isParamsQtyInvalid($parts)
    {
        $expectedPartsCountForPostRoute = 2;
        return $expectedPartsCountForPostRoute !== count($parts);
    }
}
