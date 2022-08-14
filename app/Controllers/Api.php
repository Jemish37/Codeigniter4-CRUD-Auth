<?php


namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\ResponseInterface;

helper('fn');


class Api extends BaseController
{

    public function register()
    {
        $res = [false, [], 'Invalid request'];
        if (isset($_POST) && $_POST != "") {
            if (isset($_POST["first_name"]) && $_POST["first_name"] != "") {
                if (chk_string($_POST["first_name"])) {
                    if (isset($_POST["last_name"]) && $_POST["last_name"] != "") {
                        if (chk_string($_POST["last_name"])) {
                            if (isset($_POST["email"]) && $_POST["email"] != "") {
                                if (chk_mail($_POST["email"])) {
                                    if (isset($_POST["password"]) && $_POST["password"] != "") {
                                        if (chk_password($_POST["password"])) {
                                            $userModel = new UserModel();
                                            $data = [
                                                'first_name'    => $_POST["first_name"],
                                                'last_name' => $_POST["last_name"],
                                                'email'    => $_POST["email"],
                                                'password' => password_hash($_POST["password"], PASSWORD_DEFAULT)
                                            ];

                                            $getExistUser = $userModel->where('email', $_POST["email"])->first();

                                            if (is_null($getExistUser)) {
                                                if ($userModel->save($data)) {
                                                    $res[2] = "User Registration Successfully";
                                                    $res[0] = true;
                                                } else {
                                                    $res[2] = "Something went wrong !";
                                                }
                                            } else {
                                                $res[2] = "Email already exists";
                                            }
                                        } else {
                                            $res[2] = "Password must be combination of Upper and Lower case letters, digits and special characters";
                                        }
                                    } else {
                                        $res[2] = "Password must be provided";
                                    }
                                } else {
                                    $res[2] = "Email must be a valid email address";
                                }
                            } else {
                                $res[2] = "Email address must be provided";
                            }
                        } else {
                            $res[2] = "Last name must be String";
                        }
                    } else {
                        $res[2] = "Last name must be provided";
                    }
                } else {
                    $res[2] = "First name must be String";
                }
            } else {
                $res[2] = "First name must be provided";
            }
        }

        echo send_data($res);
    }

    public function login()
    {
        $session = session();

        $res = [false, [], 'Invalid request'];
        if (isset($_POST) && $_POST != "") {
            if (isset($_POST["email"]) && $_POST["email"] != "") {
                if (chk_mail($_POST["email"])) {
                    if (isset($_POST["password"]) && $_POST["password"] != "") {
                        if (chk_password($_POST["password"])) {
                            $userModel = new UserModel();
                            $user = $userModel->where('email', $_POST["email"])->first();

                            if (is_null($user)) {
                                $res[2] = "Invalid Email or Password";
                            } else {
                                $pwd_verify = password_verify($_POST["password"], $user['password']);

                                if (!$pwd_verify) {
                                    $res[2] = "Invalid Email or Password";
                                } else {

                                    $key = getenv('JWT_SECRET');
                                    $iat = time(); // current timestamp value

                                    $exp = $iat + 86400;

                                    $data =  [
                                        "email" => $user['email'],
                                        "first_name" => $user['first_name'],
                                        "last_name" => $user['last_name'],
                                        "post_code" => $user['post_code'],
                                        "contact_number" => $user['contact_number'],
                                        "hobbies" => $user['hobbies'],
                                        "gender" => $user['gender'],
                                        "images" => $user['images'],
                                        "city" => $user['city'],
                                        "created_at" => $user['created_at'],
                                        "updated_at" => $user['updated_at']
                                    ];

                                    $payload = array(
                                        "iss" => "Issuer of the JWT",
                                        "aud" => "Audience that the JWT",
                                        "sub" => "Subject of the JWT",
                                        "iat" => $iat, //Time the JWT issued at
                                        "exp" => $exp, // Expiration time of token
                                        "data" => $data
                                    );

                                    $session->set('userSession', $data);


                                    $token = JWT::encode($payload, $key, 'HS256');
                                    $res[1] = [
                                        'token' => $token
                                    ];

                                    $res[2] = "User Login Successfully";
                                    $res[0] = true;
                                }
                            }
                        } else {
                            $res[2] = "Password must be combination of Upper and Lower case letters, digits and special characters";
                        }
                    } else {
                        $res[2] = "Password must be provided";
                    }
                } else {
                    $res[2] = "Email must be a valid email address";
                }
            } else {
                $res[2] = "Email must be provided";
            }
        }

        echo send_data($res);
    }

