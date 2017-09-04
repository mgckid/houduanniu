<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/4
 * Time: 17:09
 */

namespace app\logic;


use app\controller\UserBaseController;
use Overtrue\Pinyin\Pinyin;
use app\library\BosonNLP;

class Article extends UserBaseController
{

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

    protected function getImageFromContent($content)
    {
        //匹配IMG标签
        $content = htmlspecialchars_decode($content);
        $img_pattern = "/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i";
        preg_match_all($img_pattern, $content, $img_out);
        return $img_out[2];
    }

    protected function getImageUrlFromUrl($url)
    {
        $_url = explode('/', $url);
        return end($_url);
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

    public function getFenci($text)
    {
        $text = strip_tags(htmlspecialchars_decode($text));
        if (empty($text)) {
            $this->setMessage('源数据不能为空');
            return false;
        }
        $token = $this->siteInfo['cfg_BosonNLP_TOKEN'];
        if (empty($token)) {
            $this->setMessage('请先设置玻森分词api Token');
            return false;
        }
        $fenci = new BosonNLP($token);
        //提取关键字
        $pram = [
            'top_k' => 10,
        ];
        $result = $fenci->analysis($fenci::ACTION_KEYWORDS, $text, $pram);
        if (!$result) {
            $this->setMessage('分词失败');
            return false;
        }
        $keyword = [];
        foreach ($result[0] as $key => $val) {
            $keyword[] = $val[1];
        }
        //提取描述
        $data = [
            'content' => $text,
            'not_exceed' => 0,
            'percentage' => 0.1,
        ];
        $result = $fenci->analysis($fenci::ACTION_SUMMARY, $data);
        $summary = !empty($result) ? str_replace(PHP_EOL, "", $result) : '';
        $return = [
            'keyword' => join(',', $keyword),
            'tag' => join(',', array_slice($keyword, 0, 5)),
            'description' => $summary,
        ];
        return $return;
    }
} 