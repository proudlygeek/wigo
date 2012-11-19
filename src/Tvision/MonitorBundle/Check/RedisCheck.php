<?php

namespace Tvision\MonitorBundle\Check;

use Liip\Monitor\Check\Check;
use Liip\Monitor\Exception\CheckFailedException;
use Liip\Monitor\Result\CheckResult;

/**
 * Created by JetBrains PhpStorm.
 * User: proudlygeek
 * Date: 11/19/12
 * Time: 2:25 PM
 * To change this template use File | Settings | File Templates.
 */
class RedisCheck extends Check
{
    protected $host;
    protected $port;

    public function __construct($host, $port) {
        $this->host = $host;
        $this->port = $port;
    }

    public function check() {

        $errCode = null;
        $errString = null;


        $c = fsockopen($this->host, $this->port, $errCode);

        if ($errCode != 0) {
            return new CheckResult("Redis", "Connection was not successful.", CheckResult::CRITICAL);
        }

        $rawCommand = "*2\r\n\$4\r\nEcho\r\n\$12\r\nhello world!\r\n";

        fwrite($c, $rawCommand);

        $rawResponse = fgets($c);
        $rawResponse = fgets($c);

        if ($rawResponse !== "hello world!\r\n") {
            return new CheckResult("Redis", "Connection was successful but message reply is wrong (" . $rawResponse .")", CheckResult::WARNING);
        }

        return new CheckResult("Redis", "OK", CheckResult::OK);
    }

    public function getName()
    {
        return 'Redis Server check';
    }

}
