<?php

namespace TechImprovement\RateLimit\Cron;

use Magento\Framework\FlagManager;
use TechImprovement\RateLimit\Model\Source\Config;

class CleanLogCronjob
{
    const FLAG_NAME = 'rate_limit_clean_log_counter';
    protected Config $config;
    protected FlagManager $flagManager;

    public function __construct(Config $config, FlagManager $flagManager)
    {
        $this->config = $config;
        $this->flagManager = $flagManager;
    }

    /**
     * Cronjob Description
     *
     * @return void
     */
    public function execute(): void
    {
        $counter = (int)$this->flagManager->getFlagData(self::FLAG_NAME);

        $counter++;

        if ($counter >= $this->config->getLoggingPeriod()) {
            $this->cleanLog();
            $counter = 0;
        }
        $this->flagManager->saveFlag(self::FLAG_NAME, $counter);
    }

    /**
     * Clean log file
     *
     * @return void
     */
    protected function cleanLog(): void
    {
        $logFile = BP . '/var/log/rate_limit.log';
        if (file_exists($logFile)) {
            file_put_contents($logFile, '');
        }
    }
}
