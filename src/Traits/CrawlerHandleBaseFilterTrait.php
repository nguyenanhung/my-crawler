<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 18:01
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerHandleBaseFilterTrait
{
    // Matches
    public function crawlerMatchesWithSelector(Crawler $crawler, $selector = ''): bool
    {
        return $crawler->matches($selector);
    }

    // Text Parse
    public function crawlerFilterGetText(Crawler $crawler, $filter = ''): string
    {
        $response = $crawler->filter($filter)->each(function ($node) {
            return $node->text();
        });
        $content = $response[0] ?? '';
        $content = (string)$content;
        return trim($content);
    }

    public function crawlerFilterGetInnerText(Crawler $crawler, $filter = ''): string
    {
        $response = $crawler->filter($filter)->each(function ($node) {
            return $node->innerText();
        });
        $content = $response[0] ?? '';
        $content = (string)$content;
        return trim($content);
    }

    public function crawlerFilterGetRawText(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->text();
        });
    }

    public function crawlerFilterGetRawInnerText(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->innerText();
        });
    }

    // HTML Parse
    public function crawlerFilterGetHtml(Crawler $crawler, $filter = ''): string
    {
        $response = $crawler->filter($filter)->each(function ($node) {
            return $node->html();
        });
        $content = $response[0] ?? '';
        $content = (string)$content;
        return trim($content);
    }

    public function crawlerFilterGetRawHtml(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->html();
        });
    }

    public function crawlerFilterGetRawOuterHtml(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->outerHtml();
        });
    }

    // Image Parse
    public function crawlerFilterGetRawImages(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->image();
        });
    }

    public function crawlerFilterGetRawImagesList(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->images();
        });
    }

    // Link URL Parse
    public function crawlerFilterGetRawLink(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->link();
        });
    }

    public function crawlerFilterGetRawLinksList(Crawler $crawler, $filter = ''): array
    {
        return $crawler->filter($filter)->each(function ($node) {
            return $node->links();
        });
    }

    // Alias
    public function crawlerFilterText(Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerFilterGetText($crawler, $filter);
    }

    public function crawlerFilterInnerText(Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerFilterGetInnerText($crawler, $filter);
    }

    public function crawlerFilterRawText(Crawler $crawler, $filter = ''): array
    {
        return $this->crawlerFilterGetRawText($crawler, $filter);
    }

    public function crawlerFilterRawInnerText(Crawler $crawler, $filter = ''): array
    {
        return $this->crawlerFilterGetRawInnerText($crawler, $filter);
    }

    public function crawlerFilterHtml(Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerFilterGetHtml($crawler, $filter);
    }

    public function crawlerFilterRawHtml(Crawler $crawler, $filter = ''): array
    {
        return $this->crawlerFilterGetRawHtml($crawler, $filter);
    }

    public function crawlerFilterRawOuterHtml(Crawler $crawler, $filter = ''): array
    {
        return $this->crawlerFilterGetRawOuterHtml($crawler, $filter);
    }
}
