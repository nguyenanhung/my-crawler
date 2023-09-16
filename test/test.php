<?php
require_once __DIR__ . '/../vendor/autoload.php';

//$time = '2023-09-06T10:00:00+0000';
//
//echo date('Y-m-d H:i:s', strtotime($time)).PHP_EOL;

$content = '<div class="PhotoCMS_Caption">
    <p data-placeholder="[nhập chú thích]">Các đại biểu mặc niệm tưởng nhớ các nạn nhân tử vong trong vụ cháy chung cư mini tại quận Thanh Xuân.</p>
  </div>';

echo "<pre>";
print_r($content);
echo "</pre>";

class ABC{
    use \nguyenanhung\Libraries\Crawler\CrawlerFilterTrait;
}

$abc = new ABC();
$check = $abc->getContentValueWithExplode($content, ' <div class="PhotoCMS_Caption">', '</div>' );
$text = strip_tags($check);
echo "<pre>";
print_r($text);
echo "</pre>";

$final = '<figcaption class="figure-bear-news-cms-photo-caption">
    <p data-placeholder="'.trim($text).'">'.trim($text).'</p>
  </figcaption>';

echo "<pre>";
print_r($final);
echo "</pre>";