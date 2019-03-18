<?php
session_start();
require_once('database.php');
class Adminpanel {
    public function __construct() {
        $inactive = 600;
        if (isset($_SESSION['kickstart_login'])) {
            $sessionTTL = time() - $_SESSION['timeout'];
            if ($sessionTTL > $inactive) {
                session_unset();
                session_destroy();
                header('Location: http://' . $_SERVER['SERVER_NAME'] . '/login.php?status=inactive');
            }
        }
        $_SESSION['timeout'] = time();
        $login = $_SESSION['kickstart_login'];
        if (empty($login)) {
            session_unset();
            session_destroy();
            header('Location: http://' . $_SERVER['SERVER_NAME'] . '/login.php?status=loggedout');
        } else {
            $this->ksdb = new Database;
            $this->base = (object) '';
            $this->base->url = 'http://'.$_SERVER['SERVER_NAME'];
        }
    }
}

class Posts extends Adminpanel {

    public function __construct(){
        parent::__construct();
        //p115 posts class setting
        if (!empty($_GET['action'])) {
            switch ($_GET['action']) {
                case 'create':
                    $this->addPost();
                    break;
                default:
                    $this->listPosts();
                    break;
                case 'save':
                    $this->savePost();
                    break;
                case 'delete':
                    $this->deletePost();
                    break;
            }
        } else {
            $this->listPosts();
        }
    }

    public function listPosts(){
        $posts = $return = array();
        $query = $this->ksdb->db->prepare("SELECT * FROM posts");
        try {
            $query->execute();
            for ($i=0; $row = $query->fetch(); $i++) { 
                $return[$i] = array();
                foreach ($row as $key => $rowitem) {
                    $return[$i][$key] = $rowitem;
                }
            }
        } catch (PDOException $e) {
                echo $e->getMessage();
        }
        $posts = $return;

        //Change require_once
        require_once('templates/manageposts.php');
    }

    public function editPosts(){

    }

    public function addPost(){
        //Change to require- robboz
        require_once('templates/newpost.php');
    }

    public function savePost(){
        $array = $format = $return = array();
        if (!empty($_POST['post'])) {
            $post = $_POST['post'];
        }

        //Check this line on p99 is not "$_POST"
        if (!empty($post['content'])) {
            $array['content'] = $post['content'];
            $format[] = ':content';
        }


        //Variables used match those of database.php to allow data insertion
        $cols = $values = '';

        $i=0;
        foreach ($array as $col => $data) {
            if ($i == 0) {
                $cols .= $col;
                $values .= $format[$i];
            } else {
                $cols .= ',' . $col;
                $values .= ',' . $format[$i];
            }
            $i++;
            # code...
        }
        try {
            $query = $this->ksdb->db->prepare("INSERT INTO posts (".$cols.") VALUES (".$values.")");
            for ($c=0;$c<$i;$c++) { 
                //Using a variable variable loop through the content of $cols & $vars
                $query->bindParam($format[$c], ${'var'.$c});
            }
            $z=0;
            foreach ($array as $col => $data) {
                ${'var' . $z} = $data;
                $z++;
            }
            $result = $query->execute();
            $add = $query->rowCount();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $query->closeCursor();
        $this->db = null;
        if (!empty($add)) {
            $status = array('error' => 'Your posts has been saved...Yesssss!');
        } else {
            $status = array('error' => 'OOPS!, There has been an error saving the post. Please try again later' );
        }
        //Check file path header("Location: http://localhost/kickstart/admin/posts.php");
        header("Location: http://localhost/admin/posts.php");
    }

    public function deletePost() {
        if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
            $query = "DELETE FROM `posts` WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute(array($_GET['id']));
            $delete = $stmt->rowCount();
            $this->db = null;
            if (!empty($delete) && $delete > 0) {
                header('Location: ' . $this->base->url . '/posts.php?delete=success');
            } else {
                header('Location: ' . $this->base->url . '/posts.php?delete=error');
            }
        }
    }
}

    class Comments extends Adminpanel {
        public function __construct(){
            parent::__construct();
        }

        public function listComments(){

        }

        public function deletePost(){

        }

    }

    $admin = new Adminpanel;