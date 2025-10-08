<?php
class Controller_Admin_Home extends Controller
{
      public function action_index()
      {
            $main_content = View::forge('admin/home');

            return Response::forge(View::forge('layouts/admin/base', [
                  'main_content' => $main_content,
                  'custom_css' => 'assets/css/admin/home.css'
            ]));
      }
}