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
namespace Aheadworks\Blog\Controller\Adminhtml\Author;

use Aheadworks\Blog\Controller\Adminhtml\Upload;

/**
 * Class UploadImage
 * @package Aheadworks\Blog\Controller\Adminhtml\Author
 */
class UploadImage extends Upload
{
    /**
     * @var string
     */
    const FILE_ID = 'image_file';

    /**
     * {@inheritdoc}
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::authors';
}
