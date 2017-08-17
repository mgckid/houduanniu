<?php

namespace app\controller;

use app\logic\DictionarryLogic;
use app\model\DictionaryModel;
use houduanniu\web\Form;
use houduanniu\base\Page;

/**
 * 数据管理控制器
 * @privilege 数据管理|Admin/Data|1985b8d0-6166-11e7-ba80-5996e3b2d0fb|1
 * @date 2016年5月4日 21:17:23
 * @author Administrator
 */
class DataController extends UserBaseController
{
    /**
     * 字典管理
     * @privilege 字典管理|Admin/Data/dictionaryManage|4e030640-6166-11e7-ba80-5996e3b2d0fb|2
     */
    public function dictionaryManage()
    {
        $pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
        #查询列表
        {
            $dictionaryModel = new DictionaryModel();
            $condition = ['pid' => $pid];
            $result = $dictionaryModel->getRecordList($dictionaryModel->orm()->where($condition), 0, 999, false, false);
        }
        #获取列表字段
        $dictionaryLogic = new DictionarryLogic();
        $list_init = $dictionaryLogic->getListInit('dictionarys');
        $data = array(
            'list' => $result,
            'list_init' => $list_init,
        );
        #面包屑导航
        $this->crumb(array(
            '数据管理' => U('Cms/index'),
            '字典管理' => ''
        ));
        $this->display('data/dictionaryManage', $data);
    }

    /**
     * 字典管理
     * @privilege 字段管理|Admin/Data/fieldManage|4e030640-6166-11e7-ba80-5996e3b2d0dd|3
     */
    public function fieldManage()
    {
        $pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
        $dictionaryModel = new DictionaryModel();
        #查询字典信息
        {
            $dictionary_info = $dictionaryModel->getRecordInfoById($pid);
        }
        #查询列表
        {
            $condition = ['pid' => $pid];
            $result = $dictionaryModel->getRecordList($dictionaryModel->orm()->where($condition), 0, 999, false, false);
            #整理数据
            foreach ($result as $key => $value) {
                $counts = $dictionaryModel->getDictionaryList($dictionaryModel->orm()->where('pid', $value['id']), '', '', true);
                $value['attr_count'] = $counts;
                $result[$key] = $value;
            }
        }
        #获取列表字段
        $dictionaryLogic = new DictionarryLogic();
        $list_init = $dictionaryLogic->getListInit('dictionarys');

        $data = array(
            'list' => $result,
            'list_init' => $list_init,
            'dictionary_info' => $dictionary_info
        );
        #面包屑导航
        $this->crumb(array(
            '字典管理' => U('Data/dictionaryManage'),
            $dictionary_info['name'] . '字段管理' => '',
        ));
        $this->display('data/fieldManage', $data);
    }

    /**
     * 字典管理
     * @privilege 属性管理|Admin/Data/attributeManage|4e030640-6166-11e7-ba80-5996e3b2d0ff|3
     */
    public function attributeManage()
    {
        $pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : 0;
        $dictionaryModel = new DictionaryModel();
        #查询字典信息
        {
            #查询字段信息
            $field_info = $dictionaryModel->getRecordInfoById($pid);
            #查询字典信息
            $dictionary_info = $dictionaryModel->getRecordInfoById($field_info['pid']);
        }

        #查询列表
        {
            $condition = ['pid' => $pid];
            $result = $dictionaryModel->getRecordList($dictionaryModel->orm()->where($condition), 0, 999, false, false);
            #整理数据
            foreach ($result as $key => $value) {
                $counts = $dictionaryModel->getDictionaryList($dictionaryModel->orm()->where('pid', $value['id']), '', '', true);
                $value['attr_count'] = $counts;
                $result[$key] = $value;
            }
        }
        #获取列表字段
        $dictionaryLogic = new DictionarryLogic();
        $list_init = $dictionaryLogic->getListInit('dictionarys');

        $data = array(
            'list' => $result,
            'list_init' => $list_init,
            'field_info' => $field_info,
        );
        #面包屑导航
        $this->crumb(array(
            '字典管理' => U('Data/dictionaryManage'),
            $dictionary_info['name'] . '字段管理' => U('Data/fieldManage', ['pid' => $field_info['pid']]),
            $field_info['name'] . '属性管理' => '',
        ));
        $this->display('data/attributeManage', $data);
    }


    /**
     * 添加字典
     * @privilege 添加字典|Admin/Data/addDictionary|75b601fb-6189-11e7-ac40-e03f49a02407|3
     */
    public function addDictionary()
    {
        if (IS_POST) {
            $logic = new DictionarryLogic();
            $request_data = $logic->getRequestData('dictionarys', 'table');
            $model = new DictionaryModel();
            $result = $model->addDictionary($request_data);
            if (!$result) {
                $this->ajaxFail();
            } else {
                $this->ajaxSuccess();
            }
        } else {
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
            #查询记录
            $model = new DictionaryModel();
            $result = [];
            if ($id) {
                $result = $model->getRecordInfoById($id);
            }
            if (!$result && $pid) {
                $result['pid'] = $pid;
            }
            $logic = new DictionarryLogic();
            $form_init = $logic->getFormInit('dictionarys', 'table');
            if(in_array($result['data_type'],['table','attribute'])){
                foreach($form_init as $key=> $value){
                    if(!in_array($key,['id','pid','name','value','data_type'])){
                        $value['type']='none';
                    }
                    $form_init[$key]=$value;
                }
            }
            Form::getInstance()->form_schema($form_init)->form_data($result);
            #面包屑导航
            $this->crumb(array(
                '字典管理' => U('Data/dictionaryManage'),
                '添加字典' => '',
            ));
            $this->display('Data/addDictionary');
        }

    }


    /**
     * 删除字典
     * @privilege 删除字典|Admin/Data/delDictionary|2cd3e8b4-774b-11e7-ba80-5996e3b2d0fb|3
     */
    public function delDictionary()
    {
        if (!IS_POST) {
            $this->ajaxFail('非法请求');
        }
        $model = new DictionaryModel();
        #验证
        $rule = array(
            'id' => 'required|integer',
        );
        $attr = array(
            'id' => '配置id',
        );
        $validate = $model->validate()->make($_POST, $rule, [], $attr);
        if (false === $validate->passes()) {
            $this->ajaxFail($validate->messages()->first());
        }
        #获取参数
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        #检查是否能删除
        $all_dictionary = $model->getAllRecord();
        $subDictionary = getChilden($all_dictionary, $id);
        if (!empty($subDictionary)) {
            $this->ajaxFail('请删除该字典下数据');
        }
        #删除记录
        if (!$model->deleteRecordById($id)) {
            $this->ajaxFail($this->getMessage());
        } else {
            $this->ajaxSuccess($this->getMessage());
        }
    }

}