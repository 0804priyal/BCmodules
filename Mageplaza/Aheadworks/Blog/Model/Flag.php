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
namespace Aheadworks\Blog\Model;

/**
 * Class Flag
 *
 * @package Aheadworks\Blog\Model
 */
class Flag extends \Magento\Framework\Flag
{
    /**#@+
     * Constants for blog flags
     */
    const AW_BLOG_SCHEDULE_POST_LAST_EXEC_TIME = 'aw_blog_schedule_post_last_exec_time';
    /**#@-*/

    /**
     * Setter for flag code
     * @codeCoverageIgnore
     *
     * @param string $code
     * @return $this
     */
    public function setBlogFlagCode($code)
    {
        $this->_flagCode = $code;
        return $this;
    }
}
