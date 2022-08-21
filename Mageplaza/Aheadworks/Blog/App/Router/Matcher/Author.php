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

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\App\Router\MatcherInterface;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\AuthorFactory;

/**
 * Class Author
 * @package Aheadworks\Blog\App\Router\Matcher
 */
class Author implements MatcherInterface
{
    /**
     * @var AuthorFactory
     */
    private $authorFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param AuthorFactory $authorFactory
     * @param Config $config
     */
    public function __construct(AuthorFactory $authorFactory, Config $config)
    {
        $this->authorFactory = $authorFactory;
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

        list(, $urlKey, $authorUrlKey) = array_merge($parts, array_fill(0, 3, null));
        $authorPageSuffix = $this->config->getAuthorUrlSuffix();
        if (!empty($authorPageSuffix) && $authorPageSuffix != '/') {
            $authorUrlKey = substr($authorUrlKey, 0, strrpos($authorUrlKey, $authorPageSuffix));
        }

        if ($urlKey == $this->config->getRouteToAuthors() && $authorId = $this->getAuthorIdByUrlKey($authorUrlKey)) {
            $request
                ->setControllerName('author')
                ->setActionName('view')
                ->setParams(['author_id' => $authorId]);

            return true;
        }

        return false;
    }

    /**
     * Retrieves author ID by URL-Key
     *
     * @param string $urlKey
     * @return int|null
     */
    private function getAuthorIdByUrlKey($urlKey)
    {
        /** @var \Aheadworks\Blog\Model\Author $authorModel */
        $authorModel = $this->authorFactory->create();
        $authorModel->load($urlKey, AuthorInterface::URL_KEY);
        return $authorModel->getId();
    }

    /**
     * Check if length of parts array is invalid
     *
     * @param array $parts
     * @return bool
     */
    private function isParamsQtyInvalid($parts)
    {
        $expectedPartsCountForAuthorRoute = 3;
        return $expectedPartsCountForAuthorRoute !== count($parts);
    }
}
