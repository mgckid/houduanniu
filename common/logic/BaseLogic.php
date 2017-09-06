<?php

/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/7/17
 * Time: 10:40
 */
namespace app\logic;

use app\model\CmsFieldModel;
use app\model\CmsModelModel;
use app\model\DictionaryModel;
use houduanniu\base\Hook;
use houduanniu\base\Model;
use houduanniu\web\Controller;
use app\model\CmsCategoryModel;

class BaseLogic extends Controller
{
    /**
     * 获取字典名称
     * @access public
     * @author furong
     * @param $field
     * @param $dictionary_name
     * @param string $field_name
     * @return array
     * @since 2017年7月17日 14:10:59
     * @abstract
     */
    public function getDictionaryName($field, $dictionary_name, $field_name = '')
    {
        $dictionaryModel = new DictionaryModel();
        $all_dictionary = treeStructForLayer($dictionaryModel->getAllDictionary());
        $name = [];
        foreach ($all_dictionary as $value) {
            if ($value['value'] == $dictionary_name) {
                foreach ($value['sub'] as $val) {
                    if (empty($field_name)) {
                        if (in_array($val['value'], $field)) {
                            $name[$val['value']] = $val['name'];
                        }
                    } else {
                        foreach ($val['sub'] as $v) {
                            if (in_array($v['value'], $field)) {
                                $name[$v['value']] = $v['name'];
                            }
                        }
                    }
                }
            }
        }
        return $name;
    }

    public function getTableDefinded($table_name, $field_field = 'f.name,f.value,f.form_type,f.validate_rule,f.data_type,f.list_display')
    {
        $model = new DictionaryModel();
        $field_result = $model->orm()->table_alias('f')
            ->left_join('dictionarys', ['f.pid', '=', 'm.id'], 'm')
            ->select_expr($field_field . ',f.id')
            ->where('m.value', $table_name)
            ->where('f.deleted', 0)
            ->find_array();
        if ($field_result) {
            foreach ($field_result as $key => $value) {
                $enum_result = $model->orm()->table_alias('a')
                    ->left_join('dictionarys', ['a.pid', '=', 'f.id'], 'f')
                    ->select_expr('a.name,a.value')
                    ->where('a.pid', $value['id'])
                    ->where('a.deleted', 0)
                    ->find_array();
                $enum = [];
                if (!empty($enum_result)) {
                    foreach ($enum_result as $e) {
                        $enum[$e['value']] = $e;
                    }
                }
                $value['enum'] = $enum;
                $field_result[$key] = $value;
            }
        }
        return $field_result;
    }

    public function getModelDefined($model_name)
    {
        $model_result = $this->getModelInfo($model_name);
        $model_name = $model_result['value'];
        $model = new CmsFieldModel();
        $field_result = $model->orm()->table_alias('f')
            ->left_join('cms_model', ['f.model_id', '=', 'm.id'], 'm')
            ->select_expr('f.*')
            ->where('m.value', $model_name)
            ->where('f.deleted', 0)
            ->find_array();
        if ($field_result) {
            foreach ($field_result as $key => $value) {
                $enum_result = $model->orm()->for_table('cms_attribute')
                    ->use_id_column('id')
                    ->select_expr('name,value')
                    ->where('field_id', $value['id'])
                    ->where('deleted', 0)
                    ->find_array();
                $enum = [];
                if (!empty($enum_result)) {
                    foreach ($enum_result as $e) {
                        $enum[$e['value']] = $e;
                    }
                }
                $value['enum'] = $enum;
                $field_result[$key] = $value;
            }
        }
        return $field_result;
    }

    public function getFormInit($table_name, $mode = 'table', $field_field = 'f.name,f.value,f.form_type,f.validate_rule,f.data_type')
    {
        if ($mode == 'table') {
            $field_definded = $this->getTableDefinded($table_name, $field_field);
        } elseif ($mode = 'model') {
            $field_definded = $this->getModelDefined($table_name);
        }
        $form_init = [];
        foreach ($field_definded as $key => $value) {
            if ($value['form_type'] == 'none') {
                continue;
            }
            $form_init[$value['value']] = [
                'field' => $value['value'],
                'title' => $value['name'],
                'type' => $value['form_type'],
                'enum' => $value['enum'],
                'description' => '这是描述',
            ];
        }

        #注册钩子方法
        foreach ($field_definded as $value) {
            $hook = $value['belong_to_table'];
            $function = 'app\\logic\\' . ucfirst($hook) . '::' . $value['value'];
            Hook::getInstance()->add_action($hook, $function);
        }
        return $form_init;
    }

