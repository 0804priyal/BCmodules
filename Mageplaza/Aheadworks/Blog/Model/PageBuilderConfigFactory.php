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

use Magento\Framework\ObjectManagerInterface;
use Magento\PageBuilder\Model\Config;

/**
 * Class PageBuilderConfigFactory
 * @package Aheadworks\Blog\Model
 */
class PageBuilderConfigFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create page builder config factory instance
     *
     * @return Config|null
     */
    public function create()
    {
        if (class_exists(Config::class)) {
            return $this->objectManager->create(Config::class);
        }

        return null;
    }
}
