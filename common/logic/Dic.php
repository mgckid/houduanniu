<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/7
 * Time: 20:57
 */

namespace app\logic;


use app\model\DicModel;
use app\model\DictionaryAttributeModel;
use houduanniu\base\Hook;

class Dic
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
            if ($value['field_value'] == $dictionary_name) {
                foreach ($value['sub'] as $val) {
                    if (empty($field_name)) {
                        if (in_array($val['field_value'], $field)) {
                            $name[$val['field_value']] = $val['field_name'];
                        }
                    } else {
                        foreach ($val['sub'] as $v) {
                            if (in_array($v['field_value'], $field)) {
                                $name[$v['field_value']] = $v['field_name'];
                            }
                        }
                    }
                }
            }
        }
        return $name;
    }

    public function getTableDefinded($table_name, $field_field = 'f.field_name,f.field_value,f.form_type,f.validate_rule,f.data_type')
    {
        $model = new DicModel();
        $field_result = $model->orm()->table_alias('t')
            ->left_join('dictionary_field', ['t.id', '=', 'f.dictionary_id'], 'f')
            ->select_expr('f.*')
            ->where('t.value', $table_name)
            ->where('f.deleted', 0)
            ->find_array();
        if ($field_result) {
            $dictionaryAttributeModel = new DictionaryAttributeModel();
            foreach ($field_result as $key => $value) {
                $orm = $dictionaryAttributeModel->orm()->where('field_value',$value['field_value']);
                $enum_result = $dictionaryAttributeModel->getAllRecord($orm,'attribute_name,attribute_value');
                $enum = [];
                if (!empty($enum_result)) {
                    foreach ($enum_result as $e) {
                        $enum[$e['attribute_value']] = [
                            'value' => $e['attribute_value'],
                            'name' => $e['attribute_name'],
                        ];
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
        $model_name = $model_result['field_value'];
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
                        $enum[$e['field_value']] = $e;
                    }
                }
                $value['enum'] = $enum;
                $field_result[$key] = $value;
            }
        }
        return $field_result;
    }

    public function getFormInit($table_name, $mode = 'table')
    {
        if ($mode == 'table') {
            $field_definded = $this->getTableDefinded($table_name);
        } elseif ($mode = 'model') {
            $field_definded = $this->getModelDefined($table_name);
        }
        $form_init = [];
        foreach ($field_definded as $key => $value) {
            if ($value['form_type'] == 'none') {
                continue;
            }
            $form_init[$value['field_value']] = [
                'field' => $value['field_value'],
                'title' => $value['field_name'],
                'type' => $value['form_type'],
                'enum' => $value['enum'],
                'description' => '这是描述',
            ];
        }

        #注册钩子方法
        foreach ($field_definded as $value) {
            $hook = $value['belong_to_table'];
            $function = 'app\\logic\\' . ucfirst($hook) . '::' . $value['field_value'];
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
            $function = 'app\\logic\\' . ucfirst($hook) . '::' . $value['field_value'];
            Hook::getInstance()->add_action($hook, $function);
        }
        #获取验证规则
        $validate_rule = [];
        $name_map = [];
        foreach ($field_definded as $value) {
            if (!empty($value['validate_rule']) && $value['form_type'] != 'none') {
                $validate_rule[$value['field_value']] = $value['validate_rule'];
                $name_map[$value['field_value']] = $value['field_name'];
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
        $input_fields = array_column($field_definded, 'field_value');
        foreach ($_REQUEST as $key => $value) {
            if (in_array($key, $input_fields)) {
                $request_data[$key] = $value;
            }
        }
        #格式化数据
        foreach ($field_definded as $value) {
            if (isset($request_data[$value['field_value']])) {
                switch ($value['data_type']) {
                    case 'int':
                        $request_data[$value['field_value']] = intval($request_data[$value['field_value']]);
                        break;
                    case 'varchar':
                        $request_data[$value['field_value']] = trim($request_data[$value['field_value']]);
                        break;
                    case 'text':
                        $request_data[$value['field_value']] = htmlspecialchars($request_data[$value['field_value']]);
                        break;
                    case 'password':
                        $request_data[$value['field_value']] = sha1(trim($request_data[$value['field_value']]));
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
            $field = $value['field_value'];
            $enum = [];
            if ($value['enum']) {
                foreach ($value['enum'] as $v) {
                    $enum[$v['field_value']] = $v['field_name'];
                }
            }
            $list_init[$field] = [
                'field_name' => $value['field_name'],
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
            $where = ['field_value' => $model_name];
        }
        $cmsModel = new CmsModelModel();
        $result = $cmsModel->getRecordInfo($cmsModel->orm()->where($where));
        return $result;
    }

} 