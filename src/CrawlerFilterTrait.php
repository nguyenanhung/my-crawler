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

use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleBaseFilterTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleBaseDataPageTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleFilterPageMetaTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleGetHeadMetaAliasTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleImageSrcContentTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleReformatAndRemovedTrait;
use nguyenanhung\Libraries\Crawler\Traits\CrawlerHandleVideoInContentTrait;

trait CrawlerFilterTrait
{
    use CrawlerHandleBaseFilterTrait, CrawlerHandleBaseDataPageTrait,
        CrawlerHandleReformatAndRemovedTrait,
        CrawlerHandleImageSrcContentTrait, CrawlerHandleVideoInContentTrait,
        CrawlerHandleGetHeadMetaAliasTrait, CrawlerHandleFilterPageMetaTrait;

    ////////////////////// ALIAS METHOD //////////////////////

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
}
