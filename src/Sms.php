<?php

namespace Ak\MrSenderRepeater;
<<<<<<< HEAD

use Ak\MrSenderRepeater\Helpers\CommonHelper;

class Sms
{
    private $_username = null;
    private $_password = null;
    public $server = "https://www.mr-sender.com/api/";
    public $commonHelper;
=======

class Sms
{
    private $userkey = null;
    private $password = null;
    private $currentOptions = [];
    public $server = "https://www.mr-sender.com/";
    private $sendStatus = null;
>>>>>>> 7c189f6042c19722f52a55277aad70e0e934dc2c

    /**
     * Sms constructor.
     * @param $_username
     * @param $_password
     */
<<<<<<< HEAD
    public function __construct($_username, $_password)
=======
    private $validOptions = [
        "Originator",
        "DeferredDeliveryTime",
        "FlashingSMS",
        "TimeZone",
        "URLBufferedMessageNotification",
        "URLDeliveryNotification",
        "URLNonDeliveryNotification",
        "AffiliateId",
        "MessageText",
        "Recipients",
        "TransactionReferenceNumbers",
    ];
    private $sendStatusCodes = [
        1 => "Ok",
        2 => "Connect failed.",
        3 => "Authorization failed (wrong userkey and/or password).",
        4 => "Binary file not found. Please check the location.",
        5 => "Not enough Credits available.",
        6 => "Time out error.",
        7 => "Transmission error. Please try it again.",
        8 => "Invalid UserKey. Please check the spelling of the UserKey.",
        9 => "Invalid Password.",
        10 => "Invalid originator. A maximum of 11 characters is allowed for alphanumeric originators.",
        11 => "Invalid message date. Please verify the data.",
        12 => "Invalid binary data. Please verify the data.",
        13 => "Invalid binary file. Please check the file type.",
        14 => "Invalid MCC. Please check the number.",
        15 => "Invalid MNC. Please check the number.",
        16 => "Invalid XSer.",
        17 => "Invalid URL buffered message notification string.",
        18 => "Invalid URL delivery notification string.",
        19 => "Invalid URL non delivery notification string.",
        20 => "Missing a recipient. Please specify at least one recipient.",
        21 => "Missing binary data. Please specify some data.",
        22 => "Invalid deferred delivery time. Please check the format.",
        23 => "Missing transaction reference number.",
        24 => "Service temporarily not available.",
        25 => "User access denied.",
    ];

    public function __construct($userkey, $password)
>>>>>>> 7c189f6042c19722f52a55277aad70e0e934dc2c
    {
        // save username
        $this->_username = $_username;
        // save password
        $this->_password = $_password;
        $this->commonHelper = new CommonHelper($this->_username, $this->_password, $this->server);
    }

<<<<<<< HEAD
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
=======
    public function sendTextSms($message, array $recipients, array $options = [])
    {
        // set message option
        $this->setOption("MessageText", $message);
        // set recipients option
        $recipientList = [];
        // collect all recipients with teir
        foreach ($recipients as $tracknr => $number) {
            // according to the docs multiple recipients must look like this: <NUMBER>:<TRACKNR>;<NUMBER>:<TRACKNR>
            $recipientList[] = "$number:$tracknr";
        }
>>>>>>> 7c189f6042c19722f52a55277aad70e0e934dc2c
        // se the recipients into the options list
        $this->commonHelper->setOption("to", $recipient);
        // start request width defined options for this action
<<<<<<< HEAD
        $response = $this->commonHelper->request("sms/send", $this->commonHelper->getOptions(array(
            "to",
            "content",
        )));
        return $response;
    }

=======
        $response = $this->request("SendTextSMS", $this->getOptions([
            "Recipients",
            "AffiliateId",
            "MessageText",
            "Originator",
            "DeferredDeliveryTime",
            "FlashingSMS",
            "TimeZone",
            "URLBufferedMessageNotification",
            "URLDeliveryNotification",
            "URLNonDeliveryNotification",
        ]));

        $result = $this->parseResponse($response);

        // verify if the status code exists in sendStatusCodes
        if (! array_key_exists($result[1], $this->sendStatusCodes)) {
            throw new Exception("Error while printing the response code into sendStatus. ResponseCode seems not valid. Response: \"{$response}\"");
        }
        // send the status as text value into $sendStatus
        $this->sendStatus = $this->sendStatusCodes[$result[1]];
        // if the result is not equal 1 something is wrong
        if ($result[1] !== "1") {
            return false;
        }

        return true;
    }

    private function setOption($key, $value)
    {
        // see if key is in the validOptions list.
        if (! in_array($key, $this->validOptions)) {
            throw new Exception("setOption: Could not find the option \"$key\" in the validOptions list!");
        }
        // set the options into the currentOptions list
        $this->currentOptions[$key] = $value;

        return true;
    }

    public function parseResponse($response)
    {
        if (empty($response)) {
            throw new Exception("Unable to parse an empty response string.");
        }

        return explode(":", $response);
    }

    private function request($action, array $values = [])
    {
        // build new AspsmsRequest-Object
        $request = new Request($this->server.$action, $this->prepareValues($values));
        // transfer the request
        $response = $request->transfer();
        // flush request class
        $request->flush();
        // flush local settings
        $this->flush();
        // return request response to its executed method
        return $response;
    }

    private function prepareValues($values)
    {
        // set default transfer values
        $transferValues = [
            'UserKey' => $this->userkey,
            'Password' => $this->password,
        ];
        /// get the request values urlencode und utf8encode first.
        foreach ($values as $key => $value) {
            $transferValues[$key] = $value;
        }
        // return changed transfer values
        return $transferValues;
    }

    private function flush()
    {
        // set back the default empty array
        $this->currentOptions = [];
        // set back send status buffer string
        $this->sendStatus = null;
    }

    public function setOptions(array $options)
    {
        // loop the $options items
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }

        return true;
    }

    /**
     * Gets all options/values. If there is no value set for the needed $optionKeys the function sets
     * an empty default string '' and returns all requests $optionKeys
     *
     * @param array $optionKeys All options which are requested
     * @return array
     */
    private function getOptions(array $optionKeys)
    {
        // return options array
        $options = [];
        // foreach all option keys and see if some values are set in the currentOptions array
        foreach ($optionKeys as $key) {
            // see if this option is set in options list
            if (array_key_exists($key, $this->currentOptions)) {
                $options[$key] = $this->currentOptions[$key];
            } else {
                $options[$key] = '';
            }
        }

        return $options;
    }
>>>>>>> 7c189f6042c19722f52a55277aad70e0e934dc2c
}
