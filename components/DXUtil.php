<?php
namespace app\components;

class DXUtil extends \dix\base\component\DXUtil
{
    public static function processPage($page, $size = 20) {
        $page = intval($page);
        $page = $page < 1 ? 1 : intval($page);
        $offset = ($page - 1) * $size;

        return [$offset, $size];
    }

  
}