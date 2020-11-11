<?php

namespace Ak\MrSenderRepeater;
use Ak\MrSenderRepeater\Exception;
use Ak\MrSenderRepeater\Request;

class Sms
{
    private $userkey=null;
    private $password=null;
    private $currentOptions = array();
    public $server = "https://www.mr-sender.com/";
    private $sendStatus = null;

    /**
     * @var array Contains all valid option parameters which can be delivered trough option
     * arguments in functions.
     */
    private $validOptions = array(
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
    );
    private $sendStatusCodes = array(
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
    );

    public function __construct($userkey, $password)
    {
        // save userkey
        $this->userkey = $userkey;
        // save password
        $this->password = $password;
        // set optional options if any provided
    }

    public function sendTextSms($message, array $recipients, array $options = array())
    {
        // set message option
        $this->setOption("MessageText", $message);
        // set recipients option
        $recipientList = array();
        // collect all recipients with teir
        foreach ($recipients as $tracknr => $number) {
            // according to the docs multiple recipients must look like this: <NUMBER>:<TRACKNR>;<NUMBER>:<TRACKNR>
            $recipientList[] = "$number:$tracknr";
        }
        // se the recipients into the options list
        $this->setOption("Recipients", implode(";", $recipientList));
        // optional options parameter to set values into currentOptions
        $this->setOptions($options);
        // start request width defined options for this action
        $response = $this->request("SendTextSMS", $this->getOptions(array(
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
        )));

        $result = $this->parseResponse($response);

        // verify if the status code exists in sendStatusCodes
        if (!array_key_exists($result[1], $this->sendStatusCodes)) {
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
        if (!in_array($key, $this->validOptions)) {
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



    private function request($action, array $values = array())
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
        $transferValues = array(
            'UserKey' => $this->userkey,
            'Password' => $this->password,
        );
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
        $this->currentOptions = array();
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
        $options = array();
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

}
