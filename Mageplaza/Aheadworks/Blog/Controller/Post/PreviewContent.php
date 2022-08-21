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
namespace Aheadworks\Blog\Controller\Post;

use Aheadworks\Blog\Controller\Action;

/**
 * Class PreviewContent
 * @package Aheadworks\Blog\Controller\Post
 */
class PreviewContent extends Action
{
    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
