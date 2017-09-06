<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/6
 * Time: 9:29
 */

namespace app\logic;

use app\model\CmsModelModel;
use app\model\CmsPostModel;

class Post extends BaseLogic
{
    public function getPostInfoById($id)
    {
        $cmsPostModel = new CmsPostModel();
        $cmsModelModel = new CmsModelModel();
        $cms_post_result = $cmsPostModel->getRecordInfoById($id);
        if (empty($cms_post_result)) {
            die('文章不存在');
        }
        #获取模型定义
        $model_defined = $this->getModelDefined($cms_post_result['model']);
        #获取扩展数据
        $tables = array_unique(array_column($model_defined, 'belong_to_table'));
        $fields = array_unique(array_column($model_defined, 'value'));
        $tables = array_flip($tables);
        if (isset($tables['cms_post'])) {
            unset ($tables['cms_post']);
        }
        $tables = array_keys($tables);
        $extend_data = [];
        foreach ($tables as $value) {
            $result = $cmsModelModel->orm()->for_table($value)->select_expr('field,value')->where(['post_id' => $cms_post_result['post_id']])->find_array();
            if ($result) {
                $extend_data = array_merge($extend_data, $result);
            }
        }
        if (!empty($extend_data)) {
            foreach ($extend_data as $value) {
                if (in_array($value['field'], $fields)) {
                    $cms_post_result[$value['field']] = $value['value'];
                }
            }
        }
        return $cms_post_result;
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
    public function addPost($request_data)
    {
        #获取模型定义
        $model_defined = $this->getModelDefined($request_data['model_id']);
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

} 