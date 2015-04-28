<?php
/**
 * UserController类 用于用户数据处理;
 * @author:zhaoyafei;
 * time   : 2015-2-26
 */
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {

	/**
     * 初始化用户数据
     * @author:zhaoyafei;
     */
    public function index(){
    	$p = I('get.p');
        if(empty($p)){
            $p = 1;
        }
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = D("User")->get_user_list_count();
    	$result    = D("User")->get_user_list('',$startPage,$list_rows);
        //呈现异步分页
    	$page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->ajaxShow($p));
        $this->assign('user_list',$result);
        $this->display('index');
    }

    /**
     * 异步分页，查询数据
     * @author:zhaoyafei;
     */
    public function user_data_search(){ 
        $p         = I('post.p');
        $user_name = I('post.user_name');
        $phone     = I('post.phone');
        $gender    = intval(I('post.gender'));
        $status    = intval(I('post.status'));
        $user_type = intval(I('post.user_type'));
        if(empty($p)){
            $p = 1;
        }
        if(!empty($user_name)){
            $content['user_name'] = array('like','%'.$user_name.'%','OR');
        }
        if(!empty($phone)){
            $content['phone'] = array('like','%'.$phone.'%','OR');
        }
        if(!empty($email)){
            $content['email'] = array('like',$email);
        }
        if(!empty($start_time) || !empty($end_time)){
            $content['_string'] = get_time_sql($start_time,$end_time);
        }
        
        if($gender > -1 && $gender < 2){
            $content['gender'] = $gender;
        }
        if($user_type > -1 && $user_type < 5){
            $content['user_type'] = $user_type;
        }else{
            $content['user_type'] != 4;
        }

        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = D("User")->get_user_list_count($content);

        $result    = D("User")->get_user_list($content,$startPage,$list_rows);
        //var_dump(D("User")->getlastsql());
        //呈现异步分页
        $page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->ajaxShow($p));
        $this->assign('user_list',$result);
        $this->display('user_data_search');
    }

    public function user_delete(){
        $id = I("post.id");
        $where['id'] = $id;
        $result = M('user_info')->where($where)->delete();
        if($result > 0){
            echo $result;
        }
    }

    
    public function addusers(){
        $user_name = I("post.user_name");
        $phone     = I("post.phone");
        $gender    = I("post.gender");
        $user_type = I("post.user_type");
        if(empty($user_name) || empty($user_type)){
            return false;
        }
        $where['user_name'] = $user_name;
        $where['phone']     = $phone;
        $where['gender']    = $gender;
        $where['user_type'] = $user_type;
        $where['create_time'] = time();
        $where['password']    = md5(1);
        $notice = M('user_info')->where(array('user_name'=>$user_name))->find();
        if(empty($notice)){
            $result = M('user_info')->add($where);
           // var_dump(M()->getlastsql());
            if($result > 0){
                echo $result;
            }
        }else{
            echo -1;
        }
        
    }


    /**
     * 启用，禁用用户
     * @author:zhaoyafei;
     */
    public function forbid_user(){
        $id     = I("post.id"); 
        $status = I("post.status"); 
        if(!empty($id) && $status > -1 && $status < 3){
            //条件
            $where['status'] = $status;
            $where['id']     = $id;
            $result = M('user_info')->save($where);
            if($result > 0){
                echo $result;
            }
        }
    }

    /**
     * 后台添加用户
     * @author:zhaoyafei;
     */
    public function add_user(){
        $province  = M("province")->select();
        $this->assign('province',$province);
        $this->display();
    }

    /**
     * 后台角色管理
     * @author:zhaoyafei;
     */
    public function user_role(){
        $role_list = M('user_role_info ui1')
                    ->field('ui1.*,ui2.name as parent_name')
                    ->join('user_role_info ui2 on ui1.parent_id = ui2.id')
                    ->where("ui1.id > 3")
                    ->select();
        $this->assign('default_type',C("DEFAULT_TYPE"));
        $this->assign('role_list',$role_list);
        $this->display('user_role');
    }

    /**
     * 后台角色管理
     * @author:zhaoyafei;
     */
    public function link_user(){
        $id = intval(I("get.role_id"));
        if(empty($id)){
            $id = C('DEFAULT_TYPE');
        }
        $user_list = M('admin_user au')
                    ->field('au.*')
                    ->join('user_role_rela urr on au.id = urr.user_id')
                    ->where("au.is_forbid = 0 and urr.role_id = ".C('DEFAULT_TYPE')." and urr.role_id !=".C("ADMIN_TYPE"))
                    ->order('au.create_time desc')
                    ->select();
        $where['au.is_forbid'] = 0;
        $where['urr.role_id']  = $id;
        $role_user_list = M('admin_user au')
                    ->field("au.*")
                    ->join("user_role_rela urr on au.id = urr.user_id")
                    ->where($where)
                    ->order('au.create_time desc')
                    ->select();
        
        $this->assign('role_id',$id);
        $this->assign('role_list',$this->get_role_list(0));
        $this->assign('role_user_list',$role_user_list);
        $this->assign('user_list',$user_list);
        $this->display('link_user');
    }

    /**
     * 得到用户组
     * status:需不需要显示默认用户组
     */
    public function get_role_list($status){
        if($status == 0){
            $search_num = 4;
        }else if($status == 1){
            $search_num = 3;
        }
        $role_list = M('user_role_info')
                    ->where("is_forbid = 0 and parent_id > 0 and id > ".$search_num)
                    ->select();
        return $role_list;
    }
    //切换选项卡
    public function role_search_user(){
        $role_id = I("post.role_id");
        if(empty($role_id)){
            return false;
        }
        $where['au.is_forbid'] = 0;
        $where['urr.role_id']  = $role_id;
        $role_user_list = M('admin_user au')
                    ->field("au.*")
                    ->join("user_role_rela urr on au.id = urr.user_id")
                    ->where($where)
                    ->order('au.create_time desc')
                    ->select();
        if(!empty($role_user_list)){
            $string = '';
            foreach($role_user_list as $k=>$value){
                $string .= "<option value='".$value['id']."'>".$value['id']."--".$value['user_name']."--".$value['phone']."</option>";
            }
            echo $string;
        }
    }

    public function set_user(){
        $all_value = trim(I("post.all_value"));
        $role_type = trim(I("post.role_type"));
        if(empty($role_type)){
            return false;
        }
        if(!empty($all_value)){
            $all_value = explode('+', $all_value);
        }
        $where['au.is_forbid'] = 0;
        $where['urr.role_id']  = $role_type;
        $old_list = M('admin_user au')
                ->field("au.id")
                ->join("user_role_rela urr on au.id = urr.user_id")
                ->where($where)
                ->select();
        if(!empty($old_list)){
            foreach ($old_list as $key => $value) {
                 $old_list[$key] = $value['id'];
            }
        }
        if(empty($old_list)){
            $add_arr = $all_value;
        }else if(empty($all_value)){
            $delete_arr = $old_list;
        }else{
            $common = array_intersect($old_list,$all_value);
            $delete_arr = array_diff($old_list,$common);
            $add_arr = array_diff($all_value,$common);
        } 
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
         $id = "";
         $i = 0;
         foreach($arr as $k=>$v){
              $id .= $v.","; 
              $i++;
         }
         $id = substr($id,0,strlen($id)-1);
         $data_where['user_id'] = array('in',$id);
         $data_save['role_id'] = $role_type;
         M()->startTrans();
         if(!empty($data_where)){
            M("user_role_rela")->where($data_where)->save($data_save);
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

    public function delete_role($arr){
         $id = "";
         $i = 0;
         foreach($arr as $k=>$v){
              $id .= $v.","; 
              $i++;
         }
         $id = substr($id,0,strlen($id)-1);
         $data_where['user_id'] = array('in',$id);
         $data_save['role_id'] = C('DEFAULT_TYPE');
         M()->startTrans();
         if(!empty($data_where)){
            M("user_role_rela")->where($data_where)->save($data_save);
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

    public function adminuser(){
        $p         = I('get.p');
        $user_name = I('get.user_name');
        $real_name = I('get.real_name');
        $phone     = I('get.phone');
        $email     = I('get.email');
        $start_time= I('get.start_time');
        $end_time  = I('get.end_time');
        $gender    = I('get.gender');
        $is_forbid = I('get.is_forbid');
        $user_type = I('get.user_type');
        if(empty($p)){
            $p = 1;
        }
        $content = array();
        if(!empty($user_name) && $user_name != ''){
            $content['user_name'] = array('like','%'.$user_name.'%','OR');
        }
        if(!empty($real_name) && $real_name != ''){
            $content['real_name'] = array('like','%'.$real_name.'%','OR');
        }
        if(!empty($phone) && $phone != ''){
            $content['phone'] = array('like','%'.$phone.'%','OR');
        }
        if(!empty($email) && $email != ''){
            $content['email'] = array('like',$email);
        }
        if(!empty($start_time) || !empty($end_time)){
            $content['_string'] = get_time_sql($start_time,$end_time);
        }
        if($gender != ''){
            $gender = intval($gender);
            if($gender > -1 && $gender < 2){
                $content['au.gender'] = $gender;
            }
        }
        if($is_forbid != ''){
            $is_forbid = intval($is_forbid);
            if($is_forbid > -1 && $is_forbid < 3){
                $content['au.is_forbid'] = $is_forbid;
            }
        }
        
        if($user_type != ''){
            $user_type = intval($user_type);
            if($user_type > 3){
                $content['uri.id'] = $user_type;
            }
        }
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = D("User")->get_adminuser_count($content);
        $result    = D("User")->get_adminuser_list($content,$startPage,$list_rows);
        //呈现异步分页
        $page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
        $this->assign('content',I("get."));
        $this->assign('role_list',$this->get_role_list(1));
        $this->assign('adminuser_list',$result);
        $this->display('adminuser');
    }
}