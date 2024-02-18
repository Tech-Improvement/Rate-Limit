<h1><strong>TechImprovement_RateLimit Magento 2 Module </strong></h1>
<h2><strong>Module Overview</strong></h2>
<p>The <code><strong>TechImprovement_RateLimit</strong></code> module for Magento 2 provides functionality to limit the rate of requests to the Magento site, which can help prevent abuse or excessive load on the server. It allows whitelisting and blacklisting of IP addresses, logging of rate-limiting events, and exemption of known good bots from rate limiting.</p>
<h2><strong>Features</strong></h2>
<ul>
<li>Enable or disable rate limiting.</li>
<li>Configure the maximum number of requests allowed and the time period for rate limiting.</li>
<li>Whitelist IPs to exclude from rate limiting.</li>
<li>Blacklist IPs to always deny access.</li>
<li>Enable logging for rate limiting events and configure log cleaning schedule.</li>
<li>Exempt known good bots from rate limiting.</li>
</ul>
<h2><strong>Installation</strong></h2>
<h3><strong>Via Composer</strong></h3>
<ol>
<li>Add the module's repository to your Magento project's <code><strong>composer.json</strong></code> file (if it is hosted on a repository).</li>
<li>Run <code><strong>composer require techimprovement/rate-limit</strong></code> to add the module to your project.</li>
<li>Enable the module by running <strong><code>php bin/magento module:enable TechImprovement_RateLimit</code>.</strong></li>
<li>Run <strong><code>php bin/magento setup:upgrade</code></strong> to install the module.</li>
<li>Deploy static content (if necessary) with <strong><code>php bin/magento setup:static-content:deploy</code>.</strong></li>
</ol>
<h3><strong>Manually</strong></h3>
<ol>
<li>Create the following directory structure in your Magento installation: <strong><code>app/code/TechImprovement/RateLimit</code>.</strong></li>
<li>Upload the module files to the newly created directory.</li>
<li>Run <strong><code>php bin/magento module:enable TechImprovement_RateLimit</code></strong> from the Magento root directory.</li>
<li>Run <strong><code>php bin/magento setup:upgrade</code></strong> to install the module.</li>
<li>Deploy static content (if necessary) with <strong><code>php bin/magento setup:static-content:deploy</code></strong>.</li>
</ol>
<h2><strong>Configuration</strong></h2>
<ol>
<li>Navigate to the Magento Admin Panel.</li>
<li>Go to <strong><code>Stores</code> &gt; <code>Configuration</code> &gt; <code>TechImprovement</code> &gt; <code>Rate Limit</code>.</strong></li>
<li>Configure the settings as needed:
<ul>
<li>Enable rate limiting and logging.</li>
<li>Set maximum requests and time period.</li>
<li>Manage whitelist and blacklist IPs.</li>
<li>Choose the known bots exemption status.</li>
<li>Configure log cleaning schedule.</li>
</ul>
</li>
<li>Save the configuration.</li>
</ol>
<h2><strong>Usage</strong></h2>
<p>After configuring the module, it will automatically start to limit the rate of requests based on the specified settings. Whitelisted IP addresses will bypass rate limiting, while blacklisted IPs will be denied access. Known bots can either be exempted or subject to rate limiting based on the configuration.</p>
<p>The module will log rate-limiting events if logging is enabled. You can view these logs in the specified log file (by default <strong><code>var/log/rate_limit.log</code></strong>). A cron job will clean the log file according to the schedule set in the configuration.</p>
<h2><strong>Enable/Disable Module</strong></h2>
<p>To enable or disable the module, use the following CLI commands:</p>
<ul>
<li>Enable: <strong><code>php bin/magento module:enable TechImprovement_RateLimit</code></strong></li>
<li>Disable:<strong> <code>php bin/magento module:disable TechImprovement_RateLimit</code></strong></li>
</ul>
<p>After enabling or disabling, always run <code>php bin/magento setup:upgrade</code> and clear the cache.</p>
<h2><strong>Support</strong></h2>
<p>For any issues or questions regarding the module, please contact support at <a title="info@techimprovement.net" href="mailto:info@techimprovement.net">info@techimprovement.net</a>.</p>
<h2><strong>Author</strong></h2>
<p>This module is brought to you by <strong>TechImprovement Inc</strong>, a provider of Magento extensions and custom eCommerce solutions.</p>

## Support Our Project

<p>If you find this project helpful, consider supporting us with a donation by clicking this button <a href="https://paypal.me/AnduelHoxha?country.x=US&locale.x=en_US"><img width="150" src="Donate-button.png"></a> or scan the QR code below:</p>

![Donate QR Code](qrcode.png)
