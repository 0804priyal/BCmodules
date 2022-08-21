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
namespace Aheadworks\Blog\Setup\Updater;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\Author\Resolver as AuthorResolver;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Aheadworks\Blog\Model\ResourceModel\Author as ResourceAuthor;
use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;
use Aheadworks\Blog\Model\ResourceModel\Category as ResourceCategory;

/**
 * Class Schema
 * @package Aheadworks\Blog\Setup\Updater
 */
class Schema
{
    /**
     * @var AuthorResolver
     */
    private $authorResolver;

    /**
     * @param AuthorResolver $authorResolver
     */
    public function __construct(AuthorResolver $authorResolver)
    {
        $this->authorResolver = $authorResolver;
    }

    /**
     * Update to 2.6.0 version
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    public function update260(SchemaSetupInterface $setup)
    {
        $this
            ->addAuthorTable($setup)
            ->addColumnToPostTable($setup)
            ->addColumnToCategoryTable($setup)
            ->modifyPostAuthor($setup)
            ->dropColumnsFromPostTable($setup)
            ->resetCategorySortOrder($setup);

        return $this;
    }

    /**
     * Update to 2.7.0 version
     *
     * @param SchemaSetupInterface $setup
     * @return $this
     * @throws \Zend_Db_Exception
     */
    public function update270(SchemaSetupInterface $setup)
    {
        $this
            ->addColumnsToPostTable270($setup)
            ->addColumnsToCategoryTable270($setup)
            ->addColumnsToAuthorTable270($setup)
        ;

        return $this;
    }

    /**
     * Add author table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     * @throws \Zend_Db_Exception
     */
    private function addAuthorTable(SchemaSetupInterface $installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE))
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Author Id'
            )->addColumn(
                'firstname',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Author First Name'
            )->addColumn(
                'lastname',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Author Last Name'
            )->addColumn(
                'url_key',
                Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'URL-Key'
            )->addColumn(
                'job_position',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Job Position'
            )->addColumn(
                'image_file',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Image File'
            )->addColumn(
                'short_bio',
                Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Short Bio'
            )->addColumn(
                'twitter_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Twitter ID'
            )->addColumn(
                'facebook_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Facebook ID'
            )->addColumn(
                'linkedin_id',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'LinkedIn ID'
            )->addIndex(
                $installer->getIdxName(ResourceAuthor::BLOG_AUTHOR_TABLE, ['twitter_id']),
                ['twitter_id']
            )->addIndex(
                $installer->getIdxName(ResourceAuthor::BLOG_AUTHOR_TABLE, ['facebook_id']),
                ['facebook_id']
            )->addIndex(
                $installer->getIdxName(ResourceAuthor::BLOG_AUTHOR_TABLE, ['linkedin_id']),
                ['linkedin_id']
            )->addIndex(
                $installer->getIdxName(ResourceAuthor::BLOG_AUTHOR_TABLE, ['url_key']),
                ['url_key']
            )->setComment('Blog Author Table');
        $installer->getConnection()->createTable($table);

        return $this;
    }

    /**
     * Add columns to post table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnToPostTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'author_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'after' => 'author_name',
                'comment' => 'Author ID'
            ]
        );
        $connection->addForeignKey(
            $connection->getForeignKeyName(
                ResourcePost::BLOG_POST_TABLE,
                'author_id',
                ResourceAuthor::BLOG_AUTHOR_TABLE,
                'id'
            ),
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'author_id',
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'id',
            Table::ACTION_SET_NULL
        );

        return $this;
    }

    /**
     * Add columns to category table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnToCategoryTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'parent_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'unsigned' => true,
                'default' => '0',
                'after' => 'meta_description',
                'comment' => 'Parent ID'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'path',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'parent_id',
                'comment' => 'Path'
            ]
        );
        $connection->addIndex(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            $installer->getIdxName(ResourceCategory::BLOG_CATEGORY_TABLE, ['path']),
            ['path']
        );

        return $this;
    }

    /**
     * Modify post author
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function modifyPostAuthor(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();
        $select = $connection->select()->from($installer->getTable(ResourcePost::BLOG_POST_TABLE));
        $posts = $connection->fetchAll($select);

        foreach ($posts as $post) {
            $authorId = $this->authorResolver->resolveId($post, 'author_name');
            $connection->update(
                $installer->getTable(ResourcePost::BLOG_POST_TABLE),
                [PostInterface::AUTHOR_ID => $authorId],
                PostInterface::ID . ' = ' . $post[PostInterface::ID]
            );
        }

        return $this;
    }

    /**
     * Drop columns from post table
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function dropColumnsFromPostTable(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->dropColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'author_name'
        );
        $connection->dropColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'meta_twitter_creator'
        );

        return $this;
    }

    /**
     * Reset category sort order
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function resetCategorySortOrder(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->update(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            [CategoryInterface::SORT_ORDER => 0]
        );

        return $this;
    }

    /**
     * Add columns to post table 2.7.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnsToPostTable270(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'meta_keywords',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_title',
                'comment' => 'Meta Keywords'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'meta_prefix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_description',
                'comment' => 'Meta Prefix'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourcePost::BLOG_POST_TABLE),
            'meta_suffix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_prefix',
                'comment' => 'Meta Suffix'
            ]
        );

        return $this;
    }

    /**
     * Add columns to category table 2.7.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnsToCategoryTable270(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'meta_keywords',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_title',
                'comment' => 'Meta Keywords'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'meta_prefix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_description',
                'comment' => 'Meta Prefix'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'meta_suffix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_prefix',
                'comment' => 'Meta Suffix'
            ]
        );

        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'image_file_name',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Image File Name'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'image_title',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Image Title'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'image_alt',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => 'Image Alt'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'is_description_enabled',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'unsigned' => true,
                'default' => '0',
                'comment' => 'Is Description Enabled'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceCategory::BLOG_CATEGORY_TABLE),
            'description',
            [
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Description'
            ]
        );

        return $this;
    }

    /**
     * Add columns to author table 2.7.0
     *
     * @param SchemaSetupInterface $installer
     * @return $this
     */
    private function addColumnsToAuthorTable270(SchemaSetupInterface $installer)
    {
        $connection = $installer->getConnection();

        $connection->addColumn(
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'meta_title',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'url_key',
                'comment' => 'Meta Title'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'meta_keywords',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_title',
                'comment' => 'Meta Keywords'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'meta_description',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_keywords',
                'comment' => 'Meta Description'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'meta_prefix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_description',
                'comment' => 'Meta Prefix'
            ]
        );
        $connection->addColumn(
            $installer->getTable(ResourceAuthor::BLOG_AUTHOR_TABLE),
            'meta_suffix',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'meta_prefix',
                'comment' => 'Meta Suffix'
            ]
        );

        return $this;
    }
}
