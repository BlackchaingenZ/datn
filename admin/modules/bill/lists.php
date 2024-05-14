<?php

if(!defined('_INCODE'))
die('Access denied...');

$data = [
    'pageTitle' => 'Danh sách hóa đơn'
];

layout('header', 'admin', $data);
layout('breadcrumb', 'admin', $data);


$allService = getRaw("SELECT * FROM services");

// echo '<pre>';
// print_r($allService);
// echo '</pre>';
// die;


// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody('get');
    

    // Xử lý lọc theo từ khóa
    if(!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        
        if(!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        }else {
            $operator = 'WHERE';
        }

        $filter .= " $operator tenphong LIKE '%$keyword%'";

    }

    //Xử lý lọc Status
    if(!empty($body['status'])) {
        $status = $body['status'];

        if($status == 2) {
            $statusSql = 0;
        } else {
            $statusSql = $status;
        }

        if(!empty($filter) && strpos($filter, 'WHERE') >= 0) {
            $operator = 'AND';
        }else {
            $operator = 'WHERE';
        }
        
        $filter .= "$operator trangthai=$statusSql";
    }
}

/// Xử lý phân trang
$allBill = getRows("SELECT id FROM bill $filter");
$perPage = _PER_PAGE; // Mỗi trang có 3 bản ghi
$maxPage = ceil($allBill / $perPage);

// 3. Xử lý số trang dựa vào phương thức GET
if(!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if($page < 1 and $page > $maxPage) {
        $page = 1;
    }
}else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;
$listAllBill = getRaw("SELECT *, room.tenphong, tenant.zalo FROM bill 
INNER JOIN room ON bill.room_id = room.id INNER JOIN tenant ON bill.tenant_id = tenant.id $filter LIMIT $offset, $perPage");

// Xử lý query string tìm kiếm với phân trang
$queryString = null;
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = str_replace('module=bill','', $queryString);
    $queryString = str_replace('&page='.$page, '', $queryString);
    $queryString = trim($queryString, '&');
    $queryString = '&'.$queryString;
}

$msg =getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>

<?php
layout('navbar', 'admin', $data);
?>

<div class="container-fluid">

        <div id="MessageFlash">          
                <?php getMsg($msg, $msgType);?>          
        </div>

    <!-- Tìm kiếm -->
    <div class="box-content">
            <!-- Tìm kiếm , Lọc dưz liệu -->
        <form action="" method="get">
            <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <select name="status" id="" class="form-select">
                        <option value="0">Chọn trạng thái</option>
                        <option value="1" <?php echo (!empty($status) && $status==1) ? 'selected':false; ?>>Đang ở</option>
                        <option value="2" <?php echo (!empty($status) && $status==2) ? 'selected':false; ?>>Đang trống</option>
                    </select>
                </div>
            </div>

            <div class="col-4">
                    <input style="height: 50px" type="search" name="keyword" class="form-control" placeholder="Nhập tên khách cần tìm" value="<?php echo (!empty($keyword))? $keyword:false; ?>" >
            </div>

            <div class="col">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-search"></i></button>
            </div>
            </div>
            <input type="hidden" name="module" value="room">
        </form>

        <form action="" method="POST" class="mt-3">
    <div>
  
