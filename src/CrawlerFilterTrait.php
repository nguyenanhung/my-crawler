<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 07/09/2023
 * Time: 00:12
 */

namespace nguyenanhung\Libraries\Crawler;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerFilterTrait
{
    public function crawlerFilterGetText(Crawler $crawler, $filter = ''): string
    {
        $response = $crawler->filter($filter)->each(function ($node) {
            return $node->text();
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

    public function crawlerParseGetHtmlFullPage(Crawler $crawler): array
    {
        return $this->crawlerFilterGetRawHtml($crawler, 'html');
    }

    public function crawlerParseGetHtmlHeader(Crawler $crawler): array
    {
        return $this->crawlerFilterGetRawHtml($crawler, 'head');
    }

    public function crawlerParseGetHtmlBody(Crawler $crawler): array
    {
        return $this->crawlerFilterGetRawHtml($crawler, 'body');
    }

    public function crawlerGetDataHeadPageTitle($str, $headline = ''): string
    {
        $explode = explode('<title>', $str);
        if (isset($explode[1])) {
            $explode = explode('</title>', $explode[1]);
            if (isset($explode[0]) && !empty($explode[0])) {
                $title = trim($explode[0]);
                if (!empty($headline)) {
                    $title = str_replace($headline, '', $title);
                }
                return trim($title);
            }
        }

        return '';
    }

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

    public function getDataFilterScriptJsonStructureDataFromHtml($html): array
    {
        $openTag = '<script type="application/ld+json">';
        $closeTag = '</script>';
        $needPointHttps = 'https://schema.org';
        $needPointHttp = 'http://schema.org';
        $exs = explode($openTag, $html);
        $c = count($exs);
        $results = array();
        if ($c > 1) {
            foreach ($exs as $item) {
                if (mb_strpos($item, $needPointHttps)) {
                    $exs2 = explode($closeTag, $item);
                    if (isset($exs2[1])) {
                        $json = trim($exs2[0]);
                        $arr = json_decode($json, true);
                        $results[] = $arr;
                    }
                } elseif (mb_strpos($item, $needPointHttp)) {
                    $exs2 = explode($closeTag, $item);
                    if (isset($exs2[1])) {
                        $json = trim($exs2[0]);
                        $arr = json_decode($json, true);
                        $results[] = $arr;
                    }
                }
            }
        }
        return $results;
    }

    public function getContentDataValueWithRegexMatch(string $txt = '', string $openTag = '', string $closeTag = '')
    {
        if (mb_strpos($openTag, '"')) {
            $pattern = '#' . $openTag . '(.*?)' . $closeTag . '#';
        } elseif (mb_strpos($openTag, "'")) {
            $pattern = "#" . $openTag . "(.*?)" . $closeTag . "#";
        } else {
            $pattern = null;
        }
        if ($pattern === null) {
            return $txt;
        }
        preg_match($pattern, $txt, $res);

        return $res[1] ?? $txt;
    }

    public function getContentDataValueWithRegexMatchAll(string $txt = '', string $openTag = '', string $closeTag = '')
    {
        if (mb_strpos($openTag, '"')) {
            $pattern = '#' . $openTag . '(.*?)' . $closeTag . '#';
        } elseif (mb_strpos($openTag, "'")) {
            $pattern = "#" . $openTag . ".(.*?)." . $closeTag . "#";
        } else {
            $pattern = null;
        }
        if ($pattern === null) {
            return $txt;
        }
        preg_match_all($pattern, $txt, $res);

        return $res;
    }

    public function getContentDataValueFromHtmlTag(string $txt = '', string $openTag = '', string $closeTag = ''): string
    {
        if (empty($txt) || empty($openTag) || empty($closeTag)) {
            return '';
        }
        $f = mb_strpos($txt, $openTag) + mb_strlen($openTag);
        $l = mb_strpos($txt, $closeTag);

        return ($f <= $l) ? mb_substr($txt, $f, $l - $f) : '';
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

    public function parseGetContentValueWithDoubleExplodeAndStripTags($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag): string
    {
        if (empty($str) || empty($openTag) || empty($closeTag) || empty($nextOpenTag) || empty($nextCloseTag)) {
            return '';
        }
        $explode = explode($openTag, $str);
        if (isset($explode[1])) {
            $explode = explode($closeTag, $explode[1]);
            if (isset($explode[0]) && !empty($explode[0])) {
                $next = trim($explode[0]);
                $explode = explode($nextOpenTag, $next);
                if (isset($explode[1])) {
                    $explode = explode($nextCloseTag, $explode[1]);
                    if (isset($explode[0]) && !empty($explode[0])) {
                        return trim(strip_tags(trim($explode[0])));
                    }
                }
            }
        }
        return '';
    }

    public function parseGetContentValueWithExplodeAndStripTags($str, $openTag, $closeTag): string
    {
        if (empty($str) || empty($openTag) || empty($closeTag)) {
            return '';
        }
        $explode = explode($openTag, $str);
        if (isset($explode[1])) {
            $explode = explode($closeTag, $explode[1]);
            if (isset($explode[0]) && !empty($explode[0])) {
                return trim(strip_tags(trim($explode[0])));
            }
        }

        return '';
    }

    public function parseGetContentValueWithDoubleExplode($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag): string
    {
        if (empty($str) || empty($openTag) || empty($closeTag) || empty($nextOpenTag) || empty($nextCloseTag)) {
            return '';
        }
        $explode = explode($openTag, $str);
        if (isset($explode[1])) {
            $explode = explode($closeTag, $explode[1]);
            if (isset($explode[0]) && !empty($explode[0])) {
                $next = trim($explode[0]);
                $explode = explode($nextOpenTag, $next);
                if (isset($explode[1])) {
                    $explode = explode($nextCloseTag, $explode[1]);
                    if (isset($explode[0]) && !empty($explode[0])) {
                        return trim($explode[0]);
                    }
                }
            }
        }
        return '';
    }

    public function parseGetContentValueWithExplode($str, $openTag, $closeTag): string
    {
        if (empty($str) || empty($openTag) || empty($closeTag)) {
            return '';
        }
        $explode = explode($openTag, $str);
        if (isset($explode[1])) {
            $explode = explode($closeTag, $explode[1]);
            if (isset($explode[0]) && !empty($explode[0])) {
                return trim($explode[0]);
            }
        }

        return '';
    }

    public function reformatDataContentWithFilterHtmlTag($content = '', $filterHtmlTag = '')
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
        if (empty($listLinks)) {
            return $content;
        }
        foreach ($listLinks as $item) {
            $itemNew = preg_replace('/<a(.*?)href="(.*?)"(.*?)>/', '', $item);
            $itemNew = preg_replace('#</a>#', '', $itemNew);
            $content = str_replace($item, $itemNew, $content);
        }
        return $content;
    }

    public function parseMatchesImageSrcFromChecklists($txt, $checklists): string
    {
        $txt = trim($txt);
        if (empty($txt) || empty($checklists)) {
            return $txt;
        }
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($txt, $openTag, $closeTag);
            if (!empty($res)) {
                return trim($res);
            }
        }
        return '';
    }

    public function reformatDataContentImageLinkInContent($content, $listImages)
    {
        if (empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $imgSrc = $this->parseMatchesImageSrcFromChecklists(
                $oldItem,
                array(
                    'data-src="' => '"',
                    'data-original="' => '"',
                    'src="' => '"',
                )
            );
            $newItem = '<img class="news-posts-content-image" width="100%" src="' . trim($imgSrc) . '" title="' . trim($title) . '" alt="' . trim($alt) . '" />';
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    public function getFirstImageSrcLinkInDataContent($crawler, $filter, $openTag = 'src="', $closeTag = '"'): string
    {
        $contentListImages = $this->crawlerFilterGetRawOuterHtml($crawler, $filter);
        $imageSrc = isset($contentListImages[0]) ? $this->parseGetContentValueWithExplode($contentListImages[0], $openTag, $closeTag) : '';
        return trim($imageSrc);
    }

    public function getDataHeadMetaPageTitle($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta name="title" content="' => '"',
            '<meta itemprop="title" content="' => '"',
            '<meta property="og:title" content="' => '"',
            '<meta property="og:title" itemprop="title" content="' => '"',
            '<meta name="twitter:title" content="' => '"',
            '"headline":"' => '"',
            '"headline": "' => '"',
            '<meta id="title" name="title" content="' => '"',
            '<meta id="Title" name="title" content="' => '"',
        ];
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($headStr, $openTag, $closeTag);
            if (!empty($res)) {
                return trim($res);
            }
        }
        return '';
    }

    public function getDataHeadMetaDescription($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta name="description" content="' => '"',
            '<meta itemprop="description" content="' => '"',
            '<meta property="og:description" content="' => '"',
            '<meta name="twitter:description" content="' => '"',
            '"description":"' => '"',
            '"description": "' => '"',
            '"description ": "' => '"',
            '<meta id="description" name="description" content="' => '"',
            '<meta id="metaDes" name="description" content="' => '"',
            '<meta id="metades" name="description" content="' => '"',
            '<meta id="metaDescription" name="description" content="' => '"',
            '<meta id="MetaDescription" name="DESCRIPTION" content="' => '"',
        ];
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($headStr, $openTag, $closeTag);
            if (!empty($res)) {
                return trim($res);
            }
        }
        return '';
    }

