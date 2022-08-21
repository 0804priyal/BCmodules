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
namespace Aheadworks\Blog\Model\UrlRewrite;

use Magento\Framework\DataObject;

/**
 * Class Metadata
 * @package Aheadworks\Blog\Model\UrlRewrite
 */
class Metadata extends DataObject implements MetadataInterface
{
    /**
     * {@inheritDoc}
     */
    public function getStoreIds()
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * {@inheritDoc}
     */
    public function setStoreIds($storeIds)
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * {@inheritDoc}
     */
    public function getOldValue()
    {
        return $this->getData(self::OLD_VALUE);
    }

    /**
     * {@inheritDoc}
     */
    public function setOldValue($value)
    {
        return $this->setData(self::OLD_VALUE, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrentValue()
    {
        return $this->getData(self::CURRENT_VALUE);
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrentValue($value)
    {
        return $this->setData(self::CURRENT_VALUE, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityType()
    {
        return $this->getData(self::ENTITY_TYPE);
    }

    /**
     * {@inheritDoc}
     */
    public function setEntityType($entityType)
    {
        return $this->setData(self::ENTITY_TYPE, $entityType);
    }

    /**
     * {@inheritDoc}
     */
    public function getRouteType()
    {
        return $this->getData(self::ROUTE_TYPE);
    }

    /**
     * {@inheritDoc}
     */
    public function setRouteType($routeType)
    {
        return $this->setData(self::ROUTE_TYPE, $routeType);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityObject()
    {
        return $this->getData(self::ENTITY_OBJECT);
    }

    /**
     * {@inheritDoc}
     */
    public function setEntityObject($entityObject)
    {
        return $this->setData(self::ENTITY_OBJECT, $entityObject);
    }

    /**
     * {@inheritDoc}
     */
    public function getOldUrl()
    {
        return $this->getData(self::OLD_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function setOldUrl($url)
    {
        return $this->setData(self::OLD_URL, $url);
    }

    /**
     * {@inheritDoc}
     */
    public function getNewUrl()
    {
        return $this->getData(self::NEW_URL);
    }

    /**
     * {@inheritDoc}
     */
    public function setNewUrl($url)
    {
        return $this->setData(self::NEW_URL, $url);
    }

    /**
     * {@inheritDoc}
     */
    public function getOriginalUrlKey()
    {
        return $this->getData(self::ORIGINAL_URL_KEY);
    }

    /**
     * {@inheritDoc}
     */
    public function setOriginalUrlKey($urlKey)
    {
        return $this->setData(self::ORIGINAL_URL_KEY, $urlKey);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlogRoute()
    {
        return $this->getData(self::BLOG_ROUTE);
    }

    /**
     * {@inheritDoc}
     */
    public function setBlogRoute($route)
    {
        return $this->setData(self::BLOG_ROUTE, $route);
    }
}
