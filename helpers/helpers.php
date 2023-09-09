<?php
if (!function_exists('_crawler_convert_youtube_embed_from_id_')) {
    function _crawler_convert_youtube_embed_from_id_($youtubeID = ''): string
    {
        if (empty($youtubeID)) {
            return '';
        }

        $youtubeID = str_replace('https://youtu.be/', '', $youtubeID);
        $youtubeID = str_replace('https://www.youtube.com/watch?v=', '', $youtubeID);
        $youtubeID = str_replace('https://www.youtube.com/embed/', '', $youtubeID);
        $newHtml = '<div class="news-posts-content-youtube-video" style="text-align: center;">';
        $newHtml .= '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . trim($youtubeID) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
        $newHtml .= '</div>';

        return trim($newHtml);
    }
}
