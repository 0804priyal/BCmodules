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
namespace Aheadworks\Blog\Model\Post\Author;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Resolver
 * @package Aheadworks\Blog\Model\Post\Author
 */
class Resolver
{
    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var Creator
     */
    private $creator;

    /**
     * @var array
     * $authorName => $authorId pairs
     */
    private $authorIds = [];

    /**
     * @param AuthorRepositoryInterface $authorRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Creator $creator
     */
    public function __construct(
        AuthorRepositoryInterface $authorRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Creator $creator
    ) {
        $this->authorRepository = $authorRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->creator = $creator;
    }

    /**
     * Retrieve post author full name
     *
     * @param PostInterface $post
     * @return string
     */
    public function getFullName($post)
    {
        $fullName = '';
        if ($author = $post->getAuthor()) {
            $fullName = $author->getFirstname() . ' ' . $author->getLastname();
        }
        return $fullName;
    }

    /**
     * Resolve author id
     *
     * @param array $postData
     * @param string $authorNameKey
     * @return int|null
     */
    public function resolveId($postData, $authorNameKey)
    {
        $this->initAuthorIds();
        $postAuthorName = isset($postData[$authorNameKey]) ? $postData[$authorNameKey] : '';

        return $this->retrieveId($postAuthorName);
    }

    /**
     * Resolve author id for Wordpress
     *
     * @param string $postAuthorName
     * @return int|null
     */
    public function resolveIdForWp($postAuthorName)
    {
        $this->initAuthorIds();

        return $this->retrieveId($postAuthorName);
    }

    /**
     * Retrieve ID
     *
     * @param string $postAuthorName
     * @return int|null
     */
    private function retrieveId($postAuthorName)
    {
        $authorId = null;
        try {
            $key = str_replace(' ', '_', $postAuthorName);
            if (array_key_exists($key, $this->authorIds)) {
                $authorId = $this->authorIds[$key];
            } else {
                $authorId = $this->creator->createByName($postAuthorName)->getId();
                $this->authorIds[$key] = $authorId;
            }
        } catch (\Exception $e) {
        }

        return $authorId;
    }

    /**
     * Init author ids
     */
    private function initAuthorIds()
    {
        if (empty($this->authorIds)) {
            $authors = $this->authorRepository->getList($this->searchCriteriaBuilder->create())->getItems();

            /** @var AuthorInterface $author */
            foreach ($authors as $author) {
                $authorName = $author->getFirstname() . '_' . $author->getLastname();
                $this->authorIds[$authorName] = $author->getId();
            }
        }
    }
}
