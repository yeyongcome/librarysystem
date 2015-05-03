<?php 
namespace Home\Model;
use Think\Model;


class BaseModel extends Model{

	/**
	 * 得到主导航
	 */
	public function get_top_menu($type){
		$where['uni.depth']   = 1;
		$where['rnr.role_id'] = $type;
		$result = $this->table("user_node_info uni")
			   ->field("uni.*")
			   ->join('role_node_rela rnr on uni.id = rnr.node_id')
               ->where($where)
               ->order("uni.sort_factor")
		       ->select();
		return $result;
	}

	/**
	 * 得到子导航
	 */
	public function get_child_menu($type){
		$where['uni.depth']   = 2;
		$where['rnr.role_id'] = $type;
		$result = $this->table("user_node_info uni")
			   ->field("uni.*")
			   ->join('role_node_rela rnr on uni.id = rnr.node_id')
               ->where($where)
               ->order("uni.sort_factor")
		       ->select();
		return $result;
	} 	

	/*
		生成公共的sql语句
	 */
		
	public function get_comsql($where){

		$sql = "SELECT distinct node_id, name,url,depth
				from user_role_rela urr
				left join role_node_rela ror on urr.role_id = ror.role_id 
				left join user_node_info uni on uni.id = ror.node_id
				where $where"." order by uni.sort_factor" ;
		return $sql;
	}

	/*
		检验该用户类型是否具有访问指定url的权限
	 */
	public function get_power($type,$url,$now_child_url){
		$sql = "select count(*) sum from role_node_rela rnr 
				left join user_node_info uni on uni.id = rnr.node_id
				where uni.is_del = 0 
				and rnr.role_id = '".$type."'
				and uni.url = '".$url."'
				and left(rnr.node_id ,3 * (select depth from user_node_info 
				where url = '".$now_child_url."'))
				= (select id from user_node_info 
				where url = '".$now_child_url."'
				)";
		$result = $this->query($sql);
		return $result[0]['sum'];

	}
}

 ?>