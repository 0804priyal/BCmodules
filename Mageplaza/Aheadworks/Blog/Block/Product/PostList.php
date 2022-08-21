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
namespace Aheadworks\Blog\Block\Product;

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Url;
use Aheadworks\Blog\Block\Post\Listing as PostListing;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Magento\Framework\View\Element\Template;

/**
 * Class PostList
 *
 * @package Aheadworks\Blog\Block\Product
 */
class PostList extends Template implements IdentityInterface
{
    /**
     * Path to template file in theme
     * @var string
     */
    protected $_template = 'Aheadworks_Blog::product/post/list.phtml';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var PostListing
     */
    private $postListing;

    /**
     * @var FeaturedImageInfo
     */
    private $imageInfo;

    /**
     * @param Context $context
     * @param Config $config
     * @param Url $url
     * @param PostListing $postListing
     * @param FeaturedImageInfo $imageInfo
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        Url $url,
        PostListing $postListing,
        FeaturedImageInfo $imageInfo,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->url = $url;
        $this->postListing = $postListing;
        $this->imageInfo = $imageInfo;
        if ($this->config->isDisplayPostsOnProductPage()) {
            $this->addTitle();
        }
    }

    /**
     * Retrieve product posts
     *
     * @return array|null
     */
    public function getPosts()
    {
        if ($this->config->isDisplayPostsOnProductPage()) {
            if ($productId = $this->getRequest()->getParam('id')) {
                $this->postListing->getSearchCriteriaBuilder()->addFilter(
                    'product_id',
                    $productId
                );
            }
            return $this->postListing->getPosts();
        }
        return [];
    }

    /**
     * Retrieve post url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getPostUrl(PostInterface $post)
    {
        return $this->url->getPostUrl($post);
    }

    /**
     * Check if featured image is loaded
     *
     * @param PostInterface $post
     * @return bool
     */
    public function isFeaturedImageLoaded(PostInterface $post)
    {
        return $post->getFeaturedImageFile() ? true : false;
    }

    /**
     * Get featured image url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getFeaturedImageUrl(PostInterface $post)
    {
        return $this->imageInfo->getImageUrl($post->getFeaturedImageFile());
    }

    /**
     * Add title
     */
    public function addTitle()
    {
        $title = __('Blog Posts');

        if ($postsCount = count($this->getPosts())) {
            $title = __('Blog Posts (%1)', $postsCount);
        }
        $this->setData('title', $title);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $identities = [Post::CACHE_TAG_LISTING];
        foreach ($this->getPosts() as $post) {
            $identities = [Post::CACHE_TAG . '_' . $post->getId()];
        }

        return $identities;
    }
}