    public function getRequestData($name, $mode = 'table')
    {
        if ($mode == 'table') {
            $field_definded = $this->getTableDefinded($name);
        } elseif ($mode = 'model') {
            $field_definded = $this->getModelDefined($name);
        }
        #注册钩子方法
        $hook = $name;
        foreach ($field_definded as $value) {
            $function = 'app\\logic\\' . ucfirst($hook) . '::' . $value['value'];
            Hook::getInstance()->add_action($hook, $function);
        }
        #获取验证规则
        $validate_rule = [];
        $name_map = [];
        foreach ($field_definded as $value) {
            if (!empty($value['validate_rule'])) {
                $validate_rule[$value['value']] = $value['validate_rule'];
                $name_map[$value['value']] = $value['name'];
            }
        }
        #验证输入数据
        if (!empty($validate_rule)) {
            $model = new Model();
            $validate = $model->validate()->make($_REQUEST, $validate_rule, [], $name_map);
            if (false === $validate->passes()) {
                if (IS_POST || IS_AJAX) {
                    $this->ajaxFail($validate->messages()->first());
                } else {
                    die($validate->messages()->first());
                }
            }
        }
        #执行钩子方法
        Hook::getInstance()->do_action($hook);
        #获取提交表单数据
        $request_data = [];
        $input_fields = array_column($field_definded, 'value');
        foreach ($_REQUEST as $key => $value) {
            if (in_array($key, $input_fields)) {
                $request_data[$key] = $value;
            }
        }

        #格式化数据
        foreach ($field_definded as $value) {
            if (isset($request_data[$value['value']])) {
                switch ($value['data_type']) {
                    case 'int':
                        $request_data[$value['value']] = intval($request_data[$value['value']]);
                        break;
                    case 'varchar':
                        $request_data[$value['value']] = trim($request_data[$value['value']]);
                        break;
                    case 'text':
                        $request_data[$value['value']] = htmlspecialchars($request_data[$value['value']]);
                        break;
                    case 'password':
                        $request_data[$value['value']] = sha1(trim($request_data[$value['value']]));
                        break;
                }
            }
        }
        return $request_data;
    }

    public function getListInit($table_name)
    {
        $table_defined = $this->getTableDefinded($table_name);
        $list_init = [];
        foreach ($table_defined as $value) {
            #只显示列表运许显示显示的字段
            if ($value['list_display'] == 0) {
                continue;
            }
            $field = $value['value'];
            $enum = [];
            if ($value['enum']) {
                foreach ($value['enum'] as $v) {
                    $enum[$v['value']] = $v['name'];
                }
            }
            $list_init[$field] = [
                'name' => $value['name'],
                'enum' => $enum,
            ];
        }
        return $list_init;
    }

    public function getModelInfo($model_name)
    {
        if (is_numeric($model_name)) {
            $where = ['id' => $model_name];
        } else {
            $where = ['value' => $model_name];
        }
        $cmsModel = new CmsModelModel();
        $result = $cmsModel->getRecordInfo($cmsModel->orm()->where($where));
        return $result;
    }

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

    public function getCategoryData()
    {
        $cmsCategoryModel = new CmsCategoryModel();
        $all_category_result = $cmsCategoryModel->getAllRecord();
        $list = treeStructForLevel($all_category_result);
        $data = [];
        foreach ($list as $value) {
            $data[] = [
                'id' => $value['id'],
                'category_name' => $value['placeHolder'] . $value['category_name'],
            ];
        }
        return $data;
    }

    public function getModelData()
    {
        #模型分类
        $cmsModelModel = new CmsModelModel();
        $model_result = $cmsModelModel->getAllCmsModel('id,name');
        $data = [];
        foreach ($model_result as $key => $value) {
            $data[] = [
                'id' => $value['id'],
                'name' => $value['name'],
            ];
        }
        return $data;
    }



}