<?php

namespace TechImprovement\RateLimit\Observer;

use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\Generic as GenericSession;
use Psr\Log\LoggerInterface;
use TechImprovement\RateLimit\Model\Source\Config;

class RateLimitObserver implements ObserverInterface
{
    /**
     * Known bot user agents
     *
     * @var array
     */
    protected array $knownBotUserAgents = ['Googlebot', 'Bingbot'];

    /**
     * @var HttpResponse
     */

    protected HttpResponse $response;

    /**
     * @var GenericSession
     */

    protected GenericSession $session;

    /**
     * @var LoggerInterface
     */

    protected LoggerInterface $logger;

    /**
     * @var Config
     */

    protected Config $config;

    /**
     * @param HttpResponse $response
     * @param GenericSession $session
     * @param LoggerInterface $logger
     * @param Config $config
     */

    public function __construct(
        HttpResponse    $response,
        GenericSession  $session,
        LoggerInterface $logger,
        Config          $config
    )
    {
        $this->response = $response;
        $this->session = $session;
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     *  Rate limit observer
     *
     * @param Observer $observer
     * @throws LocalizedException
     */
    public function execute(Observer $observer): void
    {
        if (!$this->config->isRateLimitEnabled()) {
            $this->logInfo('Rate limiting is disabled.');
            return;
        }

        $request = $observer->getEvent()->getData('request');
        $clientIp = $request->getClientIp();

        if ($this->isIpWhitelisted($clientIp) || $this->isRequestFromKnownBot($request->getHeader('User-Agent'))) {
            return;
        }

        if ($this->isIpBlacklisted($clientIp)) {
            $this->denyAccess();
            return;
        }

        $this->enforceRateLimit($clientIp);
    }

    /**
     * Log info message
     *
     * @param string $message
     */

    protected function logInfo(string $message): void
    {
        if ($this->config->isLoggingEnabled()) {
            $this->logger->info($message);
        }
    }

    /**
     * Check if the IP is whitelisted
     *
     * @param string $ip
     * @return bool
     */

    protected function isIpWhitelisted(string $ip): bool
    {
        if (in_array($ip, $this->config->getWhitelist())) {
            $this->logInfo("Request from whitelisted IP: $ip. Bypassing rate limit.");
            return true;
        }
        return false;
    }

    /**
     * Check if the request is from a known bot
     *
     * @param string $userAgent
     * @return bool
     */

    protected function isRequestFromKnownBot(string $userAgent): bool
    {
        if (!$this->config->getKnownBots()) {
            return false;
        }
        $isKnownBot = in_array($userAgent, $this->knownBotUserAgents);
        if ($isKnownBot) {
            $this->logInfo('Request is from a known bot, bypassing rate limit.');
        }
        return $isKnownBot;
    }

    /**
     * Check if the IP is blacklisted
     *
     * @param string $ip
     * @return bool
     */

    protected function isIpBlacklisted(string $ip): bool
    {
        if (in_array($ip, $this->config->getBlacklist())) {
            $this->logInfo("Request from blacklisted IP: $ip. Denying access.");
            return true;
        }
        return false;
    }

    /**
     * Deny access
     */

    protected function denyAccess(): void
    {
        $this->response->setStatusCode(403)
            ->setHeader('Content-Type', 'text/plain')
            ->setBody('Access Denied')
            ->sendResponse();
    }

    /**
     * Enforce rate limit
     *
     * @param string $ip
     * @throws LocalizedException
     */
    protected function enforceRateLimit(string $ip): void
    {
        $rateInfo = $this->session->getData('rate_info') ?: [];
        $currentTime = time();
        $maxRequests = $this->config->getMaxRequests();
        $timePeriod = $this->config->getTimePeriod();

        if (isset($rateInfo[$ip])) {
            [$firstRequestTime, $requestCount] = $rateInfo[$ip];
            $windowLength = $currentTime - $firstRequestTime;

            if ($windowLength <= $timePeriod && $requestCount >= $maxRequests) {
                $this->handleRateLimitExceeded($ip, $requestCount, $windowLength, $maxRequests, $timePeriod);
            }

            $rateInfo[$ip] = $windowLength > $timePeriod ? [$currentTime, 1] : [$firstRequestTime, ++$requestCount];
        } else {
            $this->logInfo("New IP detected: $ip. Initializing rate limiting.");
            $rateInfo[$ip] = [$currentTime, 1];
        }

        $this->session->setData('rate_info', $rateInfo);
    }

    /**
     * Handle rate limit exceeded
     *
     * @param string $ip
     * @param int $requestCount
     * @param int $windowLength
     * @param int $maxRequests
     * @param int $timePeriod
     * @throws LocalizedException
     */
    protected function handleRateLimitExceeded(string $ip, int $requestCount, int $windowLength, int $maxRequests, int $timePeriod): void
    {
        $this->response->setStatusCode(429)
            ->setHeader('Content-Type', 'text/plain')
            ->setBody('Error 429 Too many requests')
            ->sendResponse();
        $this->logInfo("Hit enforcement window: \"tech_crawler_protection_$ip\" Count: $requestCount/$maxRequests length: $windowLength secs/$timePeriod");
        throw new LocalizedException(__('Rate limit exceeded'));
    }
}
