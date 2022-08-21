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

use Aheadworks\Blog\App\Router\MatcherInterface;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\AuthorFactory;

/**
 * Class AuthorsList
 * @package Aheadworks\Blog\App\Router\Matcher
 */
class AuthorsList implements MatcherInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
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
        $authorsListPageSuffix = $this->config->getUrlSuffixForOtherPages();
        if (!empty($authorsListPageSuffix) && $authorsListPageSuffix != '/') {
            $urlKey = substr($urlKey, 0, strrpos($urlKey, $authorsListPageSuffix));
        }

        if ($urlKey == $this->config->getRouteToAuthors()) {
            $request
                ->setControllerName('author')
                ->setActionName('list');

            return true;
        }

        return false;
    }

    /**
     * Check if length of parts array is invalid
     *
     * @param array $parts
     * @return bool
     */
    private function isParamsQtyInvalid($parts)
    {
        $expectedPartsCountForAuthorsListRoute = 2;
        return $expectedPartsCountForAuthorsListRoute !== count($parts);
    }
}
