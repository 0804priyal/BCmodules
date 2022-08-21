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
use Aheadworks\Blog\Model\UrlRewrite\Manager\Suffix as SuffixManager;
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

/**
 * Class AbstractSuffix
 * @package Aheadworks\Blog\Model\Config\Backend
 */
abstract class AbstractSuffix extends Value implements ProcessorInterface
{
    /**
     * @var SuffixManager
     */
    private $suffixManager;

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
     * @param SuffixManager $suffixManager
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
        SuffixManager $suffixManager,
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
        $this->suffixManager = $suffixManager;
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

            $metadata->setEntityType($this->getEntityType())
                ->setCurrentValue($this->getValue())
                ->setOldValue($this->getOldValue())
                ->setStoreIds($this->getStoreIds());
            $this->suffixManager->update($metadata);
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
     * Get entity type
     *
     * @return string
     */
    abstract public function getEntityType();

    /**
     * Retrieve store ids, which use current config value
     *
     * @return array
     */
    protected function getStoreIds()
    {
        try {
            if ($this->getScope() == StoreScopeInterface::SCOPE_STORES) {
                $storeIds = [$this->getScopeId()];
            } elseif ($this->getScope() == StoreScopeInterface::SCOPE_WEBSITES) {
                /** @var Website $website */
                $website = $this->storeManager->getWebsite($this->getScopeId());
                $storeIds = array_keys($website->getStoreIds());
                $storeIds = array_diff($storeIds, $this->getOverrideStoreIds($storeIds));
            } else {
                $storeIds = array_keys($this->storeManager->getStores());
                $storeIds = array_diff($storeIds, $this->getOverrideStoreIds($storeIds));
            }
        } catch (LocalizedException $exception) {
            $storeIds = [];
        }
        return array_values($storeIds);
    }

    /**
     * Return store ids, which use own overridden config value
     *
     * @param array $storeIds
     * @return array
     */
    protected function getOverrideStoreIds($storeIds)
    {
        $excludeIds = [];
        foreach ($storeIds as $storeId) {
            $requestPath = $this->_config->getValue(
                $this->getPath(),
                StoreScopeInterface::SCOPE_STORE,
                $storeId
            );
            if ($requestPath != $this->getOldValue()) {
                $excludeIds[] = $storeId;
            }
        }
        return $excludeIds;
    }
}
