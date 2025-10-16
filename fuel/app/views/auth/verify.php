<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/user/verify.css">
    
    <title>Verify email</title>
</head>

<body>
    <div class="container height-100 d-flex justify-content-center align-items-center">
        <div class="position-relative">

            <form method="post" action="<?php echo \Uri::create('auth/verify'); ?>">
                <div class="card p-2 text-center">
                <h6>Vui lòng nhập mã xác thực <br> để hoàn tất đăng ký</h6>
                <div> <span>Mã xác thực đã được gửi đến </span> <strong><?php echo isset($email) ? $email : 'email của bạn'; ?></strong> </div>
                
                <?php if (isset($error_message) && $error_message): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success_message) && $success_message): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                
                <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="first" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="second" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="third" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="fourth" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="fifth" maxlength="1" required />
                    <input class="m-2 text-center form-control rounded" type="text" name="code[]" id="sixth" maxlength="1" required />
                </div>
                <div class="mt-4"> 
                    <button type="submit" class="btn btn-danger px-4">Xác thực</button> 
                </div>
            </div>
            </form>

            <div class="card-2">
                <div class="content d-flex justify-content-center align-items-center"> <span>Didn't get the code</span>
                    <a href="#" class="text-decoration-none ms-3">Resend(1/3)</a> </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/verify.js"></script>
</body>

</html>