<?php

namespace Ak\MrSenderRepeater;

use Ak\MrSenderRepeater\Helpers\CommonHelper;

class Sms
{
    private $_username = null;
    private $_password = null;
    public $server = "https://www.mr-sender.com/api/";
    public $commonHelper;

    /**
     * Sms constructor.
     * @param $_username
     * @param $_password
     */
    public function __construct($_username, $_password)
    {
        // save username
        $this->_username = $_username;
        // save password
        $this->_password = $_password;
        $this->commonHelper = new CommonHelper($this->_username, $this->_password, $this->server);
    }

    /**
     * @param $message
     * @param string $recipient
     * @return string
     * @throws Exception
     */

    public function sendTextSms($message, $recipient)
    {
        // set message option
        $this->commonHelper->setOption("content", $message);
        // se the recipients into the options list
        $this->commonHelper->setOption("to", $recipient);
        // start request width defined options for this action
        $response = $this->commonHelper->request("sms/send", $this->commonHelper->getOptions(array(
            "to",
            "content",
        )));
        return $response;
    }

}
