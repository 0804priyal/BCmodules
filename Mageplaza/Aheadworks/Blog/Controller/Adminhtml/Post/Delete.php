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
namespace Aheadworks\Blog\Controller\Adminhtml\Post;

/**
 * Class Delete
 * @package Aheadworks\Blog\Controller\Adminhtml\Post
 */
class Delete extends \Aheadworks\Blog\Controller\Adminhtml\Post
{
    /**
     * Delete post action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $postId = (int)$this->getRequest()->getParam('id');
        if ($postId) {
            try {
                $this->postRepository->deleteById($postId);
                $this->messageManager->addSuccessMessage(__('Post was successfully deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Post could not be deleted.'));
        return $resultRedirect->setPath('*/*/');
    }
}
