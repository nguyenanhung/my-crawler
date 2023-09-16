<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 18:05
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerHandleReformatAndRemovedTrait
{
    // Page Content Reformat
    public function crawlerReformatContentRemovedWithFilter(Crawler $crawler, $content, $filterRemoved = ''): string
    {
        if (empty($filterRemoved)) {
            return $content;
        }
        $ads = $this->crawlerFilterGetRawHtml($crawler, $filterRemoved);
        foreach ($ads as $ad) {
            $content = str_replace($ad, "", $content);
        }
        return trim($content);
    }

    public function crawlerReformatContentRemovedWithFilterOuterHtml(Crawler $crawler, $content, $filterRemoved = ''): string
    {
        if (empty($filterRemoved)) {
            return $content;
        }
        $ads = $this->crawlerFilterRawOuterHtml($crawler, $filterRemoved);
        foreach ($ads as $ad) {
            $content = str_replace($ad, "", $content);
        }
        return trim($content);
    }

    public function reformatDataContentAndItemScopePublisherRemoved($content = '', $openTag = '<span itemprop="publisher" itemscope="itemscope" itemtype="http://schema.org/Organization">', $closeTag = '</span>'): string
    {
        $itemScopePublisher = $this->getContentDataValueFromHtmlTag($content, $openTag, $closeTag);
        $content = str_replace($itemScopePublisher, '', $content);
        return trim($content);
    }

    public function reformatGetFirstPositionDataContentWithFilterHtmlTag($content = '', $filterHtmlTag = '')
    {
        if (empty($content) || empty($filterHtmlTag)) {
            return $content;
        }

        $explode = explode($filterHtmlTag, $content);
        $countEx = count($explode);
        if ($countEx > 1 && isset($explode[1])) {
            $content = $explode[0] ?? '';
        }
        return $content;
    }

    public function reformatSkipFirstDataContentWithFilterPositionHtmlTag($content = '', $filterHtmlTag = '')
    {
        if (empty($content) || empty($filterHtmlTag)) {
            return $content;
        }

        $explode = explode($filterHtmlTag, $content);
        $countEx = count($explode);
        if ($countEx > 1 && isset($explode[1])) {
            $content = $explode[1] ?? '';
        }
        return $content;
    }

    public function reformatGetNextDataContentWithFilterPositionHtmlTag($content = '', $filterHtmlTag = '', $position = 1)
    {
        if (empty($content) || empty($filterHtmlTag)) {
            return $content;
        }

        $explode = explode($filterHtmlTag, $content);
        $countEx = count($explode);
        if ($countEx > 1 && isset($explode[$position])) {
            $content = $explode[$position] ?? '';
        }
        return $content;
    }

    public function reformatDataContentWithFilterHtmlTag($content = '', $filterHtmlTag = '')
    {
        return $this->reformatGetFirstPositionDataContentWithFilterHtmlTag($content, $filterHtmlTag);
    }

    public function reformatDataAndAddSiteUrlIntoContent($content = '', $siteUrl = '', $match = '')
    {
        if (empty($content) || empty($siteUrl) || empty($match)) {
            return $content;
        }
        $content = str_replace($match, trim($siteUrl . $match), $content);

        return trim($content);
    }

    public function reformatDataContentAndRemovedLinkHrefWithRegex($content, $listLinks)
    {
        if (empty($content) || empty($listLinks)) {
            return $content;
        }
        foreach ($listLinks as $item) {
            $itemNew = preg_replace('/<a(.*?)href="(.*?)"(.*?)>/', '', $item);
            $itemNew = preg_replace('#</a>#', '', $itemNew);
            $content = str_replace($item, $itemNew, $content);
        }
        return $content;
    }

    public function filterTagClassNameRemoved($html, $tag, $classname = '')
    {
        if (empty($classname) || empty($tag)) {
            return $html;
        }
        if (strpos($classname, '"')) {
            $pattern = "/<" . trim($tag) . $classname . ".*?\/" . trim($tag) . ">/s";
        } elseif (strpos($classname, "'")) {
            $pattern = '/<' . trim($tag) . $classname . '.*?\/' . trim($tag) . '>/s';
        } else {
            $pattern = null;
        }
        if ($pattern === null) {
            return $html;
        }

        return preg_replace($pattern, "", $html) ?: $html;
    }

    public function filterScriptTagRemoved($html)
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    }

    public function filterCssStyleTagRemoved($html)
    {
        return preg_replace('#<style>(.*?)</style>#is', '', $html);
    }

    // Alias
    public function reformatContentRemovedLinkHref($contentText, $listLinks)
    {
        return $this->reformatDataContentAndRemovedLinkHrefWithRegex($contentText, $listLinks);
    }

    public function itemScopePublisherRemoved($contentText = ''): string
    {
        return $this->reformatDataContentAndItemScopePublisherRemoved($contentText);
    }

    public function crawlerHtmlEmbedContentRemoved($contentText, Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerReformatContentRemovedWithFilter($crawler, $contentText, $filter);
    }

    public function crawlerHtmlEmbedContentOuterHtmlRemoved($contentText, Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerReformatContentRemovedWithFilterOuterHtml($crawler, $contentText, $filter);
    }
}