    public function getUser($type = "0")
    {
        $res = [false, [], 'Invalid request'];
        $request = \Config\Services::request();
        $key = getenv('JWT_SECRET');
        $header = $request->getHeader("Authorization");
        $token = null;

        // extract the token from the header
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                $token = $matches[1];
            }
        }

        // check if token is null or empty
        if (is_null($token) || empty($token)) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            $res[2] = "Access Denied";
        }

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));

            $userModel = new UserModel();
            $user = $userModel->where('email', $decoded->data->email)->select("md5(id) as id,email,first_name,last_name,images,city,post_code,contact_number,hobbies,gender")->first();
            $res[0] = true;
            $res[1] = $user;
            $res[2] = "Data Found";
        } catch (Exception $ex) {
            $response = service('response');
            $response->setBody('Access denied');
            $response->setStatusCode(401);
            $res[2] = "Access Denied";

        }

        if ($type == "1") {
            return $res[1];
        } else {
            echo send_data($res);
        }
    }

    public function updateProfile()
    {
        helper(['form']);

        $res = [false, [], 'Invalid request'];
        if (isset($_POST) && $_POST != "") {
            if (isset($_POST["first_name"]) && $_POST["first_name"] != "") {
                if (chk_string($_POST["first_name"])) {
                    if (isset($_POST["last_name"]) && $_POST["last_name"] != "") {
                        if (chk_string($_POST["last_name"])) {
                            $passwordCheck = false;
                            $newPassword = "";
                            $fileUploadArray = [];

                            if (isset($_POST["password"]) && $_POST["password"] != "") {
                                if (chk_password($_POST["password"])) {
                                    $passwordCheck = false;
                                    $newPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
                                } else {
                                    $res[2] = "Password must be combination of Upper and Lower case letters, digits and special characters";
                                }
                            }

                            if (!$passwordCheck) {
                                if (!empty($_FILES['file']) && $_FILES['file']['name'] != []) {
                                    foreach ($_FILES['file']['name'] as $key => $value) {
                                        $extt = pathinfo($value, PATHINFO_EXTENSION);
                                        if ($_FILES['file']['size'][$key] < 3000000) {
                                            if ($extt == "png" || $extt == "PNG" || $extt == "jpg" || $extt == "JPG" || $extt == "jpeg" || $extt == "JPEG") {
                                                $attachment = time() . strGenerate(5) . '.' .  $extt;
                                                if (move_uploaded_file($_FILES['file']['tmp_name'][$key], "uploads/" . $attachment)) {
                                                    $fileUploadArray[] = $attachment;
                                                }
                                            }
                                        }
                                    }
                                }

                                $userData = $this->getUser($type = "1");
                                if ($userData != []) {
                                    $updateData = [
                                        "first_name" => $_POST["first_name"],
                                        "last_name" => $_POST["last_name"],
                                        "hobbies" => $_POST["hobbies"],
                                        "gender" => $_POST["gender"],
                                        "contact_number" => $_POST["contact_number"],
                                        "post_code" => $_POST["post_code"],
                                        "city" => $_POST["city"],
                                    ];

                                    if ($newPassword != "") {
                                        $updateData["password"] = $newPassword;
                                    }
                                    if ($fileUploadArray != []) {
                                        $updateData["images"] = implode(",", $fileUploadArray);
                                    }
                                    $userModel = new UserModel();
                                    $userModel
                                        ->where('email', $userData['email'])
                                        ->set($updateData)
                                        ->update();

                                    $res[2] = "Profile Data Updated Successfully";
                                    $res[0] = true;
                                }
                            }
                        } else {
                            $res[2] = "Last name must be String";
                        }
                    } else {
                        $res[2] = "Last name must be provided";
                    }
                } else {
                    $res[2] = "First name must be String";
                }
            } else {
                $res[2] = "First name must be provided";
            }
        }

        echo send_data($res);
    }

    public function allUsers()
    {
        $db      = \Config\Database::connect();
        $res = [false, [], 'Invalid request'];

        
        if ($currentUser = $this->getUser("1")) {
            $arr = [];
            $req = $_POST;
            if ($req['start'] !== '') {
                if ($req['length'] !== '') {
                    $fields = [
                        'u.id',
                        'email',
                        "CONCAT(first_name, ' ', last_name)",
                        "contact_number",
                        'IF(gender = 0, "Male" , IF(gender = 1, "Female" , "Rather not Say"))',
                        'c.city',
                        'post_code',
                        'Hobbies',
                        'created_at',
                        'updated_at',
                        'u.id'
                    ];

                    $offset = $req['start'];
                    $limit = $req['length'];

                    $select = "u.id as id,email,CONCAT(first_name, ' ', last_name) as name,c.city as city,contact_number,post_code,images,hobbies,gender,created_at,updated_at";
                    $table = "users u, cities c";
                    $where = "u.city = c.id";

                    if(isset($currentUser)){
                        if (isset($currentUser['email'])) {
                            $currentUserEmail = $currentUser['email'];
                            $where .= " AND u.email != '$currentUserEmail'";
                        }
                    }
                    if (isset($req['selState']) && $req['selState'] != '') {
                        $state = $req['selState'];
                        $where .= " AND c.state = '$state'";
                    }

                    if (isset($req['selCity']) && $req['selCity'] != '') {
                        $city = $req['selCity'];
                        $where .= " AND u.city = '$city'";
                    }

                    if (isset($req['selGender']) && $req['selGender'] != '') {
                        $gender = $req['selGender'];
                        $where .= " AND u.gender = '$gender'";
                    }

                    if (isset($req['search']['value']) && $req['search']['value'] != "") {
                        $searchStr = trim($req['search']['value']);
                        foreach ($fields as $fldCnt => $item) {
                            $where .= ($fldCnt != 0 ? ' OR' : ' and (') . " $item like '%{$searchStr}%'" . ($fldCnt == (count($fields) - 1) ? ' ' : '');
                        }

                        $where .= ' )';
                    }

                    if (isset($req['order'])) {
                        $order = $req['order'][0]['dir'];
                        $where .= " order by {$fields[$req['order'][0]['column']]} $order";
                    } else {
                        $where .= " order by id desc";
                    }

                    $chkCNT = $db->query("Select  " . $select . " FROM " . $table . " Where " . $where)->getNumRows();

                    $where .= " LIMIT $limit OFFSET $offset";

                    $userdata = $db->query("Select " . $select . " FROM " . $table . " Where " . $where)->getResultArray();
                    $idOrder = 0;
                    if ($req['order'][0]['column'] == 0) {
                        if ($req['order'][0]['dir'] == 'desc') {
                            $idOrder = 0;
                        } else {
                            $idOrder = 1;
                        }

                        $cnt = $idOrder == 0 ? $req['start'] + 1 : $chkCNT - $req['start'];
                    } else {
                        $cnt = 1;
                    }

                    // $balanceField = '';
                    foreach ($userdata as $i => $item) {

                        $arr[$i]['cnt'] = $cnt;
                        $arr[$i]['id'] = md5($item['id']);
                        $arr[$i]['name'] = $item['name'];
                        $arr[$i]['email'] = $item['email'];
                        $arr[$i]['contact_number'] = $item['contact_number'] == null ? '-' : $item['contact_number'];
                        $arr[$i]['post_code'] = $item['post_code'] == null ? '-' : $item['post_code'];
                        $arr[$i]['city'] = $item['city'];
                        $arr[$i]['hobbies'] = $item['hobbies'];
                        if ($item['gender'] != null && $item['gender'] != '') {
                            switch ($item['gender']) {
                                case '0':
                                    $arr[$i]['gender'] = 'Male';
                                    break;
                                case '1':
                                    $arr[$i]['gender'] = 'Female';
                                    break;
                                default:
                                    $arr[$i]['gender'] = 'Rather not Say';
                                    break;
                            }
                        } else {
                            $arr[$i]['gender'] = '-';
                        }
                        $arr[$i]['images'] = $item['images'];
                        $arr[$i]['created_at'] = $item['created_at'];
                        $arr[$i]['updated_at'] = $item['updated_at'];

                        $cnt = $idOrder == 0 ? $cnt += 1 : $cnt -= 1;
                    }
                    $res[0] = true;
                    $res[1]['data'] = $arr;
                    $res[1]['draw'] = (isset($req['draw']) && $req['draw'] != '' ? $req['draw'] : 2);
                    $res[1]['recordsTotal'] = $chkCNT;
                    $res[1]['recordsFiltered'] = $chkCNT;
                    $res[2] = '';
                }
            }
        } else {
            $res[2] = "Session Expired";
        }

        echo send_data($res);
    }

    public function deleteUser(){


        $res = [false, [], 'Invalid request'];
        if (isset($_POST['id'])) {

            $userModel = new UserModel();
            $delete = $userModel->where('md5(id)',$_POST['id'])->delete();
            if ($delete) {
                $res[0] = true;
                $res[2] = "User Deleted Successfully";
            }
        }

        echo send_data($res);

    }
}
