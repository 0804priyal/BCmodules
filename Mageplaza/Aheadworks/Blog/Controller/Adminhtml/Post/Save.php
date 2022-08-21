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

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\Message\Error;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Controller\Adminhtml\Post;

/**
 * Class Save
 * @package Aheadworks\Blog\Controller\Adminhtml\Post
 */
class Save extends Post
{
    /**
     * Save post action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($postData = $this->getRequest()->getPostValue()) {
            $postData = $this->preparePostData($postData);
            $postId = isset($postData['id']) ? $postData['id'] : false;
            try {
                $postDataObject = $postId
                    ? $this->postRepository->get($postId)
                    : $this->postDataFactory->create();
                $this->dataObjectHelper->populateWithArray(
                    $postDataObject,
                    $postData,
                    PostInterface::class
                );
                $post = $this->postRepository->save($postDataObject);
                $this->dataPersistor->clear('aw_blog_post');
                $this->messageManager->addSuccessMessage(__('The post was successfully saved.'));
                $back = $this->getRequest()->getParam('back');
                if ($back == 'edit') {
                    return $resultRedirect->setPath(
                        '*/*/' . $back,
                        [
                            'id' => $post->getId(),
                            '_current' => true
                        ]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Validator\Exception $exception) {
                $messages = $exception->getMessages();
                if (empty($messages)) {
                    $messages = [$exception->getMessage()];
                }
                foreach ($messages as $message) {
                    if (!$message instanceof Error) {
                        $message = new Error($message);
                    }
                    $this->messageManager->addMessage($message);
                }
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving the post.')
                );
            }
            unset($postData[PostInterface::PRODUCT_CONDITION]);
            $this->dataPersistor->set('aw_blog_post', $postData);
            if ($postId) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $postId, '_current' => true]);
            }
            return $resultRedirect->setPath('*/*/new', ['_current' => true]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
