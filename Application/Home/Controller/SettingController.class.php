<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class SettingController extends BaseController {
    public function index(){
        $p         = I('get.p');
        $book_name = I('get.book_name');
        $book_type = I('get.book_type');
        $user_name   = trim(I('get.user_name'));
        if(empty($p)){
            $p = 1;
        }
        if(!empty($book_name)){
            $content['bi.name'] = array('like','%'.$book_name.'%');
        }
        if(!empty($book_type)){
            $content['bt.id'] = array('like','%'.$book_type.'%');
        }
        if($user_name != ''){
            $content['ui.user_name'] = $user_name;
        }
        $content['bi.is_del'] = 0;
        $content['b.is_back'] = 0;
        $content['b.is_past'] = 1;
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('book_info bi')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->join('user_info ui on ui.id = b.user_id')
                   ->where($content)
                   ->count();
        $result    = M('book_info bi')
                   ->field('bi.name,b.*,bt.name as type_name,ui.user_name as user_name,ui.user_type')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->join('borrow b on b.book_id = bi.id')
                   ->join('user_info ui on ui.id = b.user_id')
                   ->where($content)
                   ->limit($startPage,$list_rows)
                   ->order("create_time desc")
                   ->select();
                //var_dump(M()->getlastsql());
        $count = count($result);
        for($i = 0; $i < $count; $i++){
            if($result[$i]['user_type'] == '2'){
                $day = intval(C('T_PASSTIME'));
            }else if($result[$i]['user_type'] == '3'){
                $day = intval(C('S_PASSTIME'));
            }
            $result[$i]['realback_time'] = $result[$i]['create_time'] + $day*3600*24; 
        }
        $type_list = M('book_type')->where('is_del = 0')->select();
        $this->assign('lists',$type_list);
        $page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
        $this->assign('result',$result);
        $this->assign('content',I("get."));
        $this->display();
    }

    public function back_book(){
        $id = I('post.id');
        if(empty($id)){
            return false;
        }
        $data['is_back']   = 1;
        $data['back_time'] = time();
        $count = M('borrow')->where(array('id'=>$id))->save($data);

        if(!empty($count)){
            echo $count;
        } 
    }

    public function log(){
        $p = I('get.p');
        if(empty($p)){
            $p = 1;
        }
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('operate_log ol')
                    ->join('user_info ui on ol.operate_id = ui.id')
                    ->count();
        $result    = M('operate_log ol')
                    ->field("ol.*,ui.user_name")
                    ->join('user_info ui on ol.operate_id = ui.id')
                    ->limit($startPage,$list_rows)
                    ->order('create_time')
                    ->select();
        //呈现异步分页
        $page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
        $this->assign('result',$result);
        $this->display('log');
    }

    //删除日志
    public function delete_log(){
        $id = I("post.id");
        $where['id'] = $id;
        $result = M('operate_log')->where($where)->delete();
        if($result > 0){
            echo $result;
        }
    }

    //保存类型
    public function bsave(){
        $type_name = I('post.type_name');
        if(empty($type_name)){
            return false;
        }
        $data['name']        = $type_name;
        $data['is_del']      = 0;
        $data['create_time'] = time();
        $result = M('book_type')->add($data);
        if(!empty($result)){
            echo $result;
        } 
    }

    public function add_book(){
        $result = M('book_type')->where('is_del = 0')->select();
        $this->assign('lists',$result);
        $this->display('add_book');
    }

    public function add_books(){
        $book_name = I('post.book_name');
        $type_id   = I('post.type_id');
        if(empty($book_name) || empty($type_id)){
            return false;
        }

        $data['name']        = $book_name;
        $data['type_id']     = $type_id;
        $data['create_time'] = time();
        $count = M('book_info')->where(array('type_id'=>$type_id))->count();
        if($count > 0){
            $data['is_blue'] = 0;
        }else{
            $data['is_blue'] = 1;
        }
        $result = M('book_info')->add($data);
        if(!empty($result)){
            echo $result;
        } 
    }

    public function authority(){
        $user_type = intval(I("get.role_id"));
        if(empty($user_type)){
            $user_type = 4;
        }
    	//所有权限
        $list_all = M('user_node_info')
                ->field('id,name,url,parent_id')
                ->select();
        foreach ($list_all as $key => $value) {
            $list_all[$key]['pId'] = $value['parent_id'];
            $list_all[$key]['open'] = true;
            if($value['parent_id'] == 0){
            	$list_all[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/8.png";
            }else{
            	$list_all[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/2.png";
            }
        }
        //所有权限
        $list_user = M('user_role_info')
                ->field('id,name,parent_id')
                ->select();
        foreach ($list_user as $key => $value) {
            $list_user[$key]['pId'] = $value['parent_id'];
            $list_user[$key]['open'] = true;
            if($value['parent_id'] == 0){
            	$list_user[$key]['iconOpen'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/1_open.png";
            	$list_user[$key]['iconClose'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/1_close.png";
            }else{
            	$list_user[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/11.png";
            }
            if($value['id'] == 3){
            	$list_user[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/10.png";
            }
        }
        $this->assign('user_type',$user_type);
        $this->assign('list_user',json_encode($list_user));
        $this->assign('list_all',json_encode($list_all));
        $this->display();
    }

    public function get_type_role(){
    	//所有权限
        $where['rnr.role_id'] = I("post.role_type");
        $type_role = M('role_node_rela rnr')
                ->field('uni.id,uni.name,uni.parent_id')
                ->join('user_node_info uni on rnr.node_id = uni.id')
                ->where($where)
                ->select();
        if(!empty($type_role)){
        	foreach ($type_role as $key => $value) {
	            $type_role[$key]['pId'] = $value['parent_id'];
	            $type_role[$key]['open'] = true;
	            if($value['parent_id'] == 0){
	            	$type_role[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/8.png";
	            }else{
	            	$type_role[$key]['icon'] = __ROOT__."/Public/static/ztree/css/zTreeStyle/img/diy/2.png";
	            }
	        }
	        echo json_encode($type_role);
        }else{
        	echo '';
        }
    }

    public function save_role(){
    	$znodes_str = trim(I('post.znodes_str'));
    	$role_type  = trim(I('post.role_type'));
    	if(empty($role_type)){
    		return false;
    	}
    	$data = array();
    	if(!empty($znodes_str)){
    		$znodes_str = explode(',', $znodes_str);
    		foreach (array_chunk($znodes_str, 2) as $key => $value) {
	    		$data[$value[0]] = $value[0];
	    		$data[$value[1]] = $value[1];
	    	}
	    	unset($data[0]);
    	}
    	$where['role_id'] = $role_type;
    	$old_list = M("role_node_rela")->where($where)->getField('node_id',true);

    	if(empty($old_list)){
            $add_arr = $data;
        }else if(empty($data)){
            $delete_arr = $old_list;
        }else{
            $common = array_intersect($old_list,$data);
            $delete_arr = array_diff($old_list,$common);
            $add_arr = array_diff($data,$common);
        } 
        /*dump($common);
        dump($delete_arr);
        dump($add_arr);*/
        if(!empty($add_arr)){
             $result = $this->add_role($add_arr,$role_type);
        }
        if(!empty($delete_arr)){  
             $result = $this->delete_role($delete_arr,$role_type);
        }
        if(empty($delete_arr) && empty($add_arr)){
        	$result = 1;
        }
        if($result > 0){
        	echo $result;
        }
    }

    public function add_role($arr,$role_type){
    	 $i = 0;
         foreach($arr as $k=>$v){
              $data[$i]['role_id'] = $role_type;
              $data[$i++]['node_id'] = $v;
         }
         M()->startTrans();

         if(!empty($data)){
         	M("role_node_rela")->addAll($data);
         }else{
         	return false;
         }
         if(mysql_affected_rows() == count($data)){
         	M()->commit();
         	return count($data);
         }else{
         	M()->rollback();
         	return false;
         }
    }

    public function delete_role($arr,$role_type){
         $id = "";
         $i = 0;
         foreach($arr as $k=>$v){
              $id .= $v.","; 
              $i++;
         }
         $id = substr($id,0,strlen($id)-1);
         $data['node_id'] = array('in',$id);
         $data['role_id'] = $role_type;
         M()->startTrans();
         if(!empty($data)){
         	M("role_node_rela")->where($data)->delete();
         }else{
         	return false;
         }
         if(mysql_affected_rows() == $i){
         	M()->commit();
         	return $i;
         }else{
         	M()->rollback();
         	return false;
         }
    }
}