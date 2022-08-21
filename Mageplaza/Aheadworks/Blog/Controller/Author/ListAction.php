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
namespace Aheadworks\Blog\Controller\Author;

use Aheadworks\Blog\Controller\Action;

/**
 * Class ListAction
 * @package Aheadworks\Blog\Controller\Author
 */
class ListAction extends Action
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $pageConfig = $resultPage->getConfig();

        $pageConfig->getTitle()->set(__('Authors'));
        if ($this->areMetaTagsEnabled()) {
            $pageConfig->setMetadata('description', $this->getBlogMetaDescription());
        }

        return $resultPage;
    }
}
