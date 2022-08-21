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

use Aheadworks\Blog\Model\UrlRewrite\MetadataInterface;
use Aheadworks\Blog\Model\UrlRewrite\Updater\Suffix as SuffixUpdater;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Config;

/**
 * Class Suffix
 * @package Aheadworks\Blog\Model\UrlRewrite\Manager
 */
class Suffix
{
    /**
     * @var SuffixUpdater
     */
    private $suffixUpdater;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param SuffixUpdater $suffixUpdater
     * @param Config $config
     */
    public function __construct(
        SuffixUpdater $suffixUpdater,
        Config $config
    ) {
        $this->suffixUpdater = $suffixUpdater;
        $this->config = $config;
    }

    /**
     * Update suffix
     *
     * @param MetadataInterface $metadata
     * @return void
     * @throws LocalizedException
     */
    public function update($metadata)
    {
        if ($this->config->getSaveRewritesHistory()) {
            foreach ($metadata->getStoreIds() as $storeId) {
                $this->suffixUpdater->update(
                    $storeId,
                    $metadata
                );
            }
        }
    }
}
