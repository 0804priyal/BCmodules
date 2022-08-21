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
namespace Aheadworks\Blog\ViewModel\Post;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\StructuredData\ProviderInterface as PostStructuredDataProviderInterface;

/**
 * Class StructuredData
 *
 * @package Aheadworks\Blog\ViewModel\Post
 */
class StructuredData implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var PostStructuredDataProviderInterface
     */
    protected $postStructuredDataProvider;

    /**
     * @param RequestInterface $request
     * @param PostRepositoryInterface $postRepository
     * @param PostStructuredDataProviderInterface $postStructuredDataProvider
     */
    public function __construct(
        RequestInterface $request,
        PostRepositoryInterface $postRepository,
        PostStructuredDataProviderInterface $postStructuredDataProvider
    ) {
        $this->request = $request;
        $this->postRepository = $postRepository;
        $this->postStructuredDataProvider = $postStructuredDataProvider;
    }

    /**
     * Retrieve structured data array for current post
     *
     * @return array
     */
    public function getStructuredDataForCurrentPost()
    {
        $data = [];
        $currentPost = $this->getCurrentPost();
        if ($currentPost) {
            $data = $this->postStructuredDataProvider->getData($currentPost);
        }
        return $data;
    }

    /**
     * Retrieve current post
     *
     * @return PostInterface|null
     */
    protected function getCurrentPost()
    {
        $postId = $this->request->getParam('post_id');
        try {
            $currentPost = $this->postRepository->get($postId);
        } catch (\Exception $e) {
            $currentPost = null;
        }
        return $currentPost;
    }
}
