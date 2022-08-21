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
namespace Aheadworks\Blog\Model\Config\Backend;

use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Magento\Framework\App\Config\Data\ProcessorInterface;
use Magento\Framework\App\Config\Value;
use Aheadworks\Blog\Model\UrlRewrite\Manager\Route as RouteManager;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Data\Collection\AbstractDb;
use Aheadworks\Blog\Model\UrlRewrite\MetadataInterfaceFactory;
use Magento\Store\Model\ScopeInterface as StoreScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\Website;
use Aheadworks\Blog\Model\Source\UrlRewrite\RouteType;

/**
 * Class AbstractRoute
 * @package Aheadworks\Blog\Model\Config\Backend
 */
abstract class AbstractRoute extends Value implements ProcessorInterface
{
    /**
     * Blog route fieldset index
     */
    const BLOG_ROUTE = 'route_to_blog';

    /**
     * @var RouteManager
     */
    private $routeManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var MetadataInterfaceFactory
     */
    protected $metadataFactory;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param ScopeConfigInterface $config
     * @param TypeListInterface $cacheTypeList
     * @param RouteManager $routeManager
     * @param StoreManagerInterface $storeManager
     * @param MetadataInterfaceFactory $metadataFactory
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        RouteManager $routeManager,
        StoreManagerInterface $storeManager,
        MetadataInterfaceFactory $metadataFactory,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $config,
            $cacheTypeList,
            $resource,
            $resourceCollection,
            $data
        );
        $this->routeManager = $routeManager;
        $this->storeManager = $storeManager;
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * Post suffix before save
     *
     * @return void
     * @throws LocalizedException
     */
    public function beforeSave()
    {
        if ($this->isValueChanged()) {
            /** @var MetadataInterface $metadata */
            $metadata = $this->metadataFactory->create();
            $metadata->setRouteType($this->getRouteType())
                ->setCurrentValue($this->getValue())
                ->setOldValue($this->getOldValue())
                ->setStoreIds($this->getStoreIds());
            if ($this->getRouteType() == RouteType::TYPE_AUTHOR) {
                $metadata->setBlogRoute($this->getFieldsetDataValue(self::BLOG_ROUTE));
            }
            $this->routeManager->update($metadata);
        }

        parent::beforeSave();
    }

    /**
     * Process config value
     *
     * @param string $value
     * @return string
     */
    public function processValue($value)
    {
        return $value;
    }

    /**
     * Get route type
     *
     * @return string
     */
    abstract public function getRouteType();

    /**
     * Retrieve store ids, which use current config value
     *
     * @return array
     */
    protected function getStoreIds()
    {
        try {
            $storeIds = $websites = $excludedWebsiteIds = [];
            if ($this->getScope() == StoreScopeInterface::SCOPE_WEBSITES) {
                /** @var Website $currentScopeWebsite */
                $currentScopeWebsite = $this->storeManager->getWebsite($this->getScopeId());
                $websites = [$currentScopeWebsite];
            } elseif ($this->getScope() == ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
                /** @var Website[] $websites */
                $websites = $this->storeManager->getWebsites();
                $excludedWebsiteIds = $this->getOverrideWebsiteIds($websites);
            }
            foreach ($websites as $website) {
                if (false === array_search($website->getWebsiteId(), $excludedWebsiteIds)) {
                    $storeIds = array_merge($storeIds, $website->getStoreIds());
                }
            }
        } catch (LocalizedException $exception) {
            $storeIds = [];
        }
        return $storeIds;
    }

    /**
     * Return website ids, which use own overridden config value
     *
     * @param Website[] $websites
     * @return Website[]
     */
    protected function getOverrideWebsiteIds($websites)
    {
        $excludedIds = [];
        foreach ($websites as $website) {
            $websiteConfigValue = $this->_config->getValue(
                $this->getPath(),
                StoreScopeInterface::SCOPE_WEBSITE,
                $website->getWebsiteId()
            );
            if ($websiteConfigValue != $this->getOldValue()) {
                $excludedIds[] = $website->getWebsiteId();
            }
        }
        return $excludedIds;
    }
}
