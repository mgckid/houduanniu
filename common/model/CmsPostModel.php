<?php

/**
 * Description of CmsPostModel
 *
 * @date 2016年5月8日 14:11:16
 *
 * @author Administrator
 */

namespace app\model;

use app\logic\BaseLogic;
use app\model\BaseModel;
use Core\DB;

class CmsPostModel extends BaseModel
{

    public $tableName = 'cms_post';
    public $pk = 'id';

    /**
     * 获取文章列表
     * @param type $condition 条件
     * @param type $offset 偏移量
     * @param type $limit 获取条数
     * @param type $forCount 统计
     * @param type $field 字段
     * @return type
     */
    public function getArticleList($condition, $offset, $limit, $forCount = false, $field = 'a.*')
    {
        $orm = $this->orm()
            ->table_alias('a')
            ->select_expr($field)
            ->left_outer_join('cms_category', array('a.column_id', '=', 'c.id'), 'c');
        if ($condition) {
            foreach ($condition as $key => $value) {
                $orm = call_user_func_array(array($orm, $key), $value);
            }
        }
        if ($forCount) {
            $result = $orm->count();
        } else {
            $result = $orm
                ->limit($limit)
                ->offset($offset)
                ->order_by_desc('id')
                ->find_array();
        }
        return $result;
    }


    /**
     * 获取文章信息
     * @param type $id 文章id
     * @param type $field 字段名
     * @return type
     */
    public function getArticleInfoById($id, $field = "a.*")
    {
        $result = $this->orm()
            ->table_alias('a')
            ->left_outer_join('cms_category', array('a.column_id', '=', 'c.id'), 'c')
            ->select_expr($field)
            ->find_one($id);
        if (!$result) {
            return false;
        }
        return $result->as_array();;
    }


    /**
     * 获取推荐文章列表
     * @param array $condition
     * @param $rows
     * @param string $field
     * @return type
     */
    public function getRecommendArticleList(array $condition, $rows, $field = '*')
    {
        return $this->getArticleList($condition, 0, $rows, false, $field);
    }

    /**
     * 获取下一篇文章
     * @param $id
     * @param $cateid
     * @param string $field
     * @return mixed
     */
    public function getNext($post_id, $category_id)
    {
        $orm = $this->orm()->where('post_id', $post_id);
        $post_result = parent::getRecordInfo($orm, 'id');
        $next_id = $this->orm()
            ->where('category_id', $category_id)
            ->where_gt('id', $post_result['id'])
            ->order_by_asc('id')
            ->find_one();
        $result = [];
        if ($next_id) {
            $next_id = $next_id->as_array();
            $result = $this->getRecordInfoById($next_id['id']);
        }
        return $result;
    }

    /**
     * 获取上一篇文章
     * @param $id
     * @param $cateid
     * @param string $field
     * @return mixed
     */
    public function getPre($post_id, $category_id)
    {
        $orm = $this->orm()->where('post_id', $post_id);
        $post_result = parent::getRecordInfo($orm, 'id');
        $pre_id = $this->orm()
            ->where('category_id', $category_id)
            ->where_lt('id', $post_result['id'])
            ->order_by_asc('id')
            ->find_one();
        $result = [];
        if ($pre_id) {
            $pre_id = $pre_id->as_array();
            $result = $this->getRecordInfoById($pre_id['id']);
        }
        return $result;
    }

    /**
     * 获取热门文章
     * @access public
     * @author furong
     * @param int $limit
     * @param string $field
     * @param string $cateId
     * @return mixed
     * @since 2017年4月24日 16:09:09
     * @abstract
     */
    public function getHotPost($limit = 10, $field = 'a.*', $cateId = '')
    {
        $orm = $this->orm()
            ->table_alias('a')
            ->select_expr($field)
            ->left_outer_join('cms_category', array('a.column_id', '=', 'c.id'), 'c');
        if ($cateId) {
            $orm = $orm->where('a.column_id', $cateId);
        }
        $result = $orm
            ->limit($limit)
            ->order_by_desc('click')
            ->find_array();
        return $result;
    }

