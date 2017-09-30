<?php
/**
 * Created by PhpStorm.
 * User: CPR137
 * Date: 2017/9/30
 * Time: 17:18
 */

namespace app\logic;


use app\model\CmsPostModel;

class PostLogic extends BaseLogic
{
    function getRecordList($model_id, $orm , $offset = '', $limit = '', $for_count = false, $sort_field = 'created', $order = 'desc', $field = '*')
    {
        $orm = $orm->where('cms_post.model_id',$model_id);
        if ($for_count) {
            $result = $orm->count();
        } else {
            $model_defined = $this->getModelDefined($model_id);

            $cms_post_extend_attribute_select[] = 'post_id';
            $cms_post_extend_text_select[] = 'post_id';
            $select_expr = '';
            foreach ($model_defined as $value) {
                if ($field != '*') {
                    $fields = explode(',', $field);
                    foreach ($fields as $val) {
                        if ($value['field_value'] == $val) {
                            $select_expr[] = $value['belong_to_table'] . '.' . $value['field_value'];
                        }
                    }
                } else {
                    $select_expr[] = $value['belong_to_table'] . '.' . $value['field_value'];
                }
                if ($value['belong_to_table'] == 'cms_post_extend_attribute') {
                    $cms_post_extend_attribute_select[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS {$value['field_value']}";
                }
                if ($value['belong_to_table'] == 'cms_post_extend_text') {
                    $cms_post_extend_text_select[] = "max( CASE field WHEN '{$value['field_value']}' THEN `value` ELSE '' END ) AS {$value['field_value']}";
                }
            }

            #cms_post_extend_attribute
            {
                $cms_post_extend_attribute_select = implode(',', $cms_post_extend_attribute_select);
                $cms_post_extend_attribute_raw_join = "left join (SELECT {$cms_post_extend_attribute_select} FROM cms_post_extend_attribute  GROUP BY post_id )";
            }
            #cms_post_extend_text
            {
                $cms_post_extend_text_select = implode(',', $cms_post_extend_text_select);
                $cms_post_extend_text_raw_join = "left join (SELECT {$cms_post_extend_text_select} FROM cms_post_extend_text  GROUP BY post_id )";
            }

            $orm = $orm->raw_join($cms_post_extend_attribute_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_attribute.post_id'], 'cms_post_extend_attribute')
                ->raw_join($cms_post_extend_text_raw_join, ['cms_post.post_id', '=', 'cms_post_extend_text.post_id'], 'cms_post_extend_text')
                ->offset($offset)
                ->select_expr($field)
                ->limit($limit);
            if ($order == 'desc') {
                $orm = $orm->order_by_desc($sort_field);
            } else {
                $orm = $orm->order_by_asc($sort_field);
            }
            $result = $orm->find_array();
        }
        return $result;
    }
} 