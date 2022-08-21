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
namespace Aheadworks\Blog\ViewModel\Category;

use Aheadworks\Blog\Model\Image\Info as ImageInfo;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;

/**
 * Class Details
 *
 * @package Aheadworks\Blog\ViewModel\Category
 */
class Details implements ArgumentInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ImageInfo
     */
    private $imageInfo;

    /**
     * @param RequestInterface $request
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ImageInfo $imageInfo
     */
    public function __construct(
        RequestInterface $request,
        CategoryRepositoryInterface $categoryRepository,
        ImageInfo $imageInfo
    ) {
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
        $this->imageInfo = $imageInfo;
    }

    /**
     * Retrieve current category
     *
     * @return CategoryInterface|null
     */
    public function getCurrentCategory()
    {
        $categoryId = $this->request->getParam('blog_category_id');
        try {
            $currentCategory = $this->categoryRepository->get($categoryId);
        } catch (\Exception $e) {
            $currentCategory = null;
        }
        return $currentCategory;
    }

    /**
     * Check if need to display details block for specific category
     *
     * @param CategoryInterface|null $category
     * @return bool
     */
    public function isNeedToDisplayDetails($category)
    {
        return $category
            && (
                $this->isNeedToDisplayImage($category)
                || $this->isNeedToDisplayDescription($category)
            );
    }

    /**
     * Check if need to display image for specific category
     *
     * @param CategoryInterface $category
     * @return bool
     */
    public function isNeedToDisplayImage($category)
    {
        return !empty($category->getImageFileName());
    }

    /**
     * Retrieve image URL for specific category
     *
     * @param CategoryInterface $category
     * @return string
     */
    public function getImageUrl($category)
    {
        try {
            $url = $this->imageInfo->getMediaUrl($category->getImageFileName());
        } catch (NoSuchEntityException $e) {
            $url = '';
        }

        return $url;
    }

    /**
     * Check if need to display description for specific category
     *
     * @param CategoryInterface $category
     * @return bool
     */
    public function isNeedToDisplayDescription($category)
    {
        return $category->getIsDescriptionEnabled();
    }
}
