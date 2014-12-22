<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Adapter;

use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 */
class BuzzOAuthListener implements \Buzz\Listener\ListenerInterface
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * {@inheritdoc}
     */
    public function preSend(RequestInterface $request)
    {
        $request->addHeader(sprintf('Authorization: Bearer %s', $this->accessToken));
    }

    /**
     * {@inheritdoc}
     */
    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
