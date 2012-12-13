<?php

namespace Tvision\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Liip\Monitor\Result\CheckResult;

/**
 * Created by JetBrains PhpStorm.
 * User: proudlygeek
 * Date: 11/20/12
 * Time: 12:40 PM
 * To change this template use File | Settings | File Templates.
 */
class HealthCheckController extends Controller
{


    public function redirectAction(Request $request) {
        return $this->redirect("/monitor");
    }

    public function heartbeatAction(Request $request) {
        $runner = $this->get('liip_monitor.check.runner');

        $results = $runner->runAllChecks();
        $globalStatus = '';

        foreach ($results as $id => $result) {
            $tmp = $result->toArray();
            if ($tmp['status'] > CheckResult::WARNING && !preg_match("/jig.terravision.eu/", $tmp['checkName']) && !preg_match("/www.terravisiontravel.com/", $tmp['checkName'])) {
                $globalStatus .= 'KO | ' . $id . '. ' . $tmp['checkName'] . ':' . $tmp['message'] ."\n";
            }
        }

        if (strlen($globalStatus) == 0) {
            $globalStatus = 'OK';
        }

        return new Response($globalStatus, 200, array('Content-Type' => 'text/plain'));
    }

}
