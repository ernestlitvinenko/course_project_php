<?php
require_once 'conf.php';
$GLOBALS['mysql'] = new mysqli(...array_values($GLOBALS['mysql_con_properties']));

function check_user_exist($email) {
    $mysql = $GLOBALS['mysql'];
    $query = "SELECT id FROM users WHERE email = '$email'";
    return $mysql->query($query)->fetch_assoc();
}
function get_channels() {
    $mysql = $GLOBALS['mysql'];
    $query = "SELECT * FROM channel";
    $data = $mysql->query($query);

    $returned_list = [];
    foreach ($data as $channel) {
        $returned_list[] = $channel;
    }
    return $returned_list;
}
function get_channel($channel_id){
    $mysql = $GLOBALS['mysql'];
    $query = "SELECT * FROM channel where id = $channel_id";
    return $mysql->query($query)->fetch_assoc();
}

function get_messages($channel_id, $field = NULL) {
    $mysql = $GLOBALS['mysql'];
    if (!isset($field)) {
        $query = "SELECT * FROM sms where _channel_id = $channel_id";
    }
    else {
        $query = "select * from sms where _channel_id = $channel_id AND id IN (SELECT _sms_id from fields_sms_relations where _hashtag_id = $field)";
    }
    $returned_list = [];

    foreach ($mysql->query($query) as $row){
        $returned_list[] = $row;
    }
    return $returned_list;
}

function check_pass(string $str):bool
{
    $regexp_eng_only = '/^[A-z\d!?*]{8,}$/';
    $regexp = '/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[*!?])\S*$/';
    if (preg_match($regexp, $str) && preg_match($regexp_eng_only, $str)) {
        return true;
    }
    return false;
}

$send_message = function () {
    $mysql = $GLOBALS['mysql'];
    $message = $_POST['message'];
    $user = $_SESSION['user_id'];
    $channel = $_POST['channel_id'];
    $hashtag_regexp = '/#\S*/';

//    Add message into DB
    $message_id = intval($mysql->query("SELECT MAX(id) from sms")->fetch_assoc()['MAX(id)']) + 1;
    $query_message = "INSERT INTO sms (id, _user_id, _channel_id, data) VALUES ('$message_id', '$user', '$channel', '$message')";
    $mysql->query($query_message);

//  HASHTAGS

// regexp matches
    if (!preg_match($hashtag_regexp, $message)) {
        header("Location: /channel.php?channel_id=$channel");
        return NULL;
    }
    preg_match_all($hashtag_regexp, $message, $matches);

//    INSERT HASHTAGS or duplicate it
    $insert_hashtags_query = "INSERT INTO fields (data) values ";
    $hashtags_for_query = [];
    $matches_for_query = [];
    foreach ($matches[0] as $hashtag) {
        $hashtags_for_query[] = "('$hashtag')";
        $matches_for_query[] = "data = '$hashtag'";
    }
    $joined_hashtag_query = implode(", ", $hashtags_for_query);
    $insert_hashtags_query .= $joined_hashtag_query . " ON DUPLICATE KEY UPDATE data=data";

    $mysql->query($insert_hashtags_query);

    // build "where" expressions for MySQL
    $where_str = implode(" OR ", $matches_for_query);
    $query_str = "SELECT id, data from fields where $where_str";



//   Check hashtags
    $hashtags_inside_db = [];

    foreach ($mysql->query($query_str) as $hashtag) {
        $hashtags_inside_db[$hashtag['data']] = $hashtag['id'];
    }

    //    Setup relations
    $query_relations = "INSERT INTO fields_sms_relations (_hashtag_id, _sms_id) VALUES ";
    foreach ($hashtags_inside_db as $key => $val) {
        $hashtags_inside_db[$key] = "($val, $message_id)";
    }
    $relations_query = implode(', ', array_values($hashtags_inside_db));
    $query_relations .= $relations_query;
    $mysql->query($query_relations);
    header("Location: /channel.php?channel_id=$channel");

};

$register_user = function () {
    $mysql = $GLOBALS['mysql'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    openssl_private_decrypt(base64_decode($password), $decrypted_pass, $GLOBALS['private_key'],  );
    $password = utf8_encode($decrypted_pass);
    if (!check_pass($password)) {
        echo <<<HTML
<p>Пароль не соответсвует требованиям, вам нужно</p>
<ul>
    <li>Использовать только буквы латинского алфавита</li>
    <li>Минимум 8 символов</li>
    <li>1 Заглавная буква</li>
    <li>1 цифра</li>
    <li>1 спец-символ</li>
</ul>
HTML;
        return NULL;

    }
    if (check_user_exist($email)) {
        echo "EMAIL ALREADY EXIST";
        return NULL;
    }
    $password = md5($password);

    $query = "INSERT INTO users (name, email, password) values ('$name', '$email', '$password')";


    $mysql->query($query);
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $user_exist = $mysql->query($query)->fetch_assoc();
    $_SESSION['user_id'] = $user_exist['id'];
    header("Location: /");
};

$login_user = function () {
    $mysql = $GLOBALS['mysql'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    openssl_private_decrypt(base64_decode($password), $decrypted_pass, $GLOBALS['private_key'],  );

    $password = md5(utf8_encode($decrypted_pass));

    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $user_exist = $mysql->query($query)->fetch_assoc();
    if (!$user_exist) {
        echo "USER NOT exist";
        return NULL;
    }
    $_SESSION['user_id'] = $user_exist['id'];
    $_SESSION['username'] = $user_exist['name'];
    header("Location: /");
    return $user_exist;
};
$post_methods = array(
  'register_user' => $register_user,
    'login_user' => $login_user,
    'send_message' => $send_message,
);

$get_methods = array(
    );

if (isset($_GET['method'])) {
    $method = $_GET['method'];
    if (isset($get_methods[$method])) {
        $get_methods[$method]();
    }
}
else if (isset($_POST['method'])) {
    $method = $_POST['method'];
    if( isset($post_methods[$method])) {
        $post_methods[$method]();
    }
};