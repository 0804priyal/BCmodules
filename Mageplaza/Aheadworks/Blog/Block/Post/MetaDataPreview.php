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
namespace Aheadworks\Blog\Block\Post;

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Post\MetadataProvider;
use Aheadworks\Blog\Model\Post\Provider as PostProvider;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class MetaDataPreview
 * @package Aheadworks\Blog\Block\Post
 */
class MetaDataPreview extends MetaData
{
    /**
     * @var PostProvider
     */
    private $postProvider;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param MetadataProvider $metadataProvider
     * @param CategoryRepositoryInterface $categoryRepository
     * @param PostProvider $postProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        MetadataProvider $metadataProvider,
        CategoryRepositoryInterface $categoryRepository,
        PostProvider $postProvider,
        array $data = []
    ) {
        $this->postProvider = $postProvider;
        parent::__construct(
            $context,
            $postRepository,
            $metadataProvider,
            $categoryRepository,
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
