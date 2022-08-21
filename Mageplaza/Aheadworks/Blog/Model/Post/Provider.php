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
namespace Aheadworks\Blog\Model\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\PostInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Aheadworks\Blog\Model\Encryptor;

/**
 * Class Provider
 * @package Aheadworks\Blog\Model\Post
 */
class Provider
{
    /**
     * @var PostInterfaceFactory
     */
    private $postFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @param Encryptor $encryptor
     * @param DataObjectHelper $dataObjectHelper
     * @param PostInterfaceFactory $postFactory
     */
    public function __construct(
        Encryptor $encryptor,
        DataObjectHelper $dataObjectHelper,
        PostInterfaceFactory $postFactory
    ) {
        $this->encryptor = $encryptor;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->postFactory = $postFactory;
    }

    /**
     * Get post by encripted data
     *
     * @param string $data
     * @return PostInterface
     */
    public function getByEncriptedData($data)
    {
        $postData = $this->encryptor->decrypt($data);
        $post = $this->postFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $post,
            $postData,
            PostInterface::class
        );

        return $post;
    }
}
