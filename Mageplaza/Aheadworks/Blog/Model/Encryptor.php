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

use Magento\Framework\Serialize\Serializer\Base64Json as Base64JsonSerializer;

/**
 * Class Encryptor
 * @package Aheadworks\Blog\Model
 */
class Encryptor
{
    /**
     * @var Base64JsonSerializer
     */
    private $base64JsonSerializer;

    /**
     * @param Base64JsonSerializer $base64JsonSerializer
     */
    public function __construct(
        Base64JsonSerializer $base64JsonSerializer
    ) {
        $this->base64JsonSerializer = $base64JsonSerializer;
    }

    /**
     * Encrypt data array to base64 url-compatible string
     *
     * @param array $data
     * @return string
     */
    public function encrypt(array $data)
    {
        $dataToEncrypt = is_array($data) ? $data : [$data];
        $base64String = $this->base64JsonSerializer->serialize($dataToEncrypt);
        return $this->getUrlCompatibleBase64String($base64String);
    }

    /**
     * Decrypt base64 url-compatible string to the data array
     *
     * @param string $urlCompatibleBase64String
     * @return array
     */
    public function decrypt($urlCompatibleBase64String)
    {
        $base64String = $this->getBase64String($urlCompatibleBase64String);
        $result = $this->base64JsonSerializer->unserialize($base64String);
        $decryptedData = is_array($result) ? $result : [$result];
        return $decryptedData;
    }

    /**
     * Convert base64 string to the format, hat can be used in urls
     *
     * @param string $base64String
     * @return string
     */
    private function getUrlCompatibleBase64String($base64String)
    {
        return rtrim(strtr($base64String, '+/', '-_'), '=');
    }

    /**
     * Convert url-compatible string to the pure base64 format
     *
     * @param string $urlCompatibleBase64String
     * @return string
     */
    private function getBase64String($urlCompatibleBase64String)
    {
        return strtr($urlCompatibleBase64String, '-_', '+/');
    }
}
