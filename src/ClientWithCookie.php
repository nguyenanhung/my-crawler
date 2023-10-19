<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 19/10/2023
 * Time: 15:49
 */

namespace nguyenanhung\Libraries\Crawler;

use Exception;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

class ClientWithCookie
{
    const _REQUEST_TIMEOUT_ = 690;

    const _REQUEST_DEFAULT_OPTS = [
        'timeout' => self::_REQUEST_TIMEOUT_
    ];

    const _REQUEST_BYPASS_VERIFY_OPTS = [
        'timeout'     => self::_REQUEST_TIMEOUT_,
        'verify_peer' => false,
        'verify_host' => false
    ];

    public $cookieFilesPath;

    public $crawlerNoVerify = false;

    public $crawlerOptions;

    public function setCookieFilePath($cookieFilesPath = ''): self
    {
        $this->cookieFilesPath = $cookieFilesPath;
        return $this;
    }

    public function getCookieFilePath(): string
    {
        return $this->cookieFilesPath;
    }

    public function enabledCrawlerBypassVerify(): self
    {
        $this->crawlerNoVerify = true;
        return $this;
    }

    public function disabledCrawlerBypassVerify(): self
    {
        $this->crawlerNoVerify = false;
        return $this;
    }

    public function handle($url, $method = 'GET'): ?Crawler
    {
        if (!file_exists($this->cookieFilesPath)) {
            return null;
        }
        try {
            if (is_array($this->crawlerOptions) && !empty($this->crawlerOptions)) {
                $httpClient = HttpClient::create($this->crawlerOptions);
            } elseif ($this->crawlerNoVerify === true) {
                $httpClient = HttpClient::create(self::_REQUEST_BYPASS_VERIFY_OPTS);
            } else {
                $httpClient = HttpClient::create(self::_REQUEST_DEFAULT_OPTS);
            }

            $client = new Client($httpClient);
            $client->request($method, $url);

            // Get all cookies
            $cookies = $client->getCookieJar()->all();
            $cookies = array_map('strval', $cookies); // Cookie::__toString
            file_put_contents($this->cookieFilesPath, json_encode($cookies));

            // Update cookies
            $cookieJar = new CookieJar();
            $cookies = json_decode(
                file_get_contents($this->cookieFilesPath),
                true
            );
            $cookieJar->updateFromSetCookie($cookies);
            unset($client);

            $client = new Client($httpClient, null, $cookieJar);

            return $client->request($method, $url);
        } catch (Exception $exception) {
            return null;
        }
    }
}
