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
        $model_name= $model_result['value'];
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

}