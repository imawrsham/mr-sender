<?php

namespace Ak\MrSenderRepeater;

class Request
{
    /**
     * @param array Default options for the curl request
     */
    private $options = [
        CURLOPT_TIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_POST => true,
    ];

    /**
     * @param array All values which are provided through value() or __construct()
     */
    private $values = [];

    /**
     * AspsmsRequest constructor requires call service url.
     *
     * @param string $url The called webservice url
     * @param array $values Values can be set direct in the class construct or via the value() method.
     */
    public function __construct($url, array $values = [])
    {
        // assign CURLOPT_URL into options array
        $this->options[CURLOPT_URL] = $url;
        // set basic value keys into values array
        $this->values = $values;
    }

    /**
     * Optional method to set values.
     *
     * @param string $key The POST-FIELD-KEY
     * @param string $value The value of the postfield
     * @return bool
     */
    public function value($key, $value)
    {
        // save values into values array (great comment)
        $this->values[$key] = $value;

        return true;
    }

<<<<<<< HEAD
=======
    /**
     * Unset all values from the values array to make new requests.
     *
     * @return bool
     */
    public function flush()
    {
        // overwrite $values with empty array()
        $this->values = [];

        return true;
    }

    /**
     * Could not use http_build_query() because of &, ; & : signs changing, need to build a
     * simple function to build the strings.
     *
     * @param array $values Key value pared parameter values
     * @return string
     */
    private function buildPostfields($values)
    {
        $params = [];
        foreach ($values as $k => $v) {
            $params[] = $k.'='.$v;
        }

        return implode("&", $params);
    }
>>>>>>> 7c189f6042c19722f52a55277aad70e0e934dc2c

    /**
     * Initiates the main curl execution.
     *
     * @return string/mixed
     * @throws \Exception
     */
    public function transfer()
    {
        $this->options[CURLOPT_POSTFIELDS] = $this->values;
        // init curl
        $curl = curl_init();
        // set all options into curl object from $options
        curl_setopt_array($curl, $this->options);
        $response = curl_exec($curl);
        // close the curl connection
        curl_close($curl);
        // execute the curl and write response into $response
        if ($response != strip_tags($response)) {
            return '{"result":"failed","message":"Invalid username. Please check the spelling of the UserKey."}';
        }
        if ($response === false) {
            $response = curl_error($curl);
        }
        return $response;
    }
}
