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
class HostCheck extends Check
{
    protected $host;

    public function __construct($host) {
        $this->host = $host;
    }

    public function check() {
        $fp = fsockopen($this->host, 80, $errCode, $errStr, 30);

        if (!$fp || $errCode != 0) {
            return new CheckResult("[HTTP CHECK for " . $this->host . "]", "Host is unreachable,", CheckResult::CRITICAL);
        }

        $out = "GET / HTTP/1.1\r\n";
        $out .= "Host: " . $this->host ."\r\n";
        $out .= "Connection: Close\r\n\r\n";

        fwrite($fp, $out);

        $result =  fgets($fp, 20);

        fclose($fp);

        if ($result == "HTTP/1.0 200 OK\r\n") {
            return new CheckResult("HTTP CHECK for [" . $this->host . "]", "Server response was: " . $result, CheckResult::WARNING);
        } else if ($result !== "HTTP/1.1 200 OK\r\n") {
            return new CheckResult("HTTP CHECK for [" . $this->host . "]", "Server response was: " . $result, CheckResult::CRITICAL);
        }

        return new CheckResult("HTTP CHECK for [" . $this->host . "]", "OK", CheckResult::OK);
    }

    public function getName()
    {
        return 'Host check';
    }

}
