<?php


namespace App\Controllers;

use App\Models\CityModel;
use App\Models\UserModel;

helper('fn');


class User extends BaseController
{
    public function getUser($email){
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->select("md5(id) as id,email,is_admin,first_name,last_name,images,city,post_code,contact_number,hobbies,gender")->first();
        return $user;
    }
    public function loads($view, $data = [])
    {
        $session = session();
        if ($userSession = $session->get('userSession')) {
            $data["userdata"] = $this->getUser($userSession['email']);
            return view('header', $data)
            . view($view, $data)
            . view('footer', $data);
        }else{
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $data["title"] = "Dashboard";
        return $this->loads("dashboard", $data);
    }

    public function profile()
    {
        $data = [];
        $data["title"] = "Profile";
        $cityModel = new CityModel();
        $cities = $cityModel->find();
        // dead($cities);
        $data["cities"] = $cities;
        return $this->loads("profile", $data);
    }

    
    public function users()
    {
        $data = [];
        $data["title"] = "Users";
        $cityModel = new CityModel();
        $cities = $cityModel->find();
        $states = $cityModel->distinct("state")->select("state")->orderBy('state', 'ASC')->find();
        $data["cities"] = $cities;
        $data["states"] = $states;
        return $this->loads("users", $data);
    }

    public function login()
    {
        $session = session();
        if ($userSession = $session->get('userSession')) {
            return redirect()->to('');
        } else {
            $data = [];
            $data["title"] = "Login";
            return view("login", $data);
        }
    }

    public function register()
    {
        $session = session();
        if ($userSession = $session->get('userSession')) {
            return redirect()->to('');
        } else {
            $data = [];
            $data["title"] = "Registration";
            return view("register", $data);
        }
    }

    public function logout()
    {
        $session = session();
        $session->remove('userSession');
        return redirect()->to('');
    }
}
