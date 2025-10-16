<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/assets/css/user/verify.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    
    <title>Verify email</title>
</head>

<body>
    <div class="container height-100 d-flex justify-content-center align-items-center">
        <div class="position-relative">

            <form method="post"  action="<?php echo \Uri::create('auth/verify'); ?>">
                <div class="card p-2 text-center">
                <h6>Please enter the one time password <br> to verify your account</h6>
                <div> <span>A code has been sent to </span> <strong>your email</strong> </div>
                <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2">
                    <input class="m-2 text-center form-control rounded" type="text" id="first" maxlength="1" />
                    <input class="m-2 text-center form-control rounded" type="text" id="second" maxlength="1" />
                    <input class="m-2 text-center form-control rounded" type="text" id="third" maxlength="1" />
                    <input class="m-2 text-center form-control rounded" type="text" id="fourth" maxlength="1" />
                    <input class="m-2 text-center form-control rounded" type="text" id="fifth" maxlength="1" />
                    <input class="m-2 text-center form-control rounded" type="text" id="sixth" maxlength="1" />
                </div>
                <div class="mt-4"> <button class="btn btn-danger px-4 validate">Validate</button> </div>
            </div>
            </form>

            <div class="card-2">
                <div class="content d-flex justify-content-center align-items-center"> <span>Didn't get the code</span>
                    <a href="#" class="text-decoration-none ms-3">Resend(1/3)</a> </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="/assets/js/verify.js"></script>
</body>

</html>