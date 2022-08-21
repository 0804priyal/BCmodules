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

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Interface MetadataInterface
 * @package Aheadworks\Blog\Model\UrlRewrite
 */
interface MetadataInterface
{
    /**#@+
     * Constants defined for keys of the data array.
     * Identical to the name of the getter in snake case
     */
    const STORE_IDS = 'store_ids';
    const OLD_VALUE = 'old_value';
    const CURRENT_VALUE = 'current_value';
    const ENTITY_TYPE = 'entity_type';
    const ENTITY_OBJECT = 'entity_object';
    const ROUTE_TYPE = 'route_type';
    const OLD_URL = 'old_url';
    const NEW_URL = 'new_url';
    const ORIGINAL_URL_KEY = 'original_url_key';
    const BLOG_ROUTE = 'blog_route';
    /**#@-*/

    /**
     * Get store ids
     *
     * @return array|null
     */
    public function getStoreIds();

    /**
     * Set store ids
     *
     * @param array $storeIds
     * @return $this
     */
    public function setStoreIds($storeIds);

    /**
     * Get old value
     *
     * @return string|null
     */
    public function getOldValue();

    /**
     * Set old value
     *
     * @param string $value
     * @return $this
     */
    public function setOldValue($value);

    /**
     * Get current value
     *
     * @return string|null
     */
    public function getCurrentValue();

    /**
     * Set current value
     *
     * @param string $value
     * @return $this
     */
    public function setCurrentValue($value);

    /**
     * Get entity type
     *
     * @return string|null
     */
    public function getEntityType();

    /**
     * Set entity type
     *
     * @param string $entityType
     * @return $this
     */
    public function setEntityType($entityType);

    /**
     * Get route type
     *
     * @return string|null
     */
    public function getRouteType();

    /**
     * Set route type
     *
     * @param string $entityType
     * @return $this
     */
    public function setRouteType($entityType);

    /**
     * Get entity object
     *
     * @return AuthorInterface|PostInterface|CategoryInterface|null
     */
    public function getEntityObject();

    /**
     * Set entity object
     *
     * @param AuthorInterface|PostInterface|CategoryInterface $entityObject
     * @return $this
     */
    public function setEntityObject($entityObject);

    /**
     * Get old url
     *
     * @return string|null
     */
    public function getOldUrl();

    /**
     * Set old url
     *
     * @param string $url
     * @return $this
     */
    public function setOldUrl($url);

    /**
     * Get new url
     *
     * @return string|null
     */
    public function getNewUrl();

    /**
     * Set new url
     *
     * @param string $url
     * @return $this
     */
    public function setNewUrl($url);

    /**
     * Get original url key
     *
     * @return string|null
     */
    public function getOriginalUrlKey();

    /**
     * Set original url key
     *
     * @param string $urlKey
     * @return $this
     */
    public function setOriginalUrlKey($urlKey);

    /**
     * Get blog route
     *
     * @return string|null
     */
    public function getBlogRoute();

    /**
     * Set blog route
     *
     * @param string $route
     * @return $this
     */
    public function setBlogRoute($route);
}
