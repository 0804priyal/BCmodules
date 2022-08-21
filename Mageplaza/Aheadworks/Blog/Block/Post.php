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

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\TagRepositoryInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Image\Info;
use Aheadworks\Blog\Model\Source\Category\Status as CategoryStatus;
use Aheadworks\Blog\Model\Source\Config\Related\BlockPosition;
use Aheadworks\Blog\Model\Source\Post\SharingButtons\DisplayAt as DisplaySharingAt;
use Aheadworks\Blog\Model\Template\FilterProvider;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Aheadworks\Blog\Model\Source\Config\Seo\UrlType;
use Magento\Framework\View\Element\Template;

/**
 * Post view/list item block
 *
 * @method bool hasPost()
 * @method bool hasMode()
 * @method PostInterface getPost()
 * @method string getMode()
 * @method string getSocialIconsBlock()
 *
 * @method Post setPost(PostInterface $post)
 * @method Post setMode(string $mode)
 *
 * @package Aheadworks\Blog\Block
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Post extends Template implements IdentityInterface
{
    const MODE_LIST_ITEM = 'list_item';
    const MODE_VIEW = 'view';

    /**
     * @var string
     */
    protected $_template = 'post.phtml';

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var TagRepositoryInterface
     */
    protected $tagRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var LinkFactory
     */
    protected $linkFactory;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var FilterProvider
     */
    protected $templateFilterProvider;

    /**
     * @var FeaturedImageInfo
     */
    protected $imageInfo;

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
        array $data = []
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->config = $config;
        $this->linkFactory = $linkFactory;
        $this->url = $url;
        $this->templateFilterProvider = $templateFilterProvider;
        $this->imageInfo = $imageInfo;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $postId = $this->getRequest()->getParam('post_id');
        if (!$this->hasPost() && $postId) {
            $this->setPost($this->postRepository->get($postId));
        }
        if (!$this->hasMode()) {
            $this->setMode(self::MODE_VIEW);
        }
    }

    /**
     * Check whether block in list item mode
     *
     * @return bool
     */
    public function isListItemMode()
    {
        return $this->getMode() == self::MODE_LIST_ITEM;
    }

    /**
     * Check whether block in view mode
     *
     * @return bool
     */
    public function isViewMode()
    {
        return $this->getMode() == self::MODE_VIEW;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->isViewMode()) {
            /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs) {
                $breadcrumbs->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'link' => $this->_storeManager->getStore()->getBaseUrl()
                    ]
                );
                $breadcrumbs->addCrumb(
                    'blog_home',
                    [
                        'label' => $this->config->getBlogTitle(),
                        'link' => $this->url->getBlogHomeUrl(),
                    ]
                );
                if ($categoryId = $this->getRequest()->getParam('blog_category_id')) {
                    $category = $this->categoryRepository->get($categoryId);
                    $breadcrumbs->addCrumb(
                        'category_view',
                        [
                            'label' => $category->getName(),
                            'link' => $this->url->getCategoryUrl($category)
                        ]
                    );
                }
                if ($postId = $this->getRequest()->getParam('post_id')) {
                    $post = $this->postRepository->get($postId);
                    $breadcrumbs->addCrumb('post_view', ['label' => $post->getTitle()]);
                }
            }
        }
        return $this;
    }

    /**
     * Get post categories
     *
     * @return CategoryInterface[]
     */
    protected function getCategories()
    {
        $this->searchCriteriaBuilder
            ->addFilter(CategoryInterface::STATUS, CategoryStatus::ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $this->_storeManager->getStore()->getId())
            ->addFilter(CategoryInterface::ID, $this->getPost()->getCategoryIds(), 'in');
        return $this->categoryRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }

    /**
     * Get post tags
     *
     * @return TagInterface[]
     */
    protected function getTags()
    {
        $this->searchCriteriaBuilder->addFilter(TagInterface::NAME, $this->getPost()->getTagNames(), 'in');
        return $this->tagRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }

    /**
     * @return bool
     */
    public function commentsEnabled()
    {
        return $this->config->isCommentsEnabled() &&
            $this->getPost()->getIsAllowComments();
    }

    /**
     * @param PostInterface $post
     * @return bool
     */
    public function showReadMoreButton(PostInterface $post)
    {
        return $this->isListItemMode() && $post->getShortContent();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSocialIconsHtml()
    {
        $displayAt = $this->config->getDisplaySharingAt();
        if ($this->isListItemMode() && in_array(DisplaySharingAt::POST_LIST, $displayAt)
            || $this->isViewMode() && in_array(DisplaySharingAt::POST, $displayAt)
        ) {
            $block = $this->getLayout()->createBlock(
                $this->getSocialIconsBlock(),
                '',
                [
                    'data' => [
                        'post' => $this->getPost()
                    ]
                ]
            );
            return $block->toHtml();
        }
        return '';
    }

    /**
     * Retrieves array of category links html
     *
     * @return string[]
     */
    public function getCategoryLinks()
    {
        $categoryLinks = [];
        foreach ($this->getCategories() as $category) {
            /** @var Link $link */
            $link = $this->linkFactory->create();
            $categoryLinks[] = $link
                ->setHref($this->url->getCategoryUrl($category))
                ->setTitle($category->getName())
                ->setLabel($category->getName())
                ->toHtml();
        }
        return $categoryLinks;
    }

    /**
     * Retrieves Disqus embed code html
     *
     * @return string
     */
    public function getDisqusEmbedHtml()
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Disqus $disqusEmbed */
        $disqusEmbed = $this->getChildBlock('disqus_embed');
        if ($disqusEmbed) {
            $post = $this->getPost();
            $html = $disqusEmbed
                ->setPageIdentifier($post->getId())
                ->setPageUrl($this->getPostUrl($post))
                ->setPageTitle($post->getTitle())
                ->toHtml();
        }
        return $html;
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getContent(PostInterface $post)
    {
        $content = $post->getContent();
        if ($this->isListItemMode() && $post->getShortContent()) {
            $content = $post->getShortContent();
        }
        return $this->templateFilterProvider->getFilter()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->filter($content);
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getPostUrl(PostInterface $post)
    {
        $categoryId = $this->getRequest()->getParam('blog_category_id');
        $storeId = $this->_storeManager->getStore()->getId();
        $canIncludeCategory = $this->config->getSeoUrlType($storeId) == UrlType::URL_INC_CATEGORY ? true : false;
        if ($canIncludeCategory && $categoryId) {
            return $this->url->getPostUrl($post, $this->categoryRepository->get($categoryId));
        }
        return $this->url->getPostUrl($post);
    }

    /**
     * @param TagInterface|string $tag
     * @return string
     */
    public function getSearchByTagUrl($tag)
    {
        return $this->url->getSearchByTagUrl($tag);
    }

    /**
     * Retrieves Related product block code html
     *
     * @param bool $viewMode
     * @param string $blockPosition
     * @return string
     */
    public function getRelatedProductHtml($viewMode, $blockPosition)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\RelatedProduct $postRelatedProduct */
        $postRelatedProduct = $this->getChildBlock('post_related_product');
        if ($viewMode && $postRelatedProduct && $this->config->getRelatedBlockPosition() == $blockPosition) {
            $post = $this->getPost();
            $html = $postRelatedProduct
                ->setPost($post)
                ->toHtml();
        }
        return $html;
    }

    /**
     * Retrieves related post block code html
     *
     * @param bool $viewMode
     * @return string
     */
    public function getRelatedPostHtml($viewMode)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Post\RelatedPost $relatedPostBlock */
        $relatedPostBlock = $this->getChildBlock('related_post');
        if ($viewMode) {
            $post = $this->getPost();
            $html = $relatedPostBlock
                ->setPost($post)
                ->toHtml();
        }

        return $html;
    }

    /**
     * Retrieves prev next block code html
     *
     * @param bool $viewMode
     * @return string
     */
    public function getPrevNextHtml($viewMode)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Post\PrevNext $prevNextBlock */
        $prevNextBlock = $this->getChildBlock('prev_next');
        if ($viewMode) {
            $post = $this->getPost();
            $html = $prevNextBlock
                ->setPost($post)
                ->toHtml();
        }

        return $html;
    }

    /**
     * Retrieve after post position
     *
     * @return string
     */
    public function getPositionAfterPost()
    {
        return BlockPosition::AFTER_POST;
    }

    /**
     * Retrieve after comments position
     *
     * @return string
     */
    public function getPositionAfterComments()
    {
        return BlockPosition::AFTER_COMMENTS;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $identities = [\Aheadworks\Blog\Model\Post::CACHE_TAG . '_' . $this->getPost()->getId()];
        if (is_array($this->getPost()->getCategoryIds())) {
            foreach ($this->getPost()->getCategoryIds() as $categoryId) {
                $identities[] = \Aheadworks\Blog\Model\Category::CACHE_TAG . '_' . $categoryId;
            }
        }
        foreach ($this->getTags() as $tag) {
            $identities[] = \Aheadworks\Blog\Model\Tag::CACHE_TAG . '_' . $tag->getId();
        }
        return $identities;
    }

    /**
     * Check if featured image is loaded
     *
     * @return bool
     */
    public function isFeaturedImageLoaded()
    {
        return $this->getPost()->getFeaturedImageFile() ? true : false;
    }

    /**
     * Get featured image url
     *
     * @return string
     */
    public function getFeaturedImageUrl()
    {
        return $this->imageInfo->getImageUrl($this->getPost()->getFeaturedImageFile());
    }

    /**
     * Get image url
     *
     * @return string
     */
    public function getAuthorImageUrl()
    {
        try {
            $url = $this->imageInfo->getMediaUrl() . Info::FILE_DIR . '/'
                .  $this->getPost()->getAuthor()->getImageFile();
        } catch (NoSuchEntityException $e) {
            $url = '';
        }

        return $url;
    }

    /**
     * Retrieve author full name
     *
     * @return string
     */
    public function getAuthorFullname()
    {
        $author = $this->getPost()->getAuthor();

        return $author->getFirstname() . ' ' . $author->getLastname();
    }

    /**
     * Retrieve author image alt
     *
     * @return string
     */
    public function getAuthorImageAlt()
    {
        return __('A photo of %1', $this->getAuthorFullname());
    }

    /**
     * Retrieve author ulr
     *
     * @return string
     */
    public function getAuthorUrl()
    {
        $author = $this->getPost()->getAuthor();

        return $this->url->getAuthorUrl($author);
    }
}
