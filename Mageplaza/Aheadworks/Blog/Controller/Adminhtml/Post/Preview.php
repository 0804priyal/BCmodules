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
use Aheadworks\Blog\Api\Data\PostInterfaceFactory;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Controller\Adminhtml\Post;
use Aheadworks\Blog\Model\Converter\Condition as ConditionConverter;
use Aheadworks\Blog\Model\Encryptor;
use Aheadworks\Blog\Model\PostFactory;
use Aheadworks\Blog\Model\Preview\UrlBuilder as PreviewUrlBuilder;
use Aheadworks\Blog\Model\Rule\ProductFactory;
use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Preview
 * @package Aheadworks\Blog\Controller\Adminhtml\Post
 */
class Preview extends Post
{
    /**
     * @var FormKeyValidator
     */
    private $formKeyValidator;

    /**
     * @var Encryptor
     */
    private $encryptor;

    /**
     * @var PreviewUrlBuilder
     */
    private $previewUrlBuilder;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param PostInterfaceFactory $postDataFactory
     * @param PostFactory $postFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param JsonFactory $resultJsonFactory
     * @param DateTime $dateTime
     * @param StoreManagerInterface $storeManager
     * @param DataPersistorInterface $dataPersistor
     * @param ConditionConverter $conditionConverter
     * @param ProductFactory $productRuleFactory
     * @param SerializeFactory $serializeFactory
     * @param FormKeyValidator $formKeyValidator
     * @param Encryptor $encryptor
     * @param PreviewUrlBuilder $previewUrlBuilder
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        PostInterfaceFactory $postDataFactory,
        PostFactory $postFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        JsonFactory $resultJsonFactory,
        DateTime $dateTime,
        StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        ConditionConverter $conditionConverter,
        ProductFactory $productRuleFactory,
        SerializeFactory $serializeFactory,
        FormKeyValidator $formKeyValidator,
        Encryptor $encryptor,
        PreviewUrlBuilder $previewUrlBuilder
    ) {
        parent::__construct(
            $context,
            $postRepository,
            $postDataFactory,
            $postFactory,
            $dataObjectHelper,
            $dataObjectProcessor,
            $resultPageFactory,
            $resultForwardFactory,
            $resultJsonFactory,
            $dateTime,
            $storeManager,
            $dataPersistor,
            $conditionConverter,
            $productRuleFactory,
            $serializeFactory
        );
        $this->formKeyValidator = $formKeyValidator;
        $this->previewUrlBuilder = $previewUrlBuilder;
        $this->encryptor = $encryptor;
    }

    /**
     * {@inheritDoc}
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $result = [
            'error'     => true,
            'message'   => __('Unknown error occured!')
        ];

        if ($postData = $this->getRequest()->getParam('post_data')) {
            if ($postData && $this->formKeyValidator->validate($this->getRequest())) {
                $postData = $this->preparePostData($postData);
                $storeIds = isset($postData[PostInterface::STORE_IDS])
                    ? $postData[PostInterface::STORE_IDS]
                    : [];
                $result = [
                    'error'     => false,
                    'message'   => __('Success.'),
                    'url'       => $this->previewUrlBuilder->getUrl(
                        'aw_blog/post/previewcontent',
                        is_array($storeIds) ? array_shift($storeIds) : $storeIds,
                        [
                            'data' => $this->encryptor->encrypt($postData),
                            '_scope_to_url' => true,
                        ],
                        'frontend'
                    )];
            } else {
                $this->_forward('noroute');
            }
        }

        return $resultJson->setData($result);
    }
}
