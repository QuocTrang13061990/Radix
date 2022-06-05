<?php 
function getLinkPrefixUrl($module=''){
    $prefixArr = [
        'services' => 'dich-vu',
        'pages' => 'thong-tin-trang',
        'portfolios' => 'du-an',
        'blog_categories' => 'danh-muc-blog',
        'blogs' => 'bai-viet'
    ];
    if(!empty($prefixArr[$module])){
        return $prefixArr[$module];
    }
    return false;
}
?>