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
namespace Aheadworks\Blog\Model\DateTime;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Stdlib\DateTime as StdlibDateTime;

/**
 * Class Formatter
 *
 * @package Aheadworks\Blog\Model\DateTime
 */
class Formatter
{
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * @param TimezoneInterface $localeDate
     */
    public function __construct(
        TimezoneInterface $localeDate
    ) {
        $this->localeDate = $localeDate;
    }

    /**
     * Retrieve formatted date and time, localized according to the specific store
     *
     * @param string|null $date
     * @param int|null $storeId
     * @param string $format
     * @return string
     */
    public function getLocalizedDateTime($date = null, $storeId = null, $format = StdlibDateTime::DATETIME_PHP_FORMAT)
    {
        $scopeDate = $this->localeDate->scopeDate($storeId, $date, true);
        return $scopeDate->format($format);
    }
}
