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
namespace Aheadworks\Blog\Controller\Adminhtml\Comment;

use Aheadworks\Blog\Model\DisqusCommentsService;
use Magento\Backend\App\Action\Context;

/**
 * Class Index
 * @package Aheadworks\Blog\Controller\Adminhtml\Comment
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::comments';

    /**
     * @var DisqusCommentsService
     */
    private $disqusCommentsService;

    /**
     * @param Context $context
     * @param DisqusCommentsService $disqusCommentsService
     */
    public function __construct(
        Context $context,
        DisqusCommentsService $disqusCommentsService
    ) {
        parent::__construct($context);
        $this->disqusCommentsService = $disqusCommentsService;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->disqusCommentsService->getModerateUrl());
        return $resultRedirect;
    }
}
