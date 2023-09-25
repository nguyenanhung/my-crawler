<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 25/09/2023
 * Time: 22:05
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerHandleFilterPageMetaTrait
{
    public function crawlerHandleFilterPageMetaTitle(Crawler $crawler): string
    {
        $checklists = [
            'meta[name="title"]',
            'meta[itemprop="title"]',
            'meta[property="og:title"]',
            'meta[property="twitter:title"]',
            'meta[name="og:title"]',
            'meta[name="twitter:title"]',
            "meta[name='title']",
            "meta[itemprop='title']",
            "meta[property='og:title']",
            "meta[property='twitter:title']",
            "meta[name='og:title']",
            "meta[name='twitter:title']",
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaDescription(Crawler $crawler): string
    {
        $checklists = [
            'meta[name="description"]',
            'meta[itemprop="description"]',
            'meta[property="og:description"]',
            'meta[property="twitter:description"]',
            'meta[name="og:description"]',
            'meta[name="twitter:description"]',
            "meta[name='description']",
            "meta[itemprop='description']",
            "meta[property='og:description']",
            "meta[property='twitter:description']",
            "meta[name='og:description']",
            "meta[name='twitter:description']",
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaKeywords(Crawler $crawler): string
    {
        $checklists = [
            'meta[name="keywords"]',
            'meta[name="news_keywords"]',
            'meta[itemprop="keywords"]',
            'meta[itemprop="news_keywords"]',
            'meta[property="og:keywords"]',
            'meta[property="og:news_keywords"]',
            'meta[property="twitter:keywords"]',
            'meta[property="twitter:news_keywords"]',
            'meta[name="og:keywords"]',
            'meta[name="og:news_keywords"]',
            'meta[name="twitter:keywords"]',
            'meta[name="twitter:news_keywords"]',
            "meta[name='keywords']",
            "meta[name='news_keywords']",
            "meta[itemprop='keywords']",
            "meta[itemprop='news_keywords']",
            "meta[property='og:keywords']",
            "meta[property='og:news_keywords']",
            "meta[property='twitter:keywords']",
            "meta[property='twitter:news_keywords']",
            "meta[name='og:keywords']",
            "meta[name='og:news_keywords']",
            "meta[name='twitter:keywords']",
            "meta[name='twitter:news_keywords']",
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaImageSrc(Crawler $crawler): string
    {
        $checklists = [
            'meta[property="og:image"]',
            'meta[property="og:image:url"]',
            'meta[property="twitter:image"]',
            'meta[property="dable:image"]',
            'meta[itemprop="image"]',
            'meta[itemprop="thumbnailUrl"]',
            'meta[name="og:image"]',
            'meta[name="image_src"]',
            'meta[name="image"]',
            'meta[name="thumbnail"]',
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaPublishedTime(Crawler $crawler): string
    {
        $checklists = [
            'meta[property="article:published_time"]',
            'meta[property="og:updated_time"]',
            'meta[itemprop="datePublished"]',
            'meta[itemprop="dateCreated"]',
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaModifiedTime(Crawler $crawler): string
    {
        $checklists = [
            'meta[property="article:modified_time"]',
            'meta[property="og:modified_time"]',
            'meta[itemprop="dateModified"]',
            'meta[itemprop="dateCreated"]',
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }

    public function crawlerHandleFilterPageMetaCreatedTime(Crawler $crawler): string
    {
        $checklists = [
            'meta[property="article:created_time"]',
            'meta[property="og:created_time"]',
            'meta[itemprop="dateCreated"]',
        ];
        foreach ($checklists as $filter) {
            $content = $crawler->filter($filter)->first()->attr('content');
            if (!empty($content)) {
                return trim($content);
            }
        }
        return '';
    }
}