    /**
     * 获取相关文章
     * @access public
     * @author furong
     * @param $postId
     * @param string $field
     * @param int $limit
     * @return array
     * @since 2017年4月25日 18:01:30
     * @abstract
     */
    public function getRelatedPost($postId, $field = 'p.*', $limit = 10)
    {

        $cateRelatedResult = [];
        #标签相关文章
        $tagRelatedResult = $this->orm()
            ->for_table('cms_post_tag')
            ->table_alias('pt')
            ->select_expr($field)
            ->left_join('cms_post_tag', 'ptt.tag_id = pt.tag_id', 'ptt')
            ->left_join('cms_post', 'ptt.post_id = p.id', 'p')
            ->where('pt.post_id', $postId)
            ->where_not_equal('p.id', $postId)
            ->group_by('p.id')
            ->order_by_desc('p.id')
            ->limit($limit)
            ->find_array();
        $resultRows = count($tagRelatedResult);
        #栏目相关文章
        if ($resultRows < $limit) {

            $limit = $limit - $resultRows;
            $cateId = current($this->getArticleInfoById($postId, 'a.column_id'));
            $cateModel = new CmsCategoryModel();
            $condition = [
                'where' => ['cate_type', $cateModel::CATE_TYPE_LIST],
            ];
            $cateList = $cateModel->getColumnList($condition);
            $subCate = array_column(treeStructForLevel($cateList, $cateId), 'id');
            $subCate[] = $cateId;
            $notInPostId = array_values(array_column($tagRelatedResult, 'id')) + [$postId];
            $condition = [
                'where_not_in' => ['a.id', $notInPostId],
                'where_in' => ['a.column_id', $subCate],
            ];
            $cateRelatedResult = $this->getArticleList($condition, 0, $limit, false, 'a.id,a.title,a.image_name,a.title_alias');
        }
        $result = array_merge($tagRelatedResult, $cateRelatedResult);
        return $result;
    }

    public function addCmsPostExtendData($table_name, $post_id, $field, $value)
    {
        $orm = $this->orm()->for_table($table_name)->use_id_column('id');
        $data = [
            'post_id' => $post_id,
            'field' => $field,
            'value' => $value
        ];
        $result = $orm->where('post_id', $post_id)
            ->where('field', $field)
            ->find_one();
        if ($result) {
            $return = $result->set($data)->save();
        } else {
            $return = $orm->create($data)->save();
        }
        if (!$return) {
            throw new \Exception('文档扩展记录添加失败');
        }
        return $return;
    }







    /**改版后****/
    /**
     * 获取文章列表
     * @param type $condition 条件
     * @param type $offset 偏移量
     * @param type $limit 获取条数
     * @param type $forCount 统计
     * @param type $field 字段
     * @return type
     */
    public function getPostList($orm = '', $offset, $limit, $forCount = false, $order_by = 'a.id', $sort = 'desc', $field = 'a.*,c.category_name')
    {
        $orm = $this->getOrm($orm)->where('a.deleted', 0)
            ->table_alias('a')
            ->select_expr($field)
            ->left_outer_join('cms_category', array('a.category_id', '=', 'c.id'), 'c');
        #排序
        if ($sort == 'desc') {
            $orm = $orm->order_by_desc($order_by);
        } elseif ($sort == 'asc') {
            $orm = $orm->order_by_asc($order_by);
        }
        if ($forCount) {
            $result = $orm->count();
        } else {
            $result = $orm
                ->limit($limit)
                ->offset($offset)
                ->order_by_desc('id')
                ->find_array();
        }
        return $result;
    }

    /**
     * 获取文档扩展属性
     * @param $post_id
     * @return array
     */
    public function getPostExtendAttrbute($post_id)
    {
        $result = $this->for_table('cms_post_extend_attribute')
            ->use_id_column('id')
            ->select_expr('field,value')
            ->where('post_id', $post_id)
            ->find_array();
        if ($result) {
            $field = array_column($result, 'field');
            $value = array_column($result, 'value');
            $result = array_combine($field, $value);
        }
        return $result;
    }

    public function getPostsInfo($post_id)
    {
        $post_info = [];
        $result = $this->orm()->where('post_id', $post_id)->find_one();
        if ($result) {
            $post_info = $this->getRecordInfoById($result['id']);
        }
        return $post_info;
    }

    /**
     * 获取单条记录
     * @access public
     * @author furong
     * @param $id
     * @param string $field
     * @return array|bool
     * @since 2017年7月28日 09:40:34
     * @abstract
     */
    public function getRecordInfoById($id, $field = 'p.*,m.value as model')
    {
        $result = $this->orm()->select_expr($field)->table_alias('p')
            ->left_join('cms_model', ['p.model_id', '=', 'm.id'], 'm')
            ->where('p.id', $id)
            ->find_one();
        if (!empty($result)) {
            $result = $result->as_array();
        }
        return $result;
    }

}
