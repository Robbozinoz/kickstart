<?php

    require_once('database.php');

    class Blog {
        public $ksdb = '';
        public $base = '';
        public function __construct(){
            $this->ksdb = new Database;
            $this->base = new stdClass;
            $this->base->url = "http://".$_SERVER['SERVER_NAME'];
        }
    }

    class Posts extends Blog {
        //Page 90 editing of the construct function
        public function __construct(){
            parent::__construct();
            $this->comments = new Comments;
            if (!empty($_GET['id'])) {
                $this->viewPost($_GET['id']);
            } else {
                $this->getPosts();
            }
        }

        public function getPosts(){
            $id = 0;
            $posts = $return = array();
            $template = '';
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
            $template = 'list-posts.php';
            include_once 'frontend/templates/' . $template;
        }

        public function viewPost($postId){
            $id = $postId;
            $posts = $return = array();
            $template = '';
            $query = $this->ksdb->db->prepare("SELECT * FROM posts WHERE id = ?");
            try {
                $query->execute(array());
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
            $posts[0]['content'] = $posts[0]['content'];
            $template = 'view-post.php';
            include_once 'frontend/templates/' . $template;
        }
    }

    class Comments extends Blog{
        //Chapter 5 addition - CHECK if needed
        //public function __construct(){
          //  parent:: __construct();
            //$this->comments = new Comments();
            //if (!empty($_GET['id'])) {
              //  $this->viewPost($_GET['id']);
            //} else {
              //  $this->getPosts();
            //}
        //}   

        public function commentNumber($postId){

        }

        public function getComments($postId){

        }

        public function addComment(){
            
        }
    }