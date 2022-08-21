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

use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\App\Router\MatcherInterface;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\TagFactory;

/**
 * Class Tag
 * @package Aheadworks\Blog\App\Router\Matcher
 */
class Tag implements MatcherInterface
{
    /**
     * @var string
     */
    const TAG_KEY = 'tag';

    /**
     * @var TagFactory
     */
    private $tagFactory;

    /**
     * @var Config
     */
    private $config;

    /**
     * @param TagFactory $tagFactory
     * @param Config $config
     */
    public function __construct(
        TagFactory $tagFactory,
        Config $config
    ) {
        $this->tagFactory = $tagFactory;
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

        list(, $urlKey, $tagName) = array_merge($parts, array_fill(0, 3, null));
        $searchByTagPageSuffix = $this->config->getUrlSuffixForOtherPages();
        if (!empty($searchByTagPageSuffix) && $searchByTagPageSuffix != '/') {
            $tagName = substr($tagName, 0, strrpos($tagName, $searchByTagPageSuffix));
        }

        $tagId = $this->getTagIdByName(urldecode($tagName));
        if ($urlKey == self::TAG_KEY && $tagId) {
            $request
                ->setControllerName('index')
                ->setActionName('index')
                ->setParams(['tag_id' => $tagId]);

            return true;
        }

        return false;
    }

    /**
     * Retrieves tag ID by name
     *
     * @param string $tagName
     * @return int|null
     */
    private function getTagIdByName($tagName)
    {
        /** @var \Aheadworks\Blog\Model\Tag $tagModel */
        $tagModel = $this->tagFactory->create();
        $tagModel->load($tagName, TagInterface::NAME);
        return $tagModel->getId();
    }

    /**
     * Check if length of parts array is invalid
     *
     * @param array $parts
     * @return bool
     */
    private function isParamsQtyInvalid($parts)
    {
        $expectedPartsCountForSearchByTagRoute = 3;
        return $expectedPartsCountForSearchByTagRoute !== count($parts);
    }
}