    public function getDataHeadMetaKeywords($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta name="keywords" content="' => '"',
            '<meta name="news_keywords" content="' => '"',
            '<meta itemprop="keywords" name="keywords" content="' => '"',
            '<meta id="metakeywords" name="keywords" content="' => '"',
            '<meta id="MetaKeyword" name="keywords" content="' => '"',
            '<meta id="keywords" name="keywords" content="' => '"',
            '<meta id="MetaKeywords" name="keywords" content="' => '"',
            '<meta id="MetaKeywords" name="KEYWORDS" content="' => '"',
        ];
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($headStr, $openTag, $closeTag);
            if (!empty($res)) {
                return trim($res);
            }
        }
        return '';
    }

    public function getDataHeadMetaImageSrcUrl($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta property="og:image" content="' => '"',
            '<meta property="og:image:url" content="' => '"',
            '<meta name="twitter:image" content="' => '"',
            '<meta itemprop="image" content="' => '"',
            '<meta itemprop="thumbnailUrl" content="' => '"',
            '<meta property="og:image" itemprop="thumbnailUrl" content="' => '"',
            '<link rel="image_src" href="' => '"',
        ];
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($headStr, $openTag, $closeTag);
            if (!empty($res)) {
                return trim($res);
            }
        }
        return '';
    }

    public function getDataHeadMetaTimeAndReformatFormat($headStr, $checklists): string
    {
        foreach ($checklists as $openTag => $closeTag) {
            $res = $this->parseGetContentValueWithExplodeAndStripTags($headStr, $openTag, $closeTag);
            if (!empty($res)) {
                $time = trim($res);
                $time = str_replace(
                    array('::', '+00:00', '+06:00', '+07:00', '+08:00', '.000', 'T'),
                    array(':', '', '', '', '', '', ' '),
                    $time
                );
                return trim($time);
            }
        }
        return '';
    }

    public function getDataHeadMetaPublishedTime($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta property="article:published_time" content="' => '"',
            '"datePublished":"' => '"',
            '"datePublished": "' => '"',
            '<meta property="og:updated_time" content="' => '"',
            '<meta itemprop="datePublished" content="' => '"',
        ];
        return $this->getDataHeadMetaTimeAndReformatFormat($headStr, $checklists);
    }

    public function getDataHeadMetaModifiedTime($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta property="article:modified_time" content="' => '"',
            '"dateModified":"' => '"',
            '"dateModified": "' => '"',
            '<meta itemprop="dateModified" content="' => '"',
        ];
        return $this->getDataHeadMetaTimeAndReformatFormat($headStr, $checklists);
    }

    public function getDataHeadMetaCreatedTime($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta itemprop="dateCreated" content="' => '"',
            '<meta property="article:created_time" content="' => '"',
        ];
        return $this->getDataHeadMetaTimeAndReformatFormat($headStr, $checklists);
    }

    public function getDataContentSapoText(Crawler $crawler, $sapoFilter, $replace = ''): string
    {
        $head = $this->crawlerParseGetHtmlHeader($crawler);
        $sapoText = $this->crawlerFilterGetText($crawler, $sapoFilter);
        if (empty($sapoText)) {
            $sapoText = isset($head[0]) ? $this->getDataHeadMetaDescription($head[0]) : '';
        }
        if (!empty($replace)) {
            $sapoText = str_replace($replace, '', $sapoText);
        }
        return trim($sapoText);
    }
}
