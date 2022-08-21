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
namespace Aheadworks\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Post resource model
 * @package Aheadworks\Blog\Model\ResourceModel
 */
class Post extends AbstractDb
{
    /**#@+
     * Constants defined for tables
     */
    const BLOG_POST_TABLE = 'aw_blog_post';
    const BLOG_POST_CATEGORY_TABLE = 'aw_blog_post_category';
    const BLOG_POST_STORE_TABLE = 'aw_blog_post_store';
    const BLOG_POST_TAG_TABLE = 'aw_blog_post_tag';
    /**#@-*/

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::BLOG_POST_TABLE, 'id');
    }

    /**
     * Load post by url key
     *
     * @param \Aheadworks\Blog\Model\Post $post
     * @param string $urlKey
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function loadByUrlKey(\Aheadworks\Blog\Model\Post $post, $urlKey)
    {
        $connection = $this->getConnection();
        $bind = ['url_key' => $urlKey];
        $select = $connection->select()
            ->from($this->getMainTable(), $this->getIdFieldName())
            ->where(
                'url_key = :url_key'
            );

        $postId = $connection->fetchOne($select, $bind);
        if ($postId) {
            $this->load($post, $postId);
        } else {
            $post->setData([]);
        }

        return $this;
    }
}
