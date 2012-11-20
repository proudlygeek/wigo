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

    /**
     * Checks if Redis is up using Telnet; the following scenarios can occur:
     *
     * 1. Redis is up and responds correcly echoes a message back => Ok;
     * 2. Redis is up and *does not* responds correctly to echo message => Warning;
     * 3. Redis is down => Critical.
     *
     * @return \Liip\Monitor\Result\CheckResult
     */
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

        fclose($c);

        return new CheckResult("Redis", "OK", CheckResult::OK);
    }

    public function getName()
    {
        return 'Redis Server check';
    }

}
