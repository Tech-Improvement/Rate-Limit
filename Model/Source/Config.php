<?php

namespace TechImprovement\RateLimit\Model\Source;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * Configuration paths
     */
    public const XML_PATH_ENABLED = 'techimprovement_ratelimit/general/enabled';
    public const XML_PATH_ENABLE_LOGGING = 'techimprovement_ratelimit/general/enabled_logging';
    public const XML_PATH_MAX_REQUESTS = 'techimprovement_ratelimit/general/max_requests';
    public const XML_PATH_MAX_TIME_PERIOD = 'techimprovement_ratelimit/general/time_period';
    public const XML_PATH_KNOWN_BOTS = 'techimprovement_ratelimit/general/known_bots';
    public const XML_PATH_WHITELIST = 'techimprovement_ratelimit/general/whitelist';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */

    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Check if the rate limit feature is enabled
     *
     * @return bool
     */
    public function isRateLimitEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if logging is enabled
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_LOGGING,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get max requests allowed in the time period
     *
     * @return int
     */
    public function getMaxRequests(): int
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAX_REQUESTS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the time period in seconds
     *
     * @return int
     */
    public function getTimePeriod(): int
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MAX_TIME_PERIOD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get if known bots are allowed
     *
     * @return bool
     */
    public function getKnownBots(): bool
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_KNOWN_BOTS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve the whitelist IP list
     *
     * @return array
     */
    public function getWhitelist(): array
    {
        $whitelist = $this->scopeConfig->getValue(
            self::XML_PATH_WHITELIST,
            ScopeInterface::SCOPE_STORE
        );

        $whitelist = $whitelist ?? '';

        return array_filter(preg_split('/\r\n|\r|\n/', $whitelist));
    }

    /**
     * Retrieve the blacklist IP list
     *
     * @return array
     */

    public function getBlacklist(): array
    {
        $blacklist = $this->scopeConfig->getValue(
            'techimprovement_ratelimit/general/blacklist',
            ScopeInterface::SCOPE_STORE
        );

        return array_filter(preg_split('/\r\n|\r|\n/', $blacklist ?? ''));
    }

    /**
     * Retrieve the logging period
     *
     * @return string
     */

    public function getLoggingPeriod(): string
    {
        return $this->scopeConfig->getValue(
            'techimprovement_ratelimit/general/logging_period',
            ScopeInterface::SCOPE_STORE
        );
    }
}
