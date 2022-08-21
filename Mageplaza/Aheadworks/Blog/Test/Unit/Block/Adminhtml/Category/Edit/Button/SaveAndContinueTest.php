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
namespace Aheadworks\Blog\Test\Unit\Block\Adminhtml\Category\Edit\Button;

use Aheadworks\Blog\Block\Adminhtml\Category\Edit\Button\SaveAndContinue as SaveAndContinueButton;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Blog\Block\Adminhtml\Category\Edit\Button\SaveAndContinue
 */
class SaveAndContinueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var SaveAndContinueButton
     */
    private $button;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->button = $objectManager->getObject(SaveAndContinueButton::class);
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $this->assertTrue(is_array($this->button->getButtonData()));
    }
}
