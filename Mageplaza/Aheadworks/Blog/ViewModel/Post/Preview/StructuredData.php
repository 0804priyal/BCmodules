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
namespace Aheadworks\Blog\ViewModel\Post\Preview;

use Aheadworks\Blog\ViewModel\Post\StructuredData as PostStructuredData;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Post\StructuredData\ProviderInterface as PostStructuredDataProviderInterface;
use Aheadworks\Blog\Model\Post\Provider as PostProvider;

/**
 * Class StructuredData
 *
 * @package Aheadworks\Blog\ViewModel\Post\Preview
 */
class StructuredData extends PostStructuredData
{
    /**
     * @var PostProvider
     */
    protected $postProvider;

    /**
     * @param RequestInterface $request
     * @param PostRepositoryInterface $postRepository
     * @param PostStructuredDataProviderInterface $postStructuredDataProvider
     * @param PostProvider $postProvider
     */
    public function __construct(
        RequestInterface $request,
        PostRepositoryInterface $postRepository,
        PostStructuredDataProviderInterface $postStructuredDataProvider,
        PostProvider $postProvider
    ) {
        parent::__construct(
            $request,
            $postRepository,
            $postStructuredDataProvider
        );
        $this->postProvider = $postProvider;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCurrentPost()
    {
        $currentPost = null;
        $data = $this->request->getParam('data');
        if (is_string($data)) {
            $currentPost = $this->postProvider->getByEncriptedData($data);
        }
        return $currentPost;
    }
}
