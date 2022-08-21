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
namespace Aheadworks\Blog\App\Router;

use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;

/**
 * Interface MatcherInterface
 * @package Aheadworks\Blog\App\Router
 */
class MatcherComposite implements MatcherInterface
{
    /**
     * @var array
     */
    private $matchers = [];

    /**
     * @param array $matchers
     */
    public function __construct(array $matchers = [])
    {
        $this->matchers = $matchers;
    }

    /**
     * {@inheritdoc}
     * @param RequestInterface|Http $request
     */
    public function match(RequestInterface $request)
    {
        /** @var MatcherInterface $matcher */
        foreach ($this->matchers as $matcher) {
            if ($matcher instanceof MatcherInterface && $matcher->match($request)) {
                return true;
            }
        }

        return false;
    }
}
