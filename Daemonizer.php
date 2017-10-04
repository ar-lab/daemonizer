<?php

abstract class Daemonizer
{

    public function daemonize()
    {
        $pid = pcntl_fork();

        if ($pid) {
            return;
        }

        posix_setsid();

        $logDir = dirname(__FILE__) . '/../log';

        if (!is_dir($logDir)) {
            mkdir($logDir);
        }

        ini_set('error_log', $logDir . '/error.log');

        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);
        $STDIN = fopen('/dev/null', 'r');
        $STDOUT = fopen($logDir . '/application.log', 'ab');
        $STDERR = fopen($logDir . '/daemon.log', 'ab');

        $this->run();
    }

    abstract public function run();

}
