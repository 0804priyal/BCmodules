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
namespace Aheadworks\Blog\Block;

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\DisqusConfig;
use Magento\Framework\View\Element\Template\Context;

/**
 * Disqus integration block
 *
 * @method int getPageIdentifier()
 * @method string getPageUrl()
 * @method string getPageTitle()
 *
 * @method $this setPageIdentifier(int)
 * @method $this setPageUrl(string)
 * @method $this setPageTitle(string)
 *
 * @package Aheadworks\Blog\Block
 */
class Disqus extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DisqusConfig
     */
    private $disqusConfig;

    /**
     * @param Context $context
     * @param Config $config
     * @param DisqusConfig $disqusConfig
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        DisqusConfig $disqusConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->disqusConfig = $disqusConfig;
    }

    /**
     * @return bool
     */
    public function isCommentsEnabled()
    {
        return $this->config->isCommentsEnabled();
    }

    /**
     * @return string
     */
    public function getCountScriptUrl()
    {
        return '\/\/' . $this->stripTags($this->disqusConfig->getForumCode()) . '.disqus.com/count.js';
    }

    /**
     * @return string
     */
    public function getEmbedScriptUrl()
    {
        return '\/\/' . $this->stripTags($this->disqusConfig->getForumCode()) . '.disqus.com/embed.js';
    }
}
