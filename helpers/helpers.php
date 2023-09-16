<?php
if (!function_exists('_crawler_convert_youtube_embed_from_id_')) {
    function _crawler_convert_youtube_embed_from_id_($youtubeID = ''): string
    {
        if (empty($youtubeID)) {
            return '';
        }

        $youtubeID = str_replace(
            array(
                'https://youtu.be/',
                'https://www.youtube.com/watch?v=',
                'https://www.youtube.com/embed/'
            ),
            '',
            $youtubeID
        );
        $newHtml = '<div class="news-posts-content-youtube-video" style="padding-top: 10px;text-align: center;padding-bottom: 10px;">';
        $newHtml .= '<iframe width="100%" height="445" src="https://www.youtube.com/embed/' . trim($youtubeID) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        $newHtml .= '</div>';

        return trim($newHtml);
    }
}
if (!function_exists('_crawler_convert_image_src_from_url_')) {
    function _crawler_convert_image_src_from_url_($imgSrc = '', $title = '', $alt = ''): string
    {
        $newImgSrc = '<img class="news-posts-content-image" width="100%" src="' . trim($imgSrc) . '" title="' . trim($title) . '" alt="' . trim($alt) . '" />';
        return trim($newImgSrc);
    }
}
if (!function_exists('_crawler_convert_figure_only_fi_img_')) {
    function _crawler_convert_figure_only_fi_img_($html = '', $figureCaption = ''): string
    {
        $newHtml = '<figure class="figure-media-image-photo figure-bear-news-cms-content"><div class="fi-img bear-news-cms-content">' . trim($html) . '</div>' . trim($figureCaption) . '</figure>';
        return trim($newHtml);
    }
}
if (!function_exists('_crawler_convert_div_only_fi_img_')) {
    function _crawler_convert_div_only_fi_img_($html = '', $figureCaption = ''): string
    {
        $newHtml = '<div class="figure-media-image-photo figure-bear-news-cms-content"><div class="fi-img bear-news-cms-content">' . trim($html) . '</div>' . trim($figureCaption) . '</div>';
        return trim($newHtml);
    }
}
if (!function_exists('_crawler_convert_figure_figcaption_')) {
    function _crawler_convert_figure_figcaption_($html = ''): string
    {
        $newHtml = '<figcaption class="figure-bear-news-cms-photo-caption"><p data-placeholder="' . escapeHtml(trim($html)) . '">' . trim($html) . '</p></figcaption>';
        return trim($newHtml);
    }
}
if (!function_exists('_crawler_convert_div_figcaption_')) {
    function _crawler_convert_div_figcaption_($html = ''): string
    {
        $newHtml = '<div class="figure-bear-news-cms-photo-caption"><p data-placeholder="' . escapeHtml(trim($html)) . '">' . trim($html) . '</p></div>';
        return trim($newHtml);
    }
}
