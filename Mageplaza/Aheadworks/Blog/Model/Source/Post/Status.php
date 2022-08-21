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
namespace Aheadworks\Blog\Model\Source\Post;

/**
 * Post Status source model
 * @package Aheadworks\Blog\Model\Source\Post
 */
class Status implements \Magento\Framework\Option\ArrayInterface
{
    // Statuses to store in DB
    const DRAFT = 'draft';
    const PUBLICATION = 'publication';
    const SCHEDULED = 'scheduled';

    /**
     * @var array
     */
    private $options;

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            self::DRAFT => __('Draft'),
            self::SCHEDULED => __('Scheduled'),
            self::PUBLICATION => __('Published')
        ];
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->options = [];
            foreach ($this->getOptions() as $value => $label) {
                $this->options[] = ['value' => $value, 'label' => $label];
            }
        }
        return $this->options;
    }
}
