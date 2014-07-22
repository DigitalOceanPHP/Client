<?php
namespace DigitalOceanV2\Exception;

class ResponseException extends \RuntimeException implements ExceptionInterface
{
    protected $content;

    /**
     * Constructor
     *
     * @param string     $message
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        $this->content = new ExceptionReader($message, $code);

        parent::__construct($this->content->getMessage(), $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public static function create($message, $code = 0, \Exception $previous = null)
    {
        return new self($message, $code, $previous);
    }

    /**
     * @param bool    $includeCodeId
     * @return string
     */
    public function getErrorMessage($includeCodeId = false)
    {
        return $this->content->getMessage($includeCodeId);
    }

    /**
     * @return string
     */
    public function getErrorId()
    {
        return strtoupper($this->content->getId());
    }
}
