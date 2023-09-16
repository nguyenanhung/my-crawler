<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 18:08
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

trait CrawlerHandleGetHeadMetaAliasTrait
{
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
}
