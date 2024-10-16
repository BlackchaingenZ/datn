<?php
if (!defined('_INCODE')) die('Access Deined...');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function layout($layoutName='header', $dir='', $data = []){

    if(!empty($dir)) {
        $dir = '/'.$dir;
    }

    if (file_exists(_WEB_PATH_TEMPLATE.$dir.'/layouts/'.$layoutName.'.php')){
        require_once _WEB_PATH_TEMPLATE.$dir.'/layouts/'.$layoutName.'.php';
    }
}

function sendMail($to, $subject, $content) {
    //Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'phongtrothaonguyen@gmail.com';                     //SMTP username
        $mail->Password   = 'fjrbtpylmaeaskzt';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('phongtrothaonguyen@gmail.com', 'Phòng Trọ Thảo Nguyên');
        $mail->addAddress($to, 'Quý khách');   

        //Content
        $mail->isHTML(true);     
        $mail -> CharSet = 'UTF-8';                             //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;

        return  $mail->send();
    
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

//Kiểm tra phương thức POST
function isPost(){
    if ($_SERVER['REQUEST_METHOD']=='POST'){
        return true;
    }

    return false;
}

//Kiểm tra phương thức GET
function isGet(){
    if ($_SERVER['REQUEST_METHOD']=='GET'){
        return true;
    }

    return false;
}

//Lấy giá trị phương thức POST, GET
function getBody(){

    $bodyArr = [];

    if (isGet()){
        //Xử lý chuỗi trước khi hiển thị ra
        //return $_GET;
        /*
         * Đọc key của mảng $_GET
         *
         * */
        if (!empty($_GET)){
            foreach ($_GET as $key=>$value){
                $key = strip_tags($key);
                if (is_array($value)){
                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }else{
                    $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

            }
        }

    }

    if (isPost()){
        if (!empty($_POST)){
            foreach ($_POST as $key=>$value){
                $key = strip_tags($key);
                if (is_array($value)){
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                }else{
                    $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }

            }
        }
    }

    return $bodyArr;
}

//Kiểm tra email
function isEmail($email){
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

//Kiểm tra số nguyên
function isNumberInt($number, $range=[]){
    /*
     * $range = ['min_range'=>1, 'max_range'=>20];
     *
     * */
    if (!empty($range)){
        $options = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT, $options);
    }else{
        $checkNumber = filter_var($number, FILTER_VALIDATE_INT);
    }

    return $checkNumber;

}

//Kiểm tra số thực
function isNumberFloat($number, $range=[]){
    /*
     * $range = ['min_range'=>1, 'max_range'=>20];
     *
     * */
    if (!empty($range)){
        $options = ['options'=>$range];
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    }else{
        $checkNumber = filter_var($number, FILTER_VALIDATE_FLOAT);
    }

    return $checkNumber;

}


//Hàm tạo thông báo
function getMsg($msg, $type = 'suc') {
    if (!empty($msg)) {
        echo '<div class="' . $type . '">';
        if ($type === 'suc') {
            ?>
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/check.png" alt="">
            <?php
        } elseif ($type === 'err') {
            ?>
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/error.pngg" alt="">
            <?php
        }
        echo $msg;
        echo '</div>';
    }
}

//Hàm chuyển hướng
function redirect($path='index.php'){
    $url = _WEB_HOST_ROOT.'/'.$path;
    header("Location: $url");
    exit;
}

//Hàm thông báo lỗi
function form_error($fieldName, $errors, $beforeHtml='', $afterHtml=''){
    return (!empty($errors[$fieldName]))?$beforeHtml.reset($errors[$fieldName]).$afterHtml:null;
}

//Hàm hiển thị dữ liệu cũ
function old($fieldName, $oldData, $default=null){
    return (!empty($oldData[$fieldName]))?$oldData[$fieldName]:$default;
}

//Kiểm tra trạng thái đăng nhập của Admin
function isLogin(){
    $checkLogin = false;
    if (getSession('loginToken')){
        $tokenLogin = getSession('loginToken');

        $queryToken = firstRaw("SELECT user_id FROM login_token WHERE token='$tokenLogin'");

        if (!empty($queryToken)){
            //$checkLogin = true;
            $checkLogin = $queryToken;
        }else{
            removeSession('loginToken');
        }
    }

    return $checkLogin;
}

//Lấy thông tin user
function getUserInfo($user_id){
    $info = firstRaw("SELECT * FROM users WHERE id=$user_id");
    return $info;
}




// GetLink
function getLinkAdmin($module, $action='', $param= []) {
    $url = _WEB_HOST_ROOT;
    $url = $url.'?module='.$module;
    if(!empty($action)) {
        $url = $url.'&action='.$action;
    }

    if(!empty($param)) {
        $paramString = http_build_query($param);
        $url = $url.'&'.$paramString;
    }
    return $url;
}

// Format Date
function getDateFormat($strDate, $format) {
    $dateObject = date_create($strDate);
    if(!empty($dateObject)) {
        return date_format($dateObject, $format);
    }
    return false;
}


// Check font-awesome
function isFontIcon($input) {
    if(strpos($input, '<i class="') != false ) {
        return true;
    }
    return false;
}
function getContractStatus($endDate) {
    $currentDate = new DateTime();
    $contractEndDate = new DateTime($endDate);
    $interval = $currentDate->diff($contractEndDate); // Tính khoảng cách giữa 2 ngày
    $daysLeft = (int)$interval->format('%R%a'); // chuyển khoảng cách ngày thành số ngày

    if ($daysLeft < 0) {
        return "Đã hết hạn";
    } elseif ($daysLeft > 0 && $daysLeft <= 30) {
        return "Sắp hết hạn";
    } else {
        return "Trong thời hạn";
    }
}





