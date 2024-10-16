<?php
if(!isLogin()) {
    redirect('?module=auth&action=login');
} 

$data = [
    'pageTitle' => 'Màn hình chính'
];

$userId = isLogin()['user_id'];
$userDetail = getUserInfo($userId);  
$roomId = $userDetail['room_id'];


if($userDetail['group_id'] == 7) {
    layout('header', 'admin', $data);
    layout('breadcrumb', 'admin', $data);
} else {
    layout('header-tenant', 'admin', $data);
    layout('sidebar', 'admin', $data);
}




?>

<?php
if($userDetail['group_id'] == 7) {
    layout('navbar', 'admin', $data);
}
?>
<?php 
if($userDetail['group_id'] == 7) {
    ?>
    <?php
}

layout('footer', 'admin');