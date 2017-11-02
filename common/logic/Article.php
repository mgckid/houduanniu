<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/4
 * Time: 17:09
 */

namespace app\logic;


use Overtrue\Pinyin\Pinyin;

class Article extends BaseLogic
{
    protected $model_name = 'article';

    public function main_image()
    {
        $field = __FUNCTION__;
        $content = $_REQUEST['content'];
        #缩略图为空 取文章图片为缩略图
        if (!isset($_REQUEST[$field]) || empty($_REQUEST[$field])) {
            $img = $this->getImageFromContent($content);
            if ($img) {
                $_REQUEST[$field] = $this->getImageUrlFromUrl(current($img));
            }
        }
        return true;
    }




    public function title_alias()
    {
        $field = __FUNCTION__;
        $title = $_REQUEST['title'];
        $pinyin = new Pinyin();
        if (!isset($_REQUEST[$field]) || empty($_REQUEST[$field])) {
            $_REQUEST[$field] = htmlspecialchars(join('-', $pinyin->convert($title)));
        }
        return true;
    }

} 