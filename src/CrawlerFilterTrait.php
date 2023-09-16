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
                    'contentLink' => '',
                    'contentImages' => '',
                    'contentFigureMedia' => '',
                    'contentFigureExpNoEditFiImg' => '',
                    'contentFigureExpNoEditFiImgSrc' => '',
                    'contentImageFirstUrl' => '',
                ];
            }
            return [
                'selector' => $inputNewsContentSelector,
                'content' => $this->crawlerFilterGetHtml($crawler, $inputNewsContentSelector),
                'contentLink' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' a'),
                'contentImages' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' img'),
                'contentFigureMedia' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.media'),
                'contentFigureExpNoEditFiImg' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.expNoEdit div.fi-img'),
                'contentFigureExpNoEditFiImgSrc' => $this->crawlerFilterGetRawOuterHtml($crawler, $inputNewsContentSelector . ' figure.expNoEdit div.fi-img img'),
                'contentImageFirstUrl' => $this->getFirstImageSrcLinkInDataContent($crawler, $inputNewsContentSelector),
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
            'contentLink' => '',
            'contentImages' => '',
            'contentFigureMedia' => '',
            'contentFigureExpNoEditFiImg' => '',
            'contentFigureExpNoEditFiImgSrc' => '',
            'contentImageFirstUrl' => '',
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
                    'contentLink' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' a'),
                    'contentImages' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' img'),
                    'contentFigureMedia' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.media'),
                    'contentFigureExpNoEditFiImg' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.expNoEdit div.fi-img'),
                    'contentFigureExpNoEditFiImgSrc' => $this->crawlerFilterGetRawOuterHtml($crawler, $selector . ' figure.expNoEdit div.fi-img img'),
                    'contentImageFirstUrl' => $this->getFirstImageSrcLinkInDataContent($crawler, $selector),
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

    public function reformatDataContentImageLinkInContent($content, $listImages)
    {
        if (empty($content) || empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $imgSrc = $this->parseMatchesImageSrcFromChecklists(
                $oldItem,
                array(
                    'class="lazyload" data-src="' => '"',
                    'class=\'lazyload\' data-src=" src=\'' => "'",
                    'data-src="' => '"',
                    'data-src=\'' => "'",
                    'data-original="' => '"',
                    'data-original=\'' => "'",
                    'data-photo-original-src="' => '"',
                    'data-photo-original-src=\'' => "'",
                    'data-background-image="' => '"',
                    'data-background-image=\'' => "'",
                    'src="' => '"',
                    'src=\'' => "'",
                )
            );
            $newItem = '<img class="news-posts-content-image" width="100%" src="' . trim($imgSrc) . '" title="' . trim($title) . '" alt="' . trim($alt) . '" />';
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    public function reformatDataContentVideoYoutubeLinkInContent($content, $videoList, $matchVideoOpenTag = '<div data-oembed-url="', $matchVideoCloseTag = '"')
    {
        if (empty($content) || empty($videoList)) {
            return $content;
        }

        foreach ($videoList as $oldItemHtml) {
            $oldItemHtml = trim($oldItemHtml);
            $youtubeID = $this->parseGetContentValueWithExplode($oldItemHtml, $matchVideoOpenTag, $matchVideoCloseTag);
            $newHtml = _crawler_convert_youtube_embed_from_id_($youtubeID);
            $content = str_replace($oldItemHtml, $newHtml, $content);
        }
        return $content;
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

    ////////////////////// ALIAS METHOD //////////////////////
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

    public function crawlerHtmlEmbedContentRemoved($contentText, Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerReformatContentRemovedWithFilter($crawler, $contentText, $filter);
    }

    public function crawlerHtmlEmbedContentOuterHtmlRemoved($contentText, Crawler $crawler, $filter = ''): string
    {
        return $this->crawlerReformatContentRemovedWithFilterOuterHtml($crawler, $contentText, $filter);
    }

    public function parseHtmlHeader(Crawler $crawler): array
    {
        return $this->crawlerFilterRawHtml($crawler, 'head');
    }

    public function parseHtmlBody(Crawler $crawler): array
    {
        return $this->crawlerFilterRawHtml($crawler, 'body');
    }

    public function filterScriptJsonStructureDataFromHtml($html): array
    {
        return $this->getDataFilterScriptJsonStructureDataFromHtml($html);
    }

    public function getContentValueWithRegexMatch(string $txt = '', string $openTag = '', string $closeTag = '')
    {
        return $this->getContentDataValueWithRegexMatch($txt, $openTag, $closeTag);
    }

    public function getContentValueWithRegexMatchAll(string $txt = '', string $openTag = '', string $closeTag = '')
    {

        return $this->getContentDataValueWithRegexMatchAll($txt, $openTag, $closeTag);
    }

    public function getContentValueFromHtmlTag(string $txt = '', string $openTag = '', string $closeTag = ''): string
    {
        return $this->getContentDataValueFromHtmlTag($txt, $openTag, $closeTag);
    }

    public function tagClassNameRemoved($html, $tag, $classname = '')
    {
        return $this->filterTagClassNameRemoved($html, $tag, $classname);
    }

    public function scriptTagRemoved($html)
    {
        return $this->filterScriptTagRemoved($html);
    }

    public function cssStyleTagRemoved($html)
    {
        return $this->filterCssStyleTagRemoved($html);
    }

    public function divTagClassNameRemoved($html, $classname = '')
    {
        return $this->tagClassNameRemoved($html, 'div', $classname);
    }

    public function insTagClassNameRemoved($html, $classname = '')
    {
        return $this->tagClassNameRemoved($html, 'ins', $classname);
    }

    public function htmlEmbedContentRemoved($contentText = '', $cmsEmbedContent = array()): string
    {
        foreach ($cmsEmbedContent as $embedContent) {
            $contentText = str_replace($embedContent, "", $contentText);
        }
        return trim($contentText);
    }

    public function getContentValueWithDoubleExplodeAndStripTags($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag): string
    {
        return $this->parseGetContentValueWithDoubleExplodeAndStripTags($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag);
    }

    public function getContentValueWithExplodeAndStripTags($str, $openTag, $closeTag): string
    {
        return $this->parseGetContentValueWithExplodeAndStripTags($str, $openTag, $closeTag);
    }

    public function getContentValueWithDoubleExplode($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag): string
    {
        return $this->parseGetContentValueWithDoubleExplode($str, $openTag, $closeTag, $nextOpenTag, $nextCloseTag);
    }

    public function getContentValueWithExplode($str, $openTag, $closeTag): string
    {
        return $this->parseGetContentValueWithExplode($str, $openTag, $closeTag);
    }

    public function simpleGetHeadMetaValueStripTags($str, $openTag, $closeTag): string
    {
        return $this->getContentValueWithExplodeAndStripTags($str, $openTag, $closeTag);
    }

    public function simpleGetHeadMetaValue($str, $openTag, $closeTag): string
    {
        return $this->getContentValueWithExplode($str, $openTag, $closeTag);
    }

    public function getHeadPageTitle($str, $headline = ''): string
    {
        return $this->crawlerGetDataHeadPageTitle($str, $headline);
    }

    public function reformatContentWithFilterHtmlTag($contentText = '', $filterHtmlTag = '')
    {
        return $this->reformatDataContentWithFilterHtmlTag($contentText, $filterHtmlTag);
    }

    public function reformatSkipFirstTagContentWithFilterHtmlTag($contentText = '', $filterHtmlTag = '')
    {
        return $this->reformatSkipFirstDataContentWithFilterPositionHtmlTag($contentText, $filterHtmlTag);
    }

    public function reformatAddSiteUrlIntoContent($contentText = '', $siteUrl = '', $match = '')
    {
        return $this->reformatDataAndAddSiteUrlIntoContent($contentText, $siteUrl, $match);
    }

    public function reformatContentRemovedLinkHref($contentText, $listLinks)
    {
        return $this->reformatDataContentAndRemovedLinkHrefWithRegex($contentText, $listLinks);
    }

    public function reformatContentLinkImages($contentText, $listLinks)
    {
        return $this->reformatDataContentImageLinkInContent($contentText, $listLinks);
    }

    public function reformatContentYoutubeVideo($contentText, $listLinks)
    {
        return $this->reformatDataContentVideoYoutubeLinkInContent($contentText, $listLinks);
    }

    public function getFirstImageLinkInContent($crawler, $filter, $openTag = 'src="', $closeTag = '"'): string
    {
        return $this->getFirstImageSrcLinkInDataContent($crawler, $filter, $openTag, $closeTag);
    }

    public function getHeadMetaImageSrc($str, $start = '<meta property="og:image" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="og:image" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaImageSrcThumbnailUrl($str, $start = '<meta property="og:image" itemprop="thumbnailUrl" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="og:image" itemprop="thumbnailUrl" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaPageTitle($str, $start = '<meta property="og:title" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="og:title" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaDescription($str, $start = '<meta property="og:description" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="og:description" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaKeywords($str, $start = '<meta name="keywords" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta name="keywords" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaNewsKeywords($str, $start = '<meta name="news_keywords" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta name="news_keywords" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaItemPropKeywords($str, $start = '<meta itemprop="keywords" name="keywords" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta itemprop="keywords" name="keywords" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleTag($str, $start = '<meta property="article:tag" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="article:tag" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleTitle($str, $start = '<meta property="og:title" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="og:title" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticlePublishedTime($str, $start = '<meta property="article:published_time" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="article:published_time" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleModifiedTime($str, $start = '<meta property="article:modified_time" content="', $end = '"'): string
    {
        $openTag = empty($start) ? '<meta property="article:modified_time" content="' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleSchemaHeadline($str, $start = '"headline":"', $end = '"'): string
    {
        $openTag = empty($start) ? '"headline":"' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleSchemaDescription($str, $start = '"description":"', $end = '"'): string
    {
        $openTag = empty($start) ? '"description":"' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleSchemaPublishedTime($str, $start = '"datePublished":"', $end = '"'): string
    {
        $openTag = empty($start) ? '"datePublished":"' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function getHeadMetaArticleSchemaModifiedTime($str, $start = '"dateModified":"', $end = '"'): string
    {
        $openTag = empty($start) ? '"dateModified":"' : $start;
        $closeTag = empty($end) ? '"' : $end;
        return $this->simpleGetHeadMetaValue($str, $openTag, $closeTag);
    }

    public function itemScopePublisherRemoved($contentText = ''): string
    {
        return $this->reformatDataContentAndItemScopePublisherRemoved($contentText);
    }
}
