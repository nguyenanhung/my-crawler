<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 18:03
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

trait CrawlerHandleVideoInContentTrait
{
    // Parse + Handle Content Video Youtube in Body Content
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

    public function reformatContentYoutubeVideo($contentText, $listLinks)
    {
        return $this->reformatDataContentVideoYoutubeLinkInContent($contentText, $listLinks);
    }
}
