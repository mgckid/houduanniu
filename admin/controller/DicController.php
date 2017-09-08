<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/7
 * Time: 20:50
 */

namespace app\controller;

use app\logic\Dic;
use app\model\DicModel;
use app\model\DictionaryFieldModel;
use app\model\DictionaryModel;
use houduanniu\web\Form;

/**
 * 数据管理控制器
 * @privilege 字典管理|Admin/Dic|1985b8d0-6166-11e7-ba80-fdjgfdjfhg555|1
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class DicController extends UserBaseController
{

    /**
     * 添加字典
     * @privilege 添加字典|Admin/Dic/addDictionary|e98fc64f-9467-11e7-adf5-14dda97b937d|2
     */
    public function addDictionary()
    {
        if (IS_POST) {
            $logic = new Dic();
            $model = new DicModel();
            $request_data = $logic->getRequestData($model->getTableName(), 'table');
            $result = $model->addRecord($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            #查询记录
            $logic = new Dic();
            $model = new DicModel();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('Data/dictionaryManage'),
                '添加字典' => '',
            ));
            $this->display('Dic/addDictionary');
        }
    }

    /**
     * 添加字段
     * @privilege 添加字典|Admin/Dic/addField|e3606093-9467-11e7-adf5-14dda97b937d|3
     */
    public function addField()
    {
        if (IS_POST) {

        } else {
            #查询记录
            $logic = new Dic();
            $model = new DictionaryFieldModel();
            $form_init = $logic->getFormInit($model->getTableName(), 'table');
//            print_g($form_init);
            Form::getInstance()->form_schema($form_init);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('Data/dictionaryManage'),
                '添加字典' => '',
            ));
            $this->display('Dic/addDictionary');

        }
    }

    /**
     * 添加属性
     * @privilege 添加字典|Admin/Dic/addAttribute|db0f7c59-9467-11e7-adf5-14dda97b937d|3
     */
    public function addAttribute()
    {
        $DictionaryModel = new DictionaryModel();
        $dicModel = new DicModel();
        $DictionaryFieldModel = new DictionaryFieldModel();
        $orm = $dicModel->orm()->where('deleted', 0);
        $tableResult = $dicModel->getAllRecord($orm);
        foreach ($tableResult as $value) {
            $orm = $DictionaryModel->orm()->where('value', $value['value'])->where('deleted', 0);
            $result = $DictionaryModel->getRecordInfo($orm);
            if ($result) {
                $orm = $DictionaryModel->orm()->where('pid', $result['id'])->where('deleted', 0);
                $d = $DictionaryModel->getAllRecord($orm);
                foreach ($d as $v) {
                    $data = [
                        'dictionary_id' => $value['id'],
                        'field_name' => $v['name'],
                        'field_value' => $v['value'],
                        'data_type' => $v['data_type'],
                        'form_type' => $v['form_type'],
                        'validate_rule' => $v['validate_rule'],
                    ];
                    $DictionaryFieldModel->addRecord($data);
                }
            }
        }
    }
} 