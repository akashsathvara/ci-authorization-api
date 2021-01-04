<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
    #########################  User Profile Data  ###############################
    // customer login
    public function wd_check_userdata($email) {
        $query = $this->db->get_where('wdp_users', array('email' => $email));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // add userdata
    public function wd_add_userdata($data) {
        $this->db->insert('wdp_users', $data);
        return array("status" => 201, "message" => "Data has been created");
    }
    // user login data
    public function wd_loginuser($email, $password) {
        $this->db->select("*");
        $this->db->where('email', $email);
        $this->db->where('status','1');
        $query = $this->db->get('wdp_users');
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            //$hash = password_hash($password1, PASSWORD_DEFAULT);
            $p1 = password_verify($password, $res[0]['password']);
            if ($p1 > 0) {
                return $res;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // get user details
    public function wd_get_userdata($user_id) {
        $query = $this->db->get_where('wdp_users', array('user_id' => $user_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // change user password
    public function wd_userChangepassword($user_id,$oldpassword,$newpassword) {
        $query = $this->db->get_where('wdp_users', array('user_id' => $user_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            $getpassword = $res[0]['password'];
            $password = password_verify($oldpassword, $getpassword);
            if ($password > 0) {
                $newpassword = password_hash($newpassword, PASSWORD_DEFAULT);
                $data = array('password'=>$newpassword);
                $this->db->where('user_id', $user_id);
                $this->db->update('wdp_users', $data);
                return array("status" => 201, "message" => "Data has been updated");
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    // delete user
    public function wd_delete_user($user_id) {
        $this->db->where('user_id', $user_id);
        $this->db->delete('wdp_users');
        return array("status" => 201, "message" => "Data has been deleted");
    }
    // update user data
    public function wd_update_user_data($user_id, $data) {
        $this->db->where('user_id', $user_id);
        $this->db->update('wdp_users', $data);
        return array("status" => 201, "message" => "Data has been updated");
    }
    // user verify
    public function wd_user_verifydata($email,$code) {
       /* $id = '10';
        $salt=date("dm");
        $encrypted_id = base64_encode($id."-".$salt);
        $dyct = base64_decode($encrypted_id);
        $arr = explode("-", $dyct, 2);
        echo $getcode = $arr[0];*/
        $dyct = base64_decode($code);
        $arr = explode("-", $dyct, 2);
        $getcode = $arr[0];

        $query = $this->db->get_where('wdp_users', array('email' => $email,'user_id'=>$getcode));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // event get
    public function wd_check_user_eventdata($user_id,$event_id) {
        $query = $this->db->get_where('wdp_user_event', array('user_id' => $user_id,'event_id' => $event_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // add user event
    public function wd_add_user_eventdata($data) {
        $this->db->insert('wdp_user_event', $data);
        return array("status" => 201, "message" => "Data has been created");
    }
    // update event data
    public function wd_update_user_eventdata($event_id, $data) {
        $this->db->where('event_id', $event_id);
        $this->db->update('wdp_user_event', $data);
        return array("status" => 201, "message" => "Data has been updated");
    }
    // add user blog
    public function wd_add_user_blogdata($data) {
        $this->db->insert('wdp_blogs', $data);
        return array("status" => 201, "message" => "Data has been created");
    }
    // get blog details
    public function wd_get_blogdetail($blog_id) {
        $query = $this->db->get_where('wdp_blogs', array('blog_id' => $blog_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // get public blog list
    public function wd_get_bloglist($event_id) {
        $query = $this->db->query("SELECT t1.*,t2.remarks FROM wdp_blogs as t1 INNER JOIN wdp_user_event as t2 ON t1.event_id=t2.event_id WHERE t1.event_id = '".$event_id."'");
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    public function wd_check_user_blogdata($user_id,$blog_id) {
        $query = $this->db->get_where('wdp_blogs', array('user_id' => $user_id,'blog_id' => $blog_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // update blog data
    public function wd_update_user_blogdata($blog_id, $data) {
        $this->db->where('blog_id', $blog_id);
        $this->db->update('wdp_blogs', $data);
        return array("status" => 201, "message" => "Data has been updated");
    }
    // delete blog
    public function wd_delete_blog($blog_id) {
        $this->db->where('blog_id', $blog_id);
        $this->db->delete('wdp_blogs');
        return array("status" => 201, "message" => "Data has been deleted");
    }
    // add like on blog
    public function wd_add_like_blogdata($data) {
        $this->db->insert('wdp_blog_likes', $data);
        return array("status" => 201, "message" => "Data has been created");
    }
    // dislike blog
    public function wd_dislike_blog($like_id) {
        $this->db->where('like_id', $like_id);
        $this->db->delete('wdp_blog_likes');
        return array("status" => 201, "message" => "Data has been deleted");
    }
    // add comment
    public function wd_add_blog_commentdata($data) {
        $this->db->insert('wdp_comments', $data);
        return array("status" => 201, "message" => "Data has been created");
    }
    // get comment details
    public function wd_get_commentdetail($comment_id) {
        $query = $this->db->get_where('wdp_comments', array('comment_id' => $comment_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // delete comment
    public function wd_delete_comment($comment_id) {
        $this->db->where('comment_id', $comment_id);
        $this->db->delete('wdp_comments');
        return array("status" => 201, "message" => "Data has been deleted");
    }
    // get comment details
    public function wd_check_user_commentdata($user_id,$comment_id) {
        $query = $this->db->get_where('wdp_comments', array('user_id' => $user_id,'comment_id' => $comment_id));
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // update comment data
    public function wd_update_user_commentdata($comment_id, $data) {
        $this->db->where('comment_id', $comment_id);
        $this->db->update('wdp_comments', $data);
        return array("status" => 201, "message" => "Data has been updated");
    }
    // get comment list
    public function wd_get_commentlist($blog_id,$from_limit,$to_limit) {
        $query = $this->db->query("SELECT t1.*,t2.first_name,t2.last_name FROM wdp_comments as t1 LEFT JOIN wdp_users as t2 ON t1.user_id=t2.user_id WHERE t1.parent_comment_id = '0' AND t1.blog_id = '".$blog_id."' ORDER BY t1.comment_id ASC LIMIT $from_limit,$to_limit");
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }
    // get reply comment list
    public function wd_get_replylist($comment_id) {
        $query = $this->db->query("SELECT t1.*,t2.first_name,t2.last_name FROM wdp_comments as t1 LEFT JOIN wdp_users as t2 ON t1.user_id=t2.user_id WHERE t1.parent_comment_id = '".$comment_id."' ORDER BY t1.comment_id ASC");
        $res = $query->result_array();
        if ($query->num_rows() > 0) {
            return $res;
        } else {
            return false;
        }
    }

}
