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

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\TagRepositoryInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Aheadworks\Blog\Model\Post\Provider as PostProvider;
use Aheadworks\Blog\Model\Template\FilterProvider;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class PostPreview
 * @package Aheadworks\Blog\Block
 */
class PostPreview extends Post implements IdentityInterface
{
    /**
     * @var string
     */
    protected $_template = 'postpreview.phtml';

    /**
     * @var PostProvider
     */
    private $postProvider;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param TagRepositoryInterface $tagRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Config $config
     * @param LinkFactory $linkFactory
     * @param Url $url
     * @param FilterProvider $templateFilterProvider
     * @param FeaturedImageInfo $imageInfo
     * @param PostProvider $postProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface $tagRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $config,
        LinkFactory $linkFactory,
        Url $url,
        FilterProvider $templateFilterProvider,
        FeaturedImageInfo $imageInfo,
        PostProvider $postProvider,
        array $data = []
    ) {
        $this->postProvider = $postProvider;
        parent::__construct(
            $context,
            $postRepository,
            $categoryRepository,
            $tagRepository,
            $searchCriteriaBuilder,
            $config,
            $linkFactory,
            $url,
            $templateFilterProvider,
            $imageInfo,
            $data
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        parent::_construct();
        $post = $this->postProvider->getByEncriptedData($this->getRequest()->getParam('data'));
        $this->setPost($post);
    }
}
