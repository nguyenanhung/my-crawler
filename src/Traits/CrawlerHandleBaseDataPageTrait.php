<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 18:02
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait CrawlerHandleBaseDataPageTrait
{
    // Page Parse
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

    public function parseHtmlHeader(Crawler $crawler): array
    {
        return $this->crawlerFilterRawHtml($crawler, 'head');
    }

    public function parseHtmlBody(Crawler $crawler): array
    {
        return $this->crawlerFilterRawHtml($crawler, 'body');
    }

    public function crawlerGetDataHeadPageTitle($str, $headline = ''): string
    {
        $listTitleTags = [
            '<title>' => '</title>',
            '<title id="title">' => '</title>',
        ];
        foreach ($listTitleTags as $openTag => $closeTag) {
            $text = $this->getContentValueWithExplodeAndStripTags($str, $openTag, $closeTag);
            if (!empty($text)) {
                $text = str_replace(array('<', '>'), '', $text);
                if (!empty($headline)) {
                    $text = str_replace($headline, '', $text);
                }
                return trim($text);
            }
        }
        return '';
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

    public function getDataHeadMetaPageTitle($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta name="title" content="' => '"',
            '<meta name="title" content=\'' => "'",
            '<meta itemprop="title" content="' => '"',
            '<meta itemprop="title" content=\'' => "'",
            '<meta property="og:title" content="' => '"',
            '<meta property="og:title" content=\'' => "'",
            '<meta itemprop="name" property="og:title" content="' => '"',
            '<meta itemprop="name" property="og:title" content=\'' => "'",
            '<meta name="twitter:title" content="' => '"',
            '<meta name="twitter:title" content=\'' => "'",
            '<meta property="og:title" itemprop="title" content="' => '"',
            '<meta property="og:title" itemprop="title" content=\'' => "'",
            '"headline":"' => '"',
            '"headline": "' => '"',
            '<meta id="title" name="title" content="' => '"',
            '<meta id="Title" name="title" content="' => '"',
            '<meta name="og:title" content="' => '"',
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
            '<meta name="description" content=\'' => "'",
            '<meta itemprop="description" content="' => '"',
            '<meta itemprop="description" content=\'' => "'",
            '<meta property="og:description" content="' => '"',
            '<meta property="og:description" content=\'' => "'",
            '<meta name="twitter:description" content="' => '"',
            '<meta name="twitter:description" content=\'' => "'",
            '"description":"' => '"',
            '"description": "' => '"',
            '"description ": "' => '"',
            '<meta id="description" name="description" content="' => '"',
            '<meta id="metaDes" name="description" content="' => '"',
            '<meta id="metades" name="description" content="' => '"',
            '<meta id="metaDescription" name="description" content="' => '"',
            '<meta id="MetaDescription" name="DESCRIPTION" content="' => '"',
            '<meta name="og:description" content="' => '"',
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
            '<meta name="keywords" content=\'' => "'",
            '<meta name="news_keywords" content="' => '"',
            '<meta name="news_keywords" content=\'' => "'",
            '<meta itemprop="keywords" content="' => '"',
            '<meta itemprop="keywords" content=\'' => "'",
            '<meta itemprop="keywords" name="keywords" content=\'' => "'",
            '<meta itemprop="keywords" name="keywords" content="' => '"',
            '<meta property="og:description" content="' => '"',
            '<meta property="og:description" content=\'' => "'",
            '<meta name="twitter:description" content="' => '"',
            '<meta name="twitter:description" content=\'' => "'",
            '<meta id="metakeywords" name="keywords" content="' => '"',
            '<meta id="MetaKeyword" name="keywords" content="' => '"',
            '<meta id="keywords" name="keywords" content="' => '"',
            '<meta id="MetaKeywords" name="keywords" content="' => '"',
            '<meta id="MetaKeywords" name="KEYWORDS" content="' => '"',
            '<meta name="og:keywords" content="' => '"',
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
            '<meta property="dable:image" content="' => '"',
            '<link rel="image_src" href="' => '"',
            '<meta name="og:image" content="' => '"',
            '<meta name="image_sr" content="' => '"',
            '<meta name="image" content="' => '"',
            '<meta name="thumbnail" content="' => '"',
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
                    array('::'),
                    array(':'),
                    $time
                );
                $time = trim($time);
                $ex = explode('.0', $time);
                if (isset($ex[1])) {
                    $time = trim($ex[0]);
                }
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
            '"uploadDate": "' => '"',
            '"uploadDate":"' => '"',
            '<meta property="article:published_time" itemprop="datePublished" content="' => '"',
            '<meta property="article:published_time"  itemprop="datePublished" content="' => '"',
            '<meta itemprop="dateCreated" content="' => '"',
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
            '"uploadDate": "' => '"',
            '"uploadDate":"' => '"',
            '<meta itemprop="dateCreated" content="' => '"',
        ];
        return $this->getDataHeadMetaTimeAndReformatFormat($headStr, $checklists);
    }

    public function getDataHeadMetaCreatedTime($headStr): string
    {
        $checklists = [
            // openTag => closeTag
            '<meta itemprop="dateCreated" content="' => '"',
            '<meta property="article:created_time" content="' => '"',
            '"uploadDate": "' => '"',
            '"uploadDate":"' => '"',
        ];
        return $this->getDataHeadMetaTimeAndReformatFormat($headStr, $checklists);
    }

    public function crawlerHandleDetailsTextContent(Crawler $crawler, $listDefaultContentSelector = array(), $contentSelector = array())
    {
        if (!empty($contentSelector)) {
            // Nếu truyền vào Content Selector
            $inputNewsContentSelector = $contentSelector;
            if (is_array($inputNewsContentSelector)) {
                $requestContent = $this->crawlerHandleRequestDetailsTextContent($crawler, $inputNewsContentSelector);
                if (!empty($requestContent) && isset($requestContent['content'])) {
                    return $requestContent;
                }
                return [
                    'selector' => 'div.hungng-not-found-element-content',
                    'content' => '',
                    'contentImageFirstUrl' => '',
                    'contentLink' => '',
                    'contentImages' => '',
                    'contentFigureMedia' => '',
                    'contentFigureExpNoEdit' => '',
                    'contentFigureExpNoEditFiImg' => '',
                    'contentFigureExpNoEditFiImgSrc' => '',
                    'contentFigureVCSortableInPreviewModeNoCaption' => '',
                    'contentFigureVCSortableInPreviewMode' => '',
                    'contentDivVCSortableInPreviewModeNoCaption' => '',
                    'contentDivVCSortableInPreviewMode' => '',
                ];
            }
            return [
                'selector' => $inputNewsContentSelector,
                'content' => $this->crawlerFilterGetHtml($crawler, $inputNewsContentSelector),
                'contentImageFirstUrl' => $this->getFirstImageSrcLinkInDataContent($crawler, $inputNewsContentSelector),
                'contentLink' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' a'),
                'contentImages' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' img'),
                'contentFigureMedia' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.media'),
                'contentFigureExpNoEdit' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.expNoEdit'),
                'contentFigureExpNoEditFiImg' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.expNoEdit div.fi-img'),
                'contentFigureExpNoEditFiImgSrc' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.expNoEdit div.fi-img img'),
                'contentFigureVCSortableInPreviewModeNoCaption' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.VCSortableInPreviewMode.noCaption'),
                'contentFigureVCSortableInPreviewMode' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.VCSortableInPreviewMode'),
                'contentDivVCSortableInPreviewModeNoCaption' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' div.VCSortableInPreviewMode.noCaption'),
                'contentDivVCSortableInPreviewMode' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' div.VCSortableInPreviewMode'),
            ];
        }

        // Lấy Content Selector mặc định
        $requestContent = $this->crawlerHandleRequestDetailsTextContent($crawler, $listDefaultContentSelector);
        if (!empty($requestContent) && isset($requestContent['content'])) {
            return $requestContent;
        }
        return [
            'selector' => 'div.hungng-not-found-element-content',
            'content' => '',
            'contentImageFirstUrl' => '',
            'contentLink' => '',
            'contentImages' => '',
            'contentFigureMedia' => '',
            'contentFigureExpNoEdit' => '',
            'contentFigureExpNoEditFiImg' => '',
            'contentFigureExpNoEditFiImgSrc' => '',
            'contentFigureVCSortableInPreviewModeNoCaption' => '',
            'contentFigureVCSortableInPreviewMode' => '',
            'contentDivVCSortableInPreviewModeNoCaption' => '',
            'contentDivVCSortableInPreviewMode' => '',
        ];
    }

    public function crawlerHandleRequestDetailsTextContent(Crawler $crawler, $newsContentSelectorList = array())
    {
        if (empty($newsContentSelectorList)) {
            return '';
        }
        foreach ($newsContentSelectorList as $selector) {
            $content = $this->crawlerFilterGetHtml($crawler, $selector);
            if (!empty($content)) {
                return [
                    'selector' => $selector,
                    'content' => $content,
                    'contentImageFirstUrl' => $this->getFirstImageSrcLinkInDataContent($crawler, $selector),
                    'contentLink' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' a'),
                    'contentImages' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' img'),
                    'contentFigureMedia' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.media'),
                    'contentFigureExpNoEdit' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.expNoEdit'),
                    'contentFigureExpNoEditFiImg' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.expNoEdit div.fi-img'),
                    'contentFigureExpNoEditFiImgSrc' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.expNoEdit div.fi-img img'),
                    'contentFigureVCSortableInPreviewModeNoCaption' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.VCSortableInPreviewMode.noCaption'),
                    'contentFigureVCSortableInPreviewMode' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.VCSortableInPreviewMode'),
                    'contentDivVCSortableInPreviewModeNoCaption' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' div.VCSortableInPreviewMode.noCaption'),
                    'contentDivVCSortableInPreviewMode' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' div.VCSortableInPreviewMode'),
                ];
            }
        }
        return '';
    }

    public function crawlerHandleRequestSapoTextContent(Crawler $crawler, $sapoSelectorList = array()): string
    {
        if (empty($sapoSelectorList)) {
            return '';
        }
        foreach ($sapoSelectorList as $selector) {
            $sapo = $this->getDataContentSapoText($crawler, $selector);
            if (!empty($sapo)) {
                return trim($sapo);
            }
        }
        return '';
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
