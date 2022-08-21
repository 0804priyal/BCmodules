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

use Aheadworks\Blog\Model\Source\UrlRewrite\RouteType;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Aheadworks\Blog\Model\UrlRewrite\Updater\Route as RouteUpdater;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Config;

/**
 * Class Route
 * @package Aheadworks\Blog\Model\UrlRewrite\Manager
 */
class Route
{
    /**
     * @var RouteUpdater
     */
    private $routeUpdater;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param RouteUpdater $routeUpdater
     * @param Config $config
     */
    public function __construct(
        RouteUpdater $routeUpdater,
        Config $config
    ) {
        $this->routeUpdater = $routeUpdater;
        $this->config = $config;
    }

    /**
     * Update route
     *
     * @param MetadataInterface $metadata
     * @return void
     * @throws LocalizedException
     */
    public function update($metadata)
    {
        if ($this->config->getSaveRewritesHistory()) {
            foreach ($metadata->getStoreIds() as $storeId) {
                if ($metadata->getRouteType() == RouteType::TYPE_BLOG) {
                    $this->routeUpdater->updateBlogRoute($storeId, $metadata);
                } elseif ($metadata->getRouteType() == RouteType::TYPE_AUTHOR) {
                    $this->routeUpdater->updateAuthorRoute($storeId, $metadata);
                }
            }
        }
    }
}
