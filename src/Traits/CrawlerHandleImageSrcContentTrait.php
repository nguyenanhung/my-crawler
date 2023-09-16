<?php
/**
 * Project my-crawler
 * Created by PhpStorm
 * User: 713uk13m <dev@nguyenanhung.com>
 * Copyright: 713uk13m <dev@nguyenanhung.com>
 * Date: 16/09/2023
 * Time: 17:45
 */

namespace nguyenanhung\Libraries\Crawler\Traits;

trait CrawlerHandleImageSrcContentTrait
{
    // Parse + Handle Content Image in Body Content
    public function handleDefaultListMatchesImageSrcFromChecklists(): array
    {
        return array(
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
        );
    }

    public function handleRequestPrepareDataContentFigureFigcaptionInContent($oldItem = ''): string
    {
        $caption = $this->getContentValueWithExplode($oldItem, '<figcaption', '</figcaption');
        $caption = '<figcaption ' . $caption;
        $caption = strip_tags($caption);
        return trim($caption);
    }

    public function handlePrepareDataContentFigureFigcaptionInContent($oldItem = ''): string
    {
        $caption = $this->handleRequestPrepareDataContentFigureFigcaptionInContent($oldItem);
        $figureCaption = _crawler_convert_figure_figcaption_($caption);
        return trim($figureCaption);
    }

    public function handleRequestPrepareDataContentDivPhotoCMSCaptionInContent($oldItem = ''): string
    {
        $caption = $this->getContentValueWithExplode($oldItem, ' <div class="PhotoCMS_Caption">', '</div>');
        $caption = strip_tags($caption);
        return trim($caption);
    }

    public function handlePrepareDataContentDivPhotoCMSCaptionInContent($oldItem = ''): string
    {
        $caption = $this->handleRequestPrepareDataContentDivPhotoCMSCaptionInContent($oldItem);
        $figureCaption = _crawler_convert_div_figcaption_($caption);
        return trim($figureCaption);
    }

    // Reformat
    public function reformatDataContentImageLinkInContent($content, $listImages)
    {
        if (empty($content) || empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $imgSrc = $this->parseMatchesImageSrcFromChecklists($oldItem, $this->handleDefaultListMatchesImageSrcFromChecklists());
            $newItem = _crawler_convert_image_src_from_url_($imgSrc, $title, $alt);
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    public function reformatDataContentDivPhotoToFigureImageLinkInContent($content, $listImages)
    {
        if (empty($content) || empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $caption = $this->handlePrepareDataContentFigureFigcaptionInContent($oldItem);
            $captionRequest = $this->handleRequestPrepareDataContentFigureFigcaptionInContent($oldItem);
            if (empty($captionRequest)) {
                $caption = $this->handlePrepareDataContentDivPhotoCMSCaptionInContent($oldItem);
            }
            $imgSrc = $this->parseMatchesImageSrcFromChecklists($oldItem, $this->handleDefaultListMatchesImageSrcFromChecklists());
            $newItem = _crawler_convert_image_src_from_url_($imgSrc, $title, $alt);
            $newItem = _crawler_convert_figure_only_fi_img_($newItem, $caption);
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    public function reformatDataContentFigureDivImageLinkInContent($content, $listImages)
    {
        if (empty($content) || empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $caption = $this->handlePrepareDataContentFigureFigcaptionInContent($oldItem);
            $imgSrc = $this->parseMatchesImageSrcFromChecklists($oldItem, $this->handleDefaultListMatchesImageSrcFromChecklists());
            $newItem = _crawler_convert_image_src_from_url_($imgSrc, $title, $alt);
            $newItem = _crawler_convert_figure_only_fi_img_($newItem, $caption);
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    public function reformatDataContentDivPhotoCMSImageLinkInContent($content, $listImages)
    {
        // Handle này của mấy cha VCCorp
        if (empty($content) || empty($listImages)) {
            return $content;
        }
        foreach ($listImages as $item) {
            $oldItem = trim($item);
            $title = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'title="', '"');
            $alt = $this->parseGetContentValueWithExplodeAndStripTags($oldItem, 'alt="', '"');
            $caption = $this->handlePrepareDataContentDivPhotoCMSCaptionInContent($oldItem);
            $imgSrc = $this->parseMatchesImageSrcFromChecklists($oldItem, $this->handleDefaultListMatchesImageSrcFromChecklists());
            $newItem = _crawler_convert_image_src_from_url_($imgSrc, $title, $alt);
            $newItem = _crawler_convert_div_only_fi_img_($newItem, $caption);
            $content = str_replace($oldItem, $newItem, $content);
        }
        return $content;
    }

    // Alias
    public function reformatContentLinkImages($contentText, $listLinks)
    {
        return $this->reformatDataContentImageLinkInContent($contentText, $listLinks);
    }

    public function reformatContentDivPhotoToFigureLinkImages($contentText, $listLinks)
    {
        return $this->reformatDataContentDivPhotoToFigureImageLinkInContent($contentText, $listLinks);
    }

    public function reformatContentFigureDivFiImgLinkImages($contentText, $listLinks)
    {
        return $this->reformatDataContentFigureDivImageLinkInContent($contentText, $listLinks);
    }

    public function reformatContentDivPhotoCMSLinkImages($contentText, $listLinks)
    {
        return $this->reformatDataContentDivPhotoCMSImageLinkInContent($contentText, $listLinks);
    }
}
