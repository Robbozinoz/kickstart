<?php
    require_once('database.php');

    class Login{

        //Set the variable value
        public $ksdb = '';
		public $base = '';

        public function __construct(){
                $this->ksdb = new Database;
                $this->base = (object) '';
                $this->base->url = "http://".$_SERVER['SERVER_NAME'];
                $this->index();
        }

        //Checks to see if the user has logged in
        public function index(){
            if (!empty($_GET['status']) && $_GET['status'] == 'logout') {
                session_unset();
                session_destroy();
                $error = 'You have been logged out. Please login in again. ';
                require_once('admin/templates/loginform.php');
            } elseif (!empty($_SESSION['kickstart_login']) && $_SESSION['kickstart_login']) {
                header('Location: ' . $this->base->url . '/admin/posts.php');
                exit();
            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->validateDetails();
                } elseif (!empty($_GET['status'])) {
                    if ($_GET['status'] == 'inactive') {
                        session_unset();
                        session_destroy();
                        $error = 'You have been logged out due to falling asleep. Please wake up and login again.';
                    }
                }
                require_once('admin/templates/loginform.php');
            }
            //Removed p114
            //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              //  $this->validateDetails();
            //} elseif (!empty($_GET['status']) && $_GET['status'] == 'inactive') {
              //  $error = 'You have been logged out due to falling asleep. Please wake up and login again.';
            //}
            //Change to require - robboz
         
        }

        //Header request for server to send location detail
        //Replaced p113 - header('Location: http:' . $_SERVER['SERVER_NAME'] . '/admin/posts.php');
        public function loginSuccess(){
            $_SESSION['kickstart_login'] = true;
            $_SESSION["timeout"] = time();
            header('Location: ' . $this->base->url . '/admin/posts.php');
            return;
        }
        
        public function loginFail(){
            return 'Your Username/Password was incorrect';
        }

        //Add a salt to password can be changed to suit
        private function validateDetails() {
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                $salt = '$2a$07$R.gJb2U2N.FmZ4hPp1y2CN$';
                $password  = crypt($_POST['password'], $salt);
                $return = array();
                $query = $this->ksdb->db->prepare("SELECT * FROM users WHERE username = '".$_POST['username']."' AND password = '".$password."'");
                try {
                    $query->execute();
                    //Check change $query->execute(array($_POST['username'], $password));
                    for ($i=0; $row = $query->fetch(); $i++) {
                        $return[$i] = array(); 
                        foreach ($row as $key => $rowitem) {
                            $return[$i] = $rowitem;
                        }
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }

                //Check change - robboz & 'is_array($login) &&' 
                $login = $return;
                if (!empty($return) && is_array($login) && !empty($return[0])) {
                    $this->loginSuccess();
                } else {
                    echo $error = $this->loginFail();
                }
            }

        }
    }
