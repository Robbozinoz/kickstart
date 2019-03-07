<?php
session_start();
require_once('database.php');
class Adminpanel {
    public function __construct() {
        $this->ksdb = new Database;
        $this->base = (object) '';
        $this->base->url = "http://".$_SERVER['SERVER_NAME'];
    }
}

class Posts extends Adminpanel {

    public function __construct(){
        parent::__construct();
        //This needs to be expanded - check book
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