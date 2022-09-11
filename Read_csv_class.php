<?php
//ini_set("display_errors","On");
session_start();

class Read_csv{
    var $max=10000;
    var $cnt=0;
    var $handle = null;
    /**
     * @param string $filename
     * @param int $cnt
     */
    public function __construct($filename="",$cnt=0)
    {
        $this->cnt = $cnt;
        $this->handle =  fopen($filename, "r");
        $_SESSION["offset"]?fseek($this->handle,$_SESSION["offset"]):$this->handle;
        if ( $this->handle !== FALSE) {
            $this->reader();
        }
    }
    /**
     * @return void
     */
    public function reader():void
    {
            $response = null;
            $data = fgetcsv($this->handle, null, ",");
            if($data !== FALSE) {
                $_SESSION["offset"] = ftell($this->handle);
                $response["data"] = $data;
                $response["cnt"] = $this->cnt>$this->max?0:($this->cnt + 1);
                print json_encode($response);
            }else{
                $_SESSION["offset"] = null;
                print "";
            }
    }
}

if (isset(d_xss($_POST["csrf_token"]))  && d_xss($_POST["csrf_token"]) === $_SESSION['csrf_token']) {
    $_SESSION["offset"] = (int)d_xss($_POST["reset_flag"])===1?null:d_xss($_SESSION["offset"]);
    $filename = d_xss($_POST["filename"]);
    $cnt = (int)d_xss($_POST["cnt"]);
    //👉$Read_csv = new Read_csv($filename,$cnt);
    $Read_csv = null;
 }else{
    print "";
 }
 function d_xss($data){
    $data = strip_tags($data);
    $data = htmlspecialchars($data,ENT_QUOTES);
    return $data;
}
