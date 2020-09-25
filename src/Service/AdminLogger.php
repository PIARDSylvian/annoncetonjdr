<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class AdminLogger
{
    private $logger;

    public function __construct(LoggerInterface $adminLogger)
    {
        $this->logger = $adminLogger;
    }

    public function notice($message)
    {
        $this->logger->notice($message);
    }
    public function warning($message)
    {
        $this->logger->warning($message);
    }
}
