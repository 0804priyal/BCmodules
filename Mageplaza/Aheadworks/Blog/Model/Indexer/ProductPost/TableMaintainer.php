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
namespace Aheadworks\Blog\Model\Indexer\ProductPost;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost;
use Magento\Framework\Indexer\ScopeResolver\IndexScopeResolver as TableResolver;
use Aheadworks\Blog\Model\Indexer\MultiThread\PostDimension;

/**
 * Class TableMaintainer
 *
 * @package Aheadworks\Blog\Model\Indexer\ProductPost
 */
class TableMaintainer
{
    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var TableResolver
     */
    private $tableResolver;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @param ResourceConnection $resource
     * @param TableResolver $tableResolver
     */
    public function __construct(
        ResourceConnection $resource,
        TableResolver $tableResolver
    ) {
        $this->resource = $resource;
        $this->tableResolver = $tableResolver;
    }

    /**
     * Return main index table name
     *
     * @param string $name
     * @return string
     */
    public function getMainTable($name)
    {
        return $this->resource->getTableName(ProductPost::BLOG_PRODUCT_POST_TABLE . $name);
    }

    /**
     * Create main index table for dimension
     *
     * @param PostDimension $dimension
     * @return string
     * @throws \Zend_Db_Exception
     */
    public function createTableForDimension($dimension)
    {
        $newTableName = $this->getMainTable($dimension->getName());
        $originalTable = $this->getTable(ProductPost::BLOG_PRODUCT_POST_TMP_TABLE);
        if (!$this->getConnection()->isTableExists($newTableName)) {
            $this->getConnection()->createTable(
                $this->getConnection()->createTableByDdl($originalTable, $newTableName)
            );
        }

        return $newTableName;
    }

    /**
     * Clear temporary index table
     */
    public function clearTemporaryIndexTable()
    {
        $this->getConnection()->truncateTable($this->getTable(ProductPost::BLOG_PRODUCT_POST_TMP_TABLE));
    }

    /**
     * Drop main index table for dimension
     *
     * @param PostDimension $dimension
     * @return void
     */
    public function dropTableForDimension($dimension)
    {
        $mainTableName = $this->getMainTable($dimension->getName());
        $this->dropTable($mainTableName);
    }

    /**
     * Get connection
     *
     * @return AdapterInterface
     */
    private function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = $this->resource->getConnection();
        }
        return $this->connection;
    }

    /**
     * Return validated table name
     *
     * @param string|string[] $table
     * @return string
     */
    private function getTable($table)
    {
        return $this->resource->getTableName($table);
    }

    /**
     * Create temporary table based on main table
     *
     * @param string $mainTableName
     * @param string $newTableName
     * @return void
     */
    private function createTemporaryTable($mainTableName, $newTableName)
    {
        if (!$this->getConnection()->isTableExists($newTableName)) {
            $this->getConnection()->createTemporaryTableLike($newTableName, $mainTableName, true);
        }
    }

    /**
     * Drop table
     *
     * @param string $tableName
     * @return void
     */
    private function dropTable($tableName)
    {
        if ($this->getConnection()->isTableExists($tableName)) {
            $this->getConnection()->dropTable($tableName);
        }
    }
}
