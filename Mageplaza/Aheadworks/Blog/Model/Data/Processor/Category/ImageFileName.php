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
namespace Aheadworks\Blog\Model\Data\Processor\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;

/**
 * Class ImageFileName
 *
 * @package Aheadworks\Blog\Model\Data\Processor\Category
 */
class ImageFileName implements ProcessorInterface
{
    /**
     * {@inheritdoc}
     */
    public function process($data)
    {
        $imageFileName = isset($data[CategoryInterface::IMAGE_FILE_NAME][0]['file'])
            ? $data[CategoryInterface::IMAGE_FILE_NAME][0]['file']
            : '';
        $data[CategoryInterface::IMAGE_FILE_NAME] = $imageFileName;
        return $data;
    }
}