</div>
            <a href="<?php echo getLinkAdmin('bill', 'add') ?>" class="btn btn-success" style="color: #fff"><i class="fa fa-plus"></i> Thêm</a>
            <a href="<?php echo getLinkAdmin('bill', 'lists'); ?>" class="btn btn-secondary"><i class="fa fa-history"></i> Refresh</a>
            <a href="<?php echo getLinkAdmin('bill', 'import'); ?>" class="btn btn-success minn"><i class="fa fa-upload"></i> Import</a>
            <a href="<?php echo getLinkAdmin('bill', 'export'); ?>" class="btn btn-success minn"><i class="fa fa-save"></i> Xuất Excel</a>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th width="3%" rowspan="2"></th>
                        <th rowspan="2">Tên phòng</th>
                        <th colspan="2">Tiền phòng</th>
                        <th colspan="3">Tiền điện (1.700đ)</th>
                        <th colspan="3">Tiền nước (20.000đ)</th>
                        <th colspan="2">Tiền rác (10.000đ)</th>
                        <th colspan="2">Tiền Wifi (50.000đ)</th>
                        <th rowspan="2">Nợ cũ</th>
                        <th rowspan="2">Tổng cộng</th>
                        <th rowspan="2">Ngày lập</th>
                        <th rowspan="2">Trạng thái</th>
                        <th rowspan="2">Thao tác</th>
                    </tr>
                    <tr>
                        <th>Số tháng</th>
                        <th>Tiền phòng</th>
                        <th>Số cũ</th>
                        <th>Số mới</th>
                        <th>Thành tiền</th>
                        <th>Số cũ</th>
                        <th>Số mới</th>
                        <th>Thành tiền</th>
                        <th>Người</th>
                        <th>Thành tiền</th>
                        <th>Tháng</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody id="roomData">
        
                    <?php
                        if(!empty($listAllBill)):
                            $count = 0; // Hiển thi số thứ tự
                            foreach($listAllBill as $item):
                                $count ++;
        
                    ?>
                     <tr>
                        <td>
                            <div class="image__bill">
                                <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/bill-icon.svg" class="image__bill-img" alt="">
                            </div>
                        </td>
                        <td><?php echo $item['tenphong']; ?></td>
                        <td><?php echo $item['chuky']; ?></td>
                        <td><b><?php echo number_format($item['tienphong'], 0, ',', '.') ?> đ</b></td>
                        <td><?php echo $item['sodiencu']; ?></td>
                        <td><?php echo $item['sodienmoi']; ?></td>
                        <td><b><?php echo number_format($item['tiendien'], 0, ',', '.') ?> đ</b></td>
                        <td><?php echo $item['sonuoccu']; ?></td>
                        <td><?php echo $item['sonuocmoi']; ?></td>
                        <td><b><?php echo number_format($item['tiennuoc'], 0, ',', '.') ?> đ</b></td>
                        <td><?php echo $item['songuoi']; ?></td>
                        <td><b><?php echo number_format($item['tienrac'], 0, ',', '.') ?> đ</b></td>
                        <td><?php echo $item['chuky']; ?></td>
                        <td><b><?php echo number_format($item['tienmang'], 0, ',', '.') ?> đ</b></td>
                        <td><b><?php echo number_format($item['nocu'], 0, ',', '.') ?> đ</b></td>
                        <td style="color: #db2828"><b><?php echo number_format($item['tongtien'], 0, ',', '.') ?> đ</b></td>
                        <td><?php echo $item['create_at']; ?></td>
                        <td>
                            <?php 
                                 echo $item['trangthaihoadon'] == 1 ? '<span class="btn-kyhopdong-suc">Đã thanh toán</span>':'<span class="btn-kyhopdong-err">Chưa thanh toán</span>';
                            ?>
                        </td>
                        <td>
                            <a target="_blank" href="<?php echo $item['zalo'] ?>"><img style="width: 30px; height: 30px" src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/zalo.jpg" alt=""></a>
                            <a title="Xem hợp đồng" href="<?php echo getLinkAdmin('contract','view',['id' => $item['id']]); ?>" class="btn btn-primary btn-sm" ><i class="nav-icon fas fa-solid fa-eye"></i> </a>
                            <a title="In hợp đồng" target="_blank" href="<?php echo getLinkAdmin('contract','print',['id' => $item['id']]) ?>" class="btn btn-secondary btn-sm" ><i class="fa fa-print"></i> </a>
                            <a href="<?php echo getLinkAdmin('contract','edit',['id' => $item['id']]); ?>" class="btn btn-warning btn-sm" ><i class="fa fa-edit"></i> </a>
                            <a href="<?php echo getLinkAdmin('contract','delete',['id' => $item['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa không ?')"><i class="fa fa-trash"></i> </a>
                        </td>
                    </tr>                
                         
                    <?php endforeach; else: ?>
                        <tr>
                            <td colspan="19">
                                <div class="alert alert-danger text-center">Không có dữ liệu hóa đơn</div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <nav aria-label="Page navigation example" class="d-flex justify-content-center">
            <ul class="pagination pagination-sm">
                <?php
                    if($page > 1) {
                        $prePage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'/?module=bill'.$queryString. '&page='.$prePage.'">Pre</a></li>';
                    }
                ?>

                <?php 
                    // Giới hạn số trang
                    $begin = $page - 2;
                    $end = $page + 2;
                    if($begin < 1) {
                        $begin = 1;
                    }
                    if($end > $maxPage) {
                        $end = $maxPage;
                    }
                    for($index = $begin; $index <= $end; $index++){  ?>
                <li class="page-item <?php echo ($index == $page) ? 'active' : false; ?> ">
                    <a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN.'?module=bill'.$queryString.'&page='.$index;  ?>"> <?php echo $index;?> </a>
                </li>
                <?php  } ?>
                
                <?php
                    if($page < $maxPage) {
                        $nextPage = $page + 1;
                        echo '<li class="page-item"><a class="page-link" href="'._WEB_HOST_ROOT_ADMIN.'?module=bill'.$queryString.'&page='.$nextPage.'">Next</a></li>';
                    }
                ?>
            </ul>
        </nav>
    </div> 

</div>

<?php

layout('footer', 'admin');
?>

<script>
    function toggle(__this){
       let isChecked = __this.checked;
       let checkbox = document.querySelectorAll('input[name="records[]"]');
        for (let index = 0; index < checkbox.length; index++) {
            checkbox[index].checked = isChecked
        }
    }
</script>