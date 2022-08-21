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
namespace Aheadworks\Blog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for post search results
 * @api
 */
interface PostSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get posts list
     *
     * @return \Aheadworks\Blog\Api\Data\PostInterface[]
     */
    public function getItems();

    /**
     * Set posts list
     *
     * @param \Aheadworks\Blog\Api\Data\PostInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
