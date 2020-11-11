<?php


namespace Ak\MrSenderRepeater\Helpers;

use Ak\MrSenderRepeater\Exception;
use Ak\MrSenderRepeater\Request;

class CommonHelper
{
    private $_username = null;
    private $_password = null;
    public $server = null;
    /**
     * @var array Contains all valid option parameters which can be delivered trough option
     * arguments in functions.
     */
    private $validOptions = [
        "content",
        "to",
    ];
    private $currentOptions = [];

    /**
     * CommonHelper constructor.
     * @param $_username
     * @param $_password
     * @param $server
     */
    public function __construct($_username, $_password, $server)
    {
        // save username
        $this->_username = $_username;
        // save password
        $this->_password = $_password;
        $this->server = $server;
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     * @throws Exception
     */
    public function setOption($key, $value)
    {
        // see if key is in the validOptions list.
        if (! in_array($key, $this->validOptions)) {
            throw new Exception("setOption: Could not find the option \"$key\" in the validOptions list!");
        }
        // set the options into the currentOptions list
        $this->currentOptions[$key] = $value;

        return true;
    }

    public function request($action, array $values = [])
    {
        // build new AspsmsRequest-Object
        $request = new Request($this->server.$action, $this->prepareValues($values));

        // transfer the request
        $response = $request->transfer();

        // flush local settings
        $this->flush();
        // return request response to its executed method
        return $response;
    }

    private function prepareValues($values)
    {
        // set default transfer values
        $transferValues = [
            '_username' => $this->_username,
            '_password' => $this->_password,
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

    /**
     * @param array $options
     * @return bool
     * @throws Exception
     */
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
    public function getOptions(array $optionKeys)
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
}
