<?php

class Controller_Admin_User extends Controller_Base
{
    public function before()
    {
        parent::before();
        $this->require_admin();
    }

    public function action_index()
    {
        $this->require_permission('users', 'read');
        
        $users = Service_User::getAll(array(
            'order_by' => 'created_at',
            'order_dir' => 'desc'
        ));
        
        $view = View::forge('admin/user/index', [
            'users' => $users
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/users.css'
        ]));
    }

    public function action_create()
    {
        $this->require_permission('users', 'create');
        
        $success_message = null;
        $error_messages = null;
        $group_options = Service_User::getGroupOptions();
        $gender_options = Service_User::getGenderOptions();

        if (Input::method() === 'POST') {
            $result = Service_User::create(Input::post());

            if ($result['success']) {
                Session::set_flash('success', $result['message']);
                Response::redirect('admin/user');
                exit();
            } else {
                $error_messages = $result['errors'];
            }
        }

        $main_content = View::forge('admin/user/create', [
            'success_message' => $success_message,
            'error_messages' => $error_messages,
            'group_options' => $group_options,
            'gender_options' => $gender_options
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $main_content,
            'custom_css' => 'assets/css/admin/users.css'
        ]));
    }

    public function action_edit($id)
    {
        $this->require_permission('users', 'update');
        
        $user = Service_User::getById($id);
        if (!$user) {
            Session::set_flash('error', 'Không tìm thấy người dùng');
            Response::redirect('admin/user');
            exit();
        }

        $success_message = null;
        $error_messages = null;
        $group_options = Service_User::getGroupOptions();
        $gender_options = Service_User::getGenderOptions();

        if (Input::method() === 'POST') {
            $result = Service_User::update($id, Input::post());

            if ($result['success']) {
                Session::set_flash('success', $result['message']);
                Response::redirect('admin/user');
                exit();
            } else {
                $error_messages = $result['errors'];
            }
        }

        $view = View::forge('admin/user/edit', [
            'user' => $user,
            'success_message' => $success_message,
            'error_messages' => $error_messages,
            'group_options' => $group_options,
            'gender_options' => $gender_options
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/users.css'
        ]));
    }

    public function action_delete($id)
    {
        $this->require_permission('users', 'delete');
        
        $user = Service_User::getById($id);
        if (!$user) {
            Session::set_flash('error', 'Không tìm thấy người dùng');
            Response::redirect('admin/user');
            exit();
        }

        $result = Service_User::delete($id);
        
        if ($result['success']) {
            Session::set_flash('success', $result['message']);
        } else {
            Session::set_flash('error', $result['message']);
        }
        
        Response::redirect('admin/user');
        exit();
    }

    public function action_show($id)
    {
        $this->require_permission('users', 'read');
        
        $user = Service_User::getById($id);
        if (!$user) {
            Session::set_flash('error', 'Không tìm thấy người dùng');
            Response::redirect('admin/user');
            exit();
        }

        $view = View::forge('admin/user/show', [
            'user' => $user
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/users.css'
        ]));
    }

    public function action_search()
    {
        $this->require_permission('users', 'read');
        
        $keyword = Input::get('keyword', '');
        $users = Service_User::search($keyword, array(
            'order_by' => 'created_at',
            'order_dir' => 'desc'
        ));
        
        $view = View::forge('admin/user/index', [
            'users' => $users,
            'keyword' => $keyword
        ]);

        return Response::forge(View::forge('layouts/admin/base', [
            'main_content' => $view,
            'custom_css' => 'assets/css/admin/users.css'
        ]));
    }
}
