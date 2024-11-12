<?php
if (!defined('_INCODE')) die('Access Deined...');
/*
 * File này chứa chức năng đăng nhập
 * */

$data = [
    'pageTitle' => 'Forgot password'
];

layout('header-login','admin', $data);

//Xử lý đăng nhập



$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$old = getFlashData('old');
?>

<body id="body-login">
        <div id="MessageFlash">
            <?php getMsg($msg, $msgType);?> 
        </div>
    <div class="col-3" style="margin: 20px auto;">
        <div class="login">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/logomain.png" class="logo-login" alt="">
        <p class="text-center title-login">Khôi phục lại mật khẩu</p>
        <p class="text-center" style="color: #000; margin-bottom: 20px">Hãy nhập địa chỉ email đã đăng ký, chúng tôi sẽ gửi link khôi phục. Quá trình này có thể mất vài phút.</p>



        <form action="" method="post">
            
            <div class="form-group">
                <label for="">Email</label> <br />
                <input type="email" name="email" class="" placeholder="Email" value="<?php echo old('email', $old); ?>">
            </div>
            <button type="submit" class="btn-login">Gửi yêu cầu</button>
            <button type="button" class="btn-login" onclick="window.location.href='http://localhost:85/datn/?module=auth&action=login';">Trở về trang trước</button>

        </form>
        </div>
    </div>
</body>

<?php

layout('footer-login', 'admin');

