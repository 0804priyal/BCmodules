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
namespace Aheadworks\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Tag Cloud Item repository interface
 * @api
 */
interface TagCloudItemRepositoryInterface
{
    /**
     * Retrieve tag cloud item
     *
     * @param int $tagId
     * @param int $storeId
     * @return \Aheadworks\Blog\Api\Data\TagCloudItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($tagId, $storeId);

    /**
     * Retrieve tags cloud items matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param int $storeId
     * @return \Aheadworks\Blog\Api\Data\TagCloudItemSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria, $storeId);
}
