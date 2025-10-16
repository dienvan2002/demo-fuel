<?php

// Test sending email using FuelPHP Email package with SMTP mailhog

namespace Fuel\Tasks;

use Fuel\Core\Package;

class Testmail
{
    public static function run()
    {
        self::sendmail('demofuel@gmail.com', 'Demo FuelPHP'  );
        // // \Package::load("email");
        // $email = \Email::forge(
        //     array(
        //         'driver' => 'smtp',
        //     )
        // );
        // $email1 = 'receiveeer@example.co.uk';
        // $name = 'duy vo';
        // $email->from('demofuel@gmail.com', 'Demo FuelPHP');
        // $email->to($email1, $name);
        // $email->subject('This is a test email from FuelPHP');
        // $email->body('thanh cong');
        // try {
        //     $email->send();
        // } catch (\EmailValidationFailedException $e) {
        //     echo $e->getMessage();
        //     echo 'loi valide email';
        // } catch (\EmailSendingFailedException $e) {
        //     echo $e->getMessage();
        //     echo 'khong gui duoc';
        // }
    }
    public static function sendmail($email1, $name){
		 $email = \Email::forge(
            array(
                'driver' => 'smtp',
            )
        );
        $email->from('demofuel@gmail.com', 'Nguyễn Duy Diện');
        $email->to($email1, $name);
        $email->subject('Code xác nhận đăng ký tài khoản');
        $email->body('Mã xác nhận của bạn là: 123456');
        try {
            $email->send();
        } catch (\EmailValidationFailedException $e) {
            echo $e->getMessage();
            echo 'Lỗi định dạng email';
        } catch (\EmailSendingFailedException $e) {
            echo $e->getMessage();
            echo 'Không gửi được email';
        }
    	
	}

}
