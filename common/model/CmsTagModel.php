<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/18
 * Time: 10:27
 */

namespace app\model;


class CmsTagModel extends BaseModel
{
    public $tableName = 'cms_tag';
    public $pk = 'tag_id';

    /**
     * 添加标签
     * @param $data
     * @return mixed
     */
    public function addTag($data)
    {
        $data['modified'] = getDateTime();
        $model = $this->orm();
        $exist = $model->where('tag_name', $data['tag_name'])
            ->find_one();
        if ($exist) {
            $data = $exist->as_array();
            return $exist['tag_id'];
        }
        $return = false;
        if (empty($data['tag_id'])) {
            unset($data['tag_id']);
            #添加
            $data['created'] = getDateTime();
            $result = $model->create($data)
                ->save();
            $return = $model->id();
        } else {
            #修改
            $id = $data['tag_id'];
            $result = $model->find_one($id);
            if ($result) {
                $return = $result->set($data)
                    ->save();
            } else {
                $this->setMessage('标签数据不存在');
            }
        }
        return $return;
    }

    /**
     * 获取标签列表
     * @param $offset
     * @param $limit
     * @param bool $forCount
     * @param string $field
     * @return string
     */
    public function getTagList($offset, $limit, $forCount = false, $field = '*')
    {
        $model = $this->orm();
        if ($forCount) {
            $result = $model->count();
        } else {
            $result = $model->offset($offset)
                ->select_expr($field)
                ->limit($limit)
                ->order_by_asc('tag_id')
                ->find_array();
        }
        return $result;
    }

    /**
     * 添加文章标签关联记录
     * @param $tagId
     * @param $postId
     * @return bool
     */
    public function addTagPost($tagId, $postId)
    {
        if (empty($tagId) || empty($postId)) {
            return false;
        }
        $data = array(
            'tag_id' => $tagId,
            'post_id' => $postId,
        );
        $cmsPostTagModel = $this->for_table('cms_post_tag');
        $count = $cmsPostTagModel->where($data)->count();
        if ($count) {
            return true;
        }
        #添加
        return $cmsPostTagModel->create($data)->save();
    }

    public function delTagPost($postId, array $tagId)
    {
        $cmsPostTagModel = $this->for_table('cms_post_tag');
        $model = $cmsPostTagModel->where(['post_id' => $postId]);
        if (!empty($tagId)) {
            $model = $model->where_not_in('tag_id', $tagId);
        }
        $result = $model->delete_many();
        return $result;
    }


    public function getTagByPostId($postId, $field = 't.tag_name,t.tag_id')
    {
        $result = $this->orm()
            ->table_alias('t')
            ->select_expr($field)
            ->join('cms_post_tag', array('t.tag_id', '=', 'pt.tag_id'), 'pt')
            ->where(array('pt.post_id' => $postId))
            ->find_array();
        return $result;
    }

    /**
     * 获取热门标签
     * @param string $limit
     * @return mixed
     */
    public function getHotTagList($limit = '20')
    {
        $result = $this->orm()
            ->table_alias('t')
            ->left_join('cms_post_tag', 't.tag_id = pt.tag_id', 'pt')
            ->left_join('cms_post', 'pt.post_id = p.id', 'p')
            ->select_expr('t.tag_name,count(p.id) as post_count')
            ->group_by('t.tag_id')
            ->order_by_desc('post_count')
            ->limit($limit)
            ->find_array();
        return $result;
    }

    public function getTagPostList($condition, $offset, $limit, $forCount = false, $field = 'p.*')
    {
        $orm = $this->orm()
            ->table_alias('t')
            ->select_expr($field)
            ->left_join('cms_post_tag', 't.tag_id = tp.tag_id', 'tp')
            ->left_join('cms_post', 'tp.post_id = p.id', 'p');
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
}