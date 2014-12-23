<?php

/*
 * This file is part of the DigitalOceanV2 library.
 *
 * (c) Antoine Corcy <contact@sbin.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DigitalOceanV2\Exception;

/**
 * @author liverbool <nukboon@gmail.com>
 */
class ResponseException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var ExceptionReader
     */
    protected $exception;

    /**
     * {@inheritdoc}
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        $this->exception = new ExceptionReader($message, $code);

        parent::__construct($this->exception->getMessage(), $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public static function create($message, $code = 0, \Exception $previous = null)
    {
        return new self($message, $code, $previous);
    }

    /**
     * @param bool $includeCodeId (optional)
     *
     * @return string
     */
    public function getErrorMessage($includeCodeId = false)
    {
        return $this->exception->getMessage($includeCodeId);
    }

    /**
     * @return string
     */
    public function getErrorId()
    {
        return strtoupper($this->exception->getId());
    }
}
