<?php


class  AuthController
{
    private $redis = '';

    public function __construct()
    {

        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1', 6379);
        echo "Connection to server sucessfully".'<br>';
    }


    function registration($email, $password, $name, $address)
    {

        $email = $this->clear($email);
        $password = md5($this->clear($password) . $this->secretKey());
        $name = $this->clear($name);
        $address = $this->clear($address);
        $hash = $this->hash($email);
        if(!$this->redis->exists($email)){
            $this->redis->set($email, json_encode(array(
                'email' => $email,
                'password' => $password,
                'hash' => $hash,
                'name'=>$name,
                'address'=>$address,
            )));
            echo 'reg success'.'<br>';
        }else{
            echo 'user exist'.'<br>';
        }


    }

    function auth($email, $password)
    {
        $email = $this->clear($email);
        $password = md5($this->clear($password) . $this->secretKey());
       if($this->redis->exists($email)){
           $user =json_decode($this->redis->get($email));
           echo "<pre>";
           echo "</pre>";
           if( $password == $user->password){
               echo 'Auth success'.'<br>';
           }else{
               echo 'Auth Error'.'<br>';
           }
       }
       echo 'go to registration';

    }

    function clear($data)
    {
        return trim(htmlspecialchars($data));
    }


    private function secretKey()
    {
        return 'dsf3vfdvdvo34fl';
    }

    protected function hash($email)
    {
        return md5($email . time());
    }

}

$auth = new AuthController();
$auth->registration('test@gmail1.com','123456', 'User',' sfsfslknfksdnfkjdsn');
$auth->auth('test@gmail1.com','123456');