<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/4
 * Time: 17:09
 */

namespace app\logic;


use app\model\CmsPostModel;
use Overtrue\Pinyin\Pinyin;
use app\library\BosonNLP;
use app\model\CmsModelModel;

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

    /**
     * 添加文档
     * @access public
     * @author furong
     * @param $request_data
     * @return bool
     * @since 2017年8月2日 15:48:44
     * @abstract
     */
    public function addRecord($request_data)
    {
        #获取模型定义
        $model_defined = $this->getModelDefined($this->model_name);
        $cms_post_data = [];
        $extend_data = [];
        foreach ($model_defined as $value) {
            switch ($value['belong_to_table']) {
                case 'cms_post':
                    if (isset($request_data[$value['value']])) {
                        $cms_post_data[$value['value']] = $request_data[$value['value']];
                    }
                    break;
                default:
                    if (isset($request_data[$value['value']])) {
                        $extend_data[] = [
                            'table_name' => $value['belong_to_table'],
                            'post_id' => $request_data['post_id'],
                            'field' => $value['value'],
                            'value' => $request_data[$value['value']],
                        ];
                    }
            }
        }
        $cmsPostModel = new CmsPostModel();
        try {
            $cmsPostModel->beginTransaction();
            $cms_post_result = $cmsPostModel->addRecord($cms_post_data);
            if (!$cms_post_result) {
                throw new \Exception('文档主记录添加失败');
            }
            if ($extend_data) {
                foreach ($extend_data as $value) {
                    $result = $cmsPostModel->addCmsPostExtendData($value['table_name'], $value['post_id'], $value['field'], $value['value']);
                    if (!$result) {
                        throw new \Exception('文档扩展记录添加失败');
                    }
                }
            }
            $cmsPostModel->commit();
            $return = true;
        } catch (\Exception $ex) {
            $cmsPostModel->rollBack();
            $this->setMessage($ex->getMessage());
            $return = false;
        }
        return $return;
    }

    public function getRecordInfoById($id)
    {
        $cmsPostModel = new CmsPostModel();
        $cms_post_result = $cmsPostModel->getRecordInfoById($id);
        #获取模型定义
        $model_defined = $this->getModelDefined($this->model_name);
        #获取扩展数据
        foreach ($model_defined as $value) {
            if ($value['belong_to_table'] != $this->tableName) {
                $result = $this->orm()->for_table($value['belong_to_table'])->where('post_id', $cms_post_result['post_id'])->where('field', $value['value'])->find_one();
                if ($result) {
                    $result = $result->as_array();
                    $cms_post_result[$result['field']] = $result['value'];
                }
            }
        }
        return $cms_post_result;
    }

} 