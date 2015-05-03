<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class BookinfoController extends BaseController {
    public function index(){
    	$p         = I('get.p');
        $book_name = I('get.book_name');
        $book_type = I('get.book_type');
        $is_blue   = I('get.is_blue');
        if(empty($p)){
            $p = 1;
        }
        if(!empty($book_name)){
            $content['bi.name'] = array('like','%'.$book_name.'%');
        }
        if(!empty($book_type)){
            $content['bt.id'] = array('like','%'.$book_type.'%');
        }
        if($is_blue != '' && $is_blue != '2'){
            $content['bi.is_blue'] = $is_blue;
        }
        $content['bi.is_del'] = 0;
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('book_info bi')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->where($content)
                   ->count();
    	$result    = M('book_info bi')
                   ->field('bi.*,bt.name as type_name')
                   ->join('book_type bt on bi.type_id = bt.id')
                   ->where($content)
                   ->limit($startPage,$list_rows)
                   ->order("create_time desc")
                   ->select();
                  // var_dump(M('book_info bi')->getlastsql());
        $type_list = M('book_type')->where('is_del = 0')->select();
        $this->assign('lists',$type_list);
    	$page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
    	$this->assign('result',$result);
        $this->assign('content',I("get."));
	    $this->display();
    }

    public function back_info(){
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


    public function editbook(){
        $type_list = M('book_type')->where('is_del = 0')->select();
        $this->assign('lists',$type_list);
        $this->display();
    }

    public function del_book(){
        $book_id   = I('post.id');
        if(empty($book_id)){
            return false;
        }
        $count = M('book_info')->where(array('id'=>$book_id))->delete();
        if(!empty($count)){
            echo $count;
        } 
    }

    public function edit_book(){
        $book_name = I('post.book_name');
        $type_id   = I('post.type_id');
        $book_id   = I('post.chbook_type');
        if(empty($book_name) || empty($type_id) || empty($book_id)){
            return false;
        }

        $data['name']        = $book_name;
        $data['type_id']     = $type_id;
        $count = M('book_info')->where(array('id'=>$book_id))->save($data);
        if(!empty($count)){
            echo $count;
        } 
    }

    
    public function borrow_book(){
        if(session('type') == 1){
            return false;
        }
        $id = I('post.id');
        if(empty($id)){
            return false;
        }
        $user_id = session('id');
        $list['user_id'] = $user_id;
        $list['is_back'] = 0;
        $list['is_past'] = 1;
        $result_list = M()->table('borrow')->where($list)->find();
        if(!empty($result_list)){
            echo -1;
            return false;
        }
        $where['id'] = $id;
        $where['is_checkout'] = 0;
        $where['is_del']      = 0;
        $where['is_blue']     = 0;
        $result = M()->table('book_info')->where($where)->find();
        if(empty($result)){
            return false;
        }
        M()->startTrans();
        $data['book_id']     = $id;
        $data['user_id']     = $user_id;
        $data['create_time'] = time();
        $list_add = M()->table('borrow')->add($data);
        $update   = M()->table("book_info")
                 ->where(array('id' => $id))
                 ->setInc('borrow_num');
        $update_list = M()->table("book_info")
                     ->where(array('id' => $id))
                     ->setInc('is_checkout');
        if(empty($update) || empty($list_add) || empty($update_list)){
            M()->rollback();
        }else{
            echo 1;
            M()->commit();
        }
    }

    public function borrow_info(){
        $p         = I('get.p');
        if(empty($p)){
            $p = 1;
        }
        $content['is_del'] = 0;
        $list_rows = C('LIST_ROWS');
        $startPage = ($p-1) * $list_rows;
        $total_num = M('book_info')
                   ->where($content)
                   ->count();
        $result    = M('book_info')
                   ->where($content)
                   ->limit($startPage,$list_rows)
                   ->order("borrow_num desc")
                   ->select();
        $this->assign('lists',$type_list);
        $page      = new \Think\Page($total_num, $list_rows);
        $this->assign('_page', $page->show($p));
        $this->assign('result',$result);
        $this->display();
    }

    //添加新闻页面
    public function addnews(){
    	$this->display('Webset:Websetchildren/addnews');
    }

     //添加新闻页面
    public function newsinfo(){
        $id = I("get.id");
        if(!empty($id)){
            $where['id'] = $id;
            $total_list = M('news_info')->where($where)->find();
            $this->assign('total_list',$total_list);
        }
        $this->display('Bookinfo:Websetchildren/newsinfo');
    }

    //修改新闻
    public function news_update(){
        $id = I("get.id");
        if(!empty($id)){
            $where['id'] = $id;
            $total_list = M('news_info')->where($where)->find();
            $this->assign('total_list',$total_list);
        }
        $this->display('Bookinfo:Websetchildren/updatenews');
    }

    //修改新闻
    public function news_delete(){
        $id = I("post.id");
        if(!empty($id)){
            $where['id']     = $id;
            $where['status'] = 1;
            $total_list = M('news_info')->save($where);
            if($total_list > 0){
                echo $total_list;
            }
        }
    }

    public function add_news(){
    	$title         = trim(I("post.title"));
    	$content       = trim(I("post.content"));
        $hideen_new_id = trim(I("post.hideen_new_id"));
        $hideen_status = trim(I("post.hideen_status"));
    	if(empty($title) || empty($content) || empty($hideen_status)){
    		return false;
    	}
    	$data['title']       = $title;
        $data['content']     = $content;
        $data['read_count']  = 0;
        $data['create_time'] = time();
        $data['update_time'] = $data['create_time'];
        //添加新闻
        if($hideen_status == 'add'){
            //开启事务支持
            M()->startTrans();
            $result = M()->table('news_info')->add($data); //添加新闻获得返回值
            //得到用户列表
            $where['status']    = 0;
            $where['is_active'] = 1;
            $user_list = M()->table('user_info')->field('id')->where($where)->select();
            $count = count($user_list);
            //组装user_news_rela数据
            for($i = 0;$i < $count;$i++){
                $user_news_rela[$i]['user_id'] = intval($user_list[$i]['id']);
                $user_news_rela[$i]['rela_id'] = $result;
                $user_news_rela[$i]['type']    = 1;
            }

            M()->table('user_news_rela')->addAll($user_news_rela);
            $arrect = mysql_affected_rows();
            //如果导入数据条数与提供数据条数不等，则回滚
            if(!empty($result) && !empty($user_list) && $arrect == $count){
                M()->commit();
                echo 1;
            }else{
                M()->rollback();
                return false;
            }
        }else{
            if(empty($hideen_new_id)){
                return false;
            }
            $data['id'] = $hideen_new_id;
            unset($data['create_time']);
            unset($data['read_count']);
            $result = M()->table('news_info')->save($data); //添加新闻获得返回值
            if($result > 0){
                echo $result;
            }
        }
    }
}