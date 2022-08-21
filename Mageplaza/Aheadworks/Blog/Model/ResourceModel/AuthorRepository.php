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

use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Api\Data\AuthorInterfaceFactory;
use Aheadworks\Blog\Api\Data\AuthorSearchResultsInterfaceFactory;
use Aheadworks\Blog\Model\AuthorFactory;
use Aheadworks\Blog\Model\AuthorRegistry;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\EntityManager\EntityManager;
use Aheadworks\Blog\Model\UrlRewrite\Manager\Entity as UrlRewriteEntityManager;
use Aheadworks\Blog\Model\UrlRewrite\Metadata\CreatorInterface as UrlRewriteMetadataCreatorInterface;

/**
 * Class AuthorRepository
 * @package Aheadworks\Blog\Model\ResourceModel
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AuthorRepository implements AuthorRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var AuthorFactory
     */
    private $authorFactory;

    /**
     * @var AuthorInterfaceFactory
     */
    private $authorDataFactory;

    /**
     * @var AuthorRegistry
     */
    private $authorRegistry;

    /**
     * @var AuthorSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var UrlRewriteEntityManager
     */
    private $urlRewriteEntityManager;

    /**
     * @var UrlRewriteMetadataCreatorInterface
     */
    private $metadataCreator;

    /**
     * @param EntityManager $entityManager
     * @param AuthorFactory $authorFactory
     * @param AuthorInterfaceFactory $authorDataFactory
     * @param AuthorRegistry $authorRegistry
     * @param AuthorSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param UrlRewriteEntityManager $urlRewriteEntityManager
     * @param UrlRewriteMetadataCreatorInterface $metadataCreator
     */
    public function __construct(
        EntityManager $entityManager,
        AuthorFactory $authorFactory,
        AuthorInterfaceFactory $authorDataFactory,
        AuthorRegistry $authorRegistry,
        AuthorSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        UrlRewriteEntityManager $urlRewriteEntityManager,
        UrlRewriteMetadataCreatorInterface $metadataCreator
    ) {
        $this->entityManager = $entityManager;
        $this->authorFactory = $authorFactory;
        $this->authorDataFactory = $authorDataFactory;
        $this->authorRegistry = $authorRegistry;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->urlRewriteEntityManager = $urlRewriteEntityManager;
        $this->metadataCreator = $metadataCreator;
    }

    /**
     * {@inheritdoc}
     */
    public function save(AuthorInterface $author)
    {
        /** @var \Aheadworks\Blog\Model\Author $authorModel */
        $authorModel = $this->authorFactory->create();
        if ($authorId = $author->getId()) {
            $this->entityManager->load($authorModel, $authorId);
        }
        $originalUrlKey = $authorModel->getUrlKey();
        $this->dataObjectHelper->populateWithArray(
            $authorModel,
            $this->dataObjectProcessor->buildOutputDataArray($author, AuthorInterface::class),
            AuthorInterface::class
        );
        $this->entityManager->save($authorModel);
        $author = $this->getAuthorDataObject($authorModel);
        $this->urlRewriteEntityManager->update(
            $this->metadataCreator->create($author, $originalUrlKey)
        );
        $this->authorRegistry->push($author);
        return $author;
    }

    /**
     * {@inheritdoc}
     */
    public function get($authorId)
    {
        return $this->authorRegistry->retrieve($authorId);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\Blog\Api\Data\AuthorSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create()
            ->setSearchCriteria($searchCriteria);
        /** @var \Aheadworks\Blog\Model\ResourceModel\Author\Collection $collection */
        $collection = $this->authorFactory->create()->getCollection();
        $this->extensionAttributesJoinProcessor->process($collection, AuthorInterface::class);
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        if ($sortOrders = $searchCriteria->getSortOrders()) {
            /** @var \Magento\Framework\Api\SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder($sortOrder->getField(), $sortOrder->getDirection());
            }
        }
        $collection
            ->setCurPage($searchCriteria->getCurrentPage())
            ->setPageSize($searchCriteria->getPageSize());

        $authors = [];
        /** @var \Aheadworks\Blog\Model\Author $authorModel */
        foreach ($collection as $authorModel) {
            $authors[] = $this->getAuthorDataObject($authorModel);
        }
        $searchResults->setItems($authors);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(\Aheadworks\Blog\Api\Data\AuthorInterface $author)
    {
        return $this->deleteById($author->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($authorId)
    {
        $author = $this->authorRegistry->retrieve($authorId);
        $this->entityManager->delete($author);
        $this->authorRegistry->remove($authorId);
        return true;
    }

    /**
     * Retrieves author data object using Author Model
     *
     * @param \Aheadworks\Blog\Model\Author $author
     * @return AuthorInterface
     */
    private function getAuthorDataObject(\Aheadworks\Blog\Model\Author $author)
    {
        /** @var AuthorInterface $authorDataObject */
        $authorDataObject = $this->authorDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $authorDataObject,
            $author->getData(),
            AuthorInterface::class
        );
        return $authorDataObject;
    }
}
