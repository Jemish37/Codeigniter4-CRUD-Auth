<?php
$forge = \Config\Database::forge();


$CI_INSTANCE = [];  # It keeps a ref to global CI instance

function &get_instance(): \App\Controllers\BaseController
{
    global $CI_INSTANCE;
    return $CI_INSTANCE[0];
}
function register_ci_instance(\App\Controllers\BaseController &$_ci)
{
    global $CI_INSTANCE;
    $CI_INSTANCE[0] = &$_ci;
}


function enc($s, $enc_key = 'qwer', $cm = 'aes-128-ctr')
{
    $ei = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cm));
    $c = openssl_encrypt($s, $cm, $enc_key, 2, $ei) . "::" . bin2hex($ei);
    unset($s, $cm, $enc_key, $ei);
    return $c;
}

function dec($c, $enc_key = 'qwer', $cm = 'aes-128-ctr')
{
    list($c, $ei) = explode("::", $c);
    if (ctype_xdigit($ei) && strlen($ei) % 2 == 0) {
        $t = openssl_decrypt($c, $cm, $enc_key, 2, hex2bin($ei));
        unset($c, $cm, $enc_key, $ei);
        return $t;
    } else {
        return '';
    }
}

function get_client_ip()
{
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else {
        $ci = &get_instance();
        $ipaddress = $ci->input->ip_address();
        if (!$ci->input->valid_ip($ipaddress)) {
            $externalContent = file_get_contents('http://checkip.dyndns.com/');
            preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
            $ipaddress = $m[1];
        }
    }
    return $ipaddress;
}

function strGenerate($c = 10)
{
    $d = "0123456789atqwertyuiolkjhgfdsxcvbngeasdefghjkutewrty";
    $th = strlen($d);
    $g = '';
    for ($i = 0; $i < $c; $i++) {
        $g .= $d[rand(0, $th - 1)];
    }
    return $g;
}

function generateNumeric($a = 6)
{
    $g = "725469138";
    $r = "";
    for ($i = 0; $i < $a; $i++) {
        $r .= substr($g, (rand() % (strlen($g))), 1);
    }
    return $r;
}

function chk_string($a)
{
    return preg_match("/^[a-zA-Z]{2,}$/", $a);
}

function chk_number($a)
{
    return preg_match("/^\d+$/", $a);
}

function chk_name($a)
{
    return preg_match("/^[a-zA-Z]{2,30}$/", $a);
}

function chk_fullname($a)
{
    return preg_match("/^[a-zA-Z ]{2,30}$/", $a);
}

function chk_string_num($a, $start, $end)
{
    return preg_match("/^[a-zA-Z]{" . $start . "," . $end . "}$/", $a);
}

function chk_userId($a)
{
    return preg_match("/^[A-Z0-9]{8,16}$/", $a);
}

function chk_recommended($a)
{
    return preg_match("/^[A-Z0-9]{8,16}$/", $a);
}

function chk_phone($a)
{
    return preg_match("/^[+][0-9]{8,15}$/", $a);
}

function chk_mail($a)
{
    return filter_var($a, FILTER_VALIDATE_EMAIL) && preg_match('/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $a);
}

function chk_password($a)
{
    return preg_match("/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[!\\/\\\\\"#$%&'()*+,-.\\:;<=>?@[\]^_`{|}~])\S*$/", $a);
}

function filvar($a)
{
    return str_replace([" or ", " OR "], "", filter_var(trim($a), FILTER_SANITIZE_SPECIAL_CHARS));
}


function send_data($data)
{
    return json_encode(["status" => $data[0], 'data' => $data[1], 'msg' => $data[2]]);
}

function showMsg($data)
{
    echo "<pre>";
    print_r($data);
}

function dead($data)
{
    echo "<pre>";
    print_r($data);
    die;
}

function showPost()
{
    showMsg($_POST);
}

function showSession()
{
    showMsg($_SESSION);
    die;
}

function showServer()
{
    showMsg($_SERVER);
    die;
}

function getRows($fields, $table, $where = '1=1')
{
    $ci = &get_instance();
    $q = $ci->db->query("select $fields from $table where $where");
    return $q->result_array();
}

function getRow($fields, $table, $where = '1=1')
{
    $ci = &get_instance();
    $q = $ci->db->query("select $fields from $table where $where");
    return $q->row_array();
}

function getCount($fields, $table, $where = '1=1')
{
    $ci = &get_instance();
    $q = $ci->db->query("select $fields from $table where $where");
    return $q->num_rows();
}

function insert($table, $data, $type = 'Single')
{
    $ci = &get_instance();
    if ($type == 'Single') {
        $ci->db->insert($table, $data);
        return $ci->db->insert_id();
    } else {
        return $ci->db->insert_batch($table, $data);
    }
}

function updatePM($table, $where, $data)
{
    $ci = &get_instance();
    foreach ($data as $datum) {
        $ci->db->set($datum[0], $datum[1], $datum[2]);
    }
    $ci->db->where($where);
    return $ci->db->update($table);
}

function update($table, $where, $data)
{
    $ci = &get_instance();
    return $ci->db->update($table, $data, $where);
}

function insert_batch($table, $data)
{
    $ci = &get_instance();
    return $ci->db->insert_batch($table, $data);
}

function delete($table, $where)
{
    $ci = &get_instance();
    return $ci->db->delete($table, $where);
}

function getAdmin()
{
    return 1;
}

function keyVal($arr, $key, $value)
{
    $newArr = [];
    foreach ($arr as $key => $value) {
    }
}

function db()
{
    echo "FDG";
}
