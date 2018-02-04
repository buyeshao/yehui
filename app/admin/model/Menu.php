<?php
// +----------------------------------------------------------------------
// | YFCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.rainfer.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: rainfer <81818832@qq.com>
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;
use think\Db;
/**
 * 前台菜单模型
 * @package app\admin\model
 */
class Menu extends Model
{
	public function news()
	{
		return $this->hasMany('News','news_columnid')->bind('menu_name');
	}

	public static function saveNodeList($menu_id,$node_list){
		$ids = [];
		if( !empty($node_list)){
			foreach ($node_list as $key => $value) {
				$row = Db::name("menu_node")->where(" id = {$key} AND menu_id = '{$menu_id}' AND status = 1")->find();
				 if(  !empty($row) && !empty($value)){
				 	$row['name'] = $value;
				 	Db::name("menu_node")->update($row);	
				 	$ids[] = $key;
		
				 }elseif( !empty($value) ){
				 	$row['menu_id'] = $menu_id;
				 	$row['name'] = $value;
				 	$id = Db::name("menu_node")->insertGetId($row);	
				 	$ids[] = $id;
			
				 }
			}
		}
		
		$con = [
			'menu_id' => ['eq',$menu_id],
			'status' => ['eq',1],
		];
		if( !empty( $ids ) ){
			$con['id'] = ['NOT IN',$ids];
			Db::name("menu_node")->where($con)->update(['status'=>0]);
		}else{
			Db::name("menu_node")->where($con)->update(['status'=>0]);
		}
	}
}
