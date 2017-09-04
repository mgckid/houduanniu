<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/8/2
 * Time: 16:57
 */

namespace app\logic;


use app\controller\UserBaseController;
use Overtrue\Pinyin\Pinyin;
use app\library\BosonNLP;

class ArticleLogic extends UserBaseController
{
    public function getFenci($text)
    {
        $text =strip_tags( htmlspecialchars_decode($text));
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