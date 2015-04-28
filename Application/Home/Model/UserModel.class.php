<?php 
namespace Home\Model;
use Think\Model;

class UserModel extends Model{

	/**
	 * 得到用户总数量
	 * @param $content:检索条件
	 */
	public function get_user_list_count($content){
		if(empty($content)){
			//无条件查询
			return $this->table("user_info")->count();
		}
		return $this->table("user_info")->where($content)->count();
	}

	/**
	 * 得到用户列表详情
	 * @param $content:检索条件
	 */
	public function get_user_list($content,$startPage,$list_rows){
		if(empty($content)){
			//无条件查询
			$res = $this->table("user_info")
						->order('create_time desc')
						->limit($startPage,$list_rows)
			            ->select();
			return $res;
		}
		$lt = $this->table("user_info")
		            ->where($content)
		            ->order('create_time desc')
		            ->limit($startPage,$list_rows)
		            ->select();
		return $lt;
	}

		/**
	 * 得到用户总数量
	 * @param $content:检索条件
	 */
	public function get_adminuser_count($content){
		//dump($content);
		if(empty($content)){
			//无条件查询
			return $this ->table('admin_user au')
                         ->field("au.*,uri.name as rela_name")
                         ->join('user_role_rela urr on urr.user_id = au.id')
                         ->join('user_role_info uri on uri.id = urr.role_id')
                         ->count();
		}
		return  $this ->table('admin_user au')
                     ->field("au.*,uri.name as rela_name")
                     ->join('user_role_rela urr on urr.user_id = au.id')
                     ->join('user_role_info uri on uri.id = urr.role_id')
                     ->where($content)
                     ->count();
	}

	/**
	 * 得到用户列表详情
	 * @param $content:检索条件
	 */
	public function get_adminuser_list($content,$startPage,$list_rows){
		if(empty($content)){
			//无条件查询
			return $this ->table('admin_user au')
                         ->field("au.*,uri.name as rela_name")
                         ->join('user_role_rela urr on urr.user_id = au.id')
                         ->join('user_role_info uri on uri.id = urr.role_id')
                         ->order("au.create_time desc")
                         ->limit($startPage,$list_rows)
                         ->select();
		}
		//有条件查询
		return $this ->table('admin_user au')
                     ->field("au.*,uri.name as rela_name")
                     ->join('user_role_rela urr on urr.user_id = au.id')
                     ->join('user_role_info uri on uri.id = urr.role_id')
                     ->where($content)
                     ->order("au.create_time desc")
                     ->limit($startPage,$list_rows)
                     ->select();
		            //var_dump($this->getlastsql());
	}
}

 ?>