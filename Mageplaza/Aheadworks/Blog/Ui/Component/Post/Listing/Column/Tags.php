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
namespace Aheadworks\Blog\Ui\Component\Post\Listing\Column;

use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Class Tags
 * @package Aheadworks\Blog\Ui\Component\Post\Listing\Column
 */
class Tags extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @inheritdoc
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $post) {
                if (is_array($post[PostInterface::TAG_NAMES])) {
                    $post['tags'] = implode(', ', $post[PostInterface::TAG_NAMES]);
                }
            }
        }
        return $dataSource;
    }
}
