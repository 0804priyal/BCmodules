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
namespace Aheadworks\Blog\Model\Validator;

/**
 * Validate Url-keys
 */
class UrlKey extends \Zend_Validate_Abstract
{
    const IS_EMPTY = 'isEmpty';
    const IS_NUMBER = 'isNumber';
    const CONTAINS_DISALLOWED_SYMBOLS = 'containsDisallowedSymbols';
    const INVALID  = 'urlKeyInvalid';

    /**
     * @var array
     */
    protected $_messageTemplates = [
        self::IS_EMPTY => 'Value is required and can\'t be empty',
        self::IS_NUMBER  => 'Value consists of numbers',
        self::CONTAINS_DISALLOWED_SYMBOLS  => 'Value contains disallowed symbols',
        self::INVALID  => 'Invalid type given. String expected',
    ];

    /**
     * {@inheritdoc}
     */
    public function isValid($value)
    {
        if (!is_string($value)) {
            $this->_error(self::INVALID);
            return false;
        }
        if ($value == '') {
            $this->_error(self::IS_EMPTY);
            return false;
        }
        if (preg_match('/^[0-9]+$/', $value)) {
            $this->_error(self::IS_NUMBER);
            return false;
        }
        if (!preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $value)) {
            $this->_error(self::CONTAINS_DISALLOWED_SYMBOLS);
            return false;
        }
        return true;
    }
}
