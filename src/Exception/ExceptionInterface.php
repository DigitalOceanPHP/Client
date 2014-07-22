<?php
namespace DigitalOceanV2\Exception;

interface ExceptionInterface
{
    /**
     * Create an exception
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     *
     * @return ExceptionInterface
     */
    public static function create($message, $code = 0, \Exception $previous = null);
}
