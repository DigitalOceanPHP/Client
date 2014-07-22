<?php
namespace DigitalOceanV2\Exception;

class ExceptionReader
{
    /**
     * @var string Error id
     */
    protected $id;

    /**
     * @var string Error message
     */
    protected $message;

    /**
     * Exception error code
     * @var int
     */
    protected $code;

    /**
     * Error message in DO format.
     *
     * @param string $content
     * @param int    $code
     */
    public function __construct($content, $code = 0)
    {
        $content = json_decode($content, true);
        $codeId  = empty($content['id']) ? null : $content['id'];
        $message = empty($content['message']) ? 'Request not processed.' : $content['message'];

        // just example to modify message
        $message = str_replace(
            array('Droplet', 'droplet'),
            array('Machine', 'machine'),
            $message
        );

        $this->id      = $codeId;
        $this->code    = $code;
        $this->message = $message;
    }

    /**
     * Message Id (DO error code)
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Error Message
     * @param  bool   $includeCodeId
     * @return string
     */
    public function getMessage($includeCodeId = true)
    {
        if ($includeCodeId) {
            $message = sprintf('%s (%s)', $this->message, $this->id);

            if ($this->code) {
                $message = sprintf('[%s] %s', $this->code, $message);
            }

            return $message;
        } else {
            return $this->message;
        }
    }
}
