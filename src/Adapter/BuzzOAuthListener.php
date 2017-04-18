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

use Buzz\Listener\ListenerInterface;
use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;

/**
 * @author Antoine Corcy <contact@sbin.dk>
 * @author Graham Campbell <graham@alt-three.com>
 */
class BuzzOAuthListener implements ListenerInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function preSend(RequestInterface $request)
    {
        $request->addHeader(sprintf('Authorization: Bearer %s', $this->token));
    }

    /**
     * {@inheritdoc}
     */
    public function postSend(RequestInterface $request, MessageInterface $response)
    {
    }
}
