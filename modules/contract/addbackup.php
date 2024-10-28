<?php

if (!defined('_INCODE'))
    die('Access denied...');


$data = [
    'pageTitle' => 'Thêm hợp đồng mới'
];

layout('header', 'admin', $data);
layout('breadcrumb', 'admin', $data);

$allRoom = getRaw("SELECT id, tenphong, soluong FROM room ORDER BY tenphong");
//kiểm tra khách đã làm hợp đồng chưa,nếu rồi thì không hiện
$allTenant = getRaw("
    SELECT tenant.id, tenant.tenkhach, room.tenphong 
    FROM tenant 
    INNER JOIN room ON room.id = tenant.room_id 
    LEFT JOIN contract ON contract.tenant_id = tenant.id OR contract.tenant_id_2 = tenant.id 
    WHERE contract.tenant_id IS NULL AND contract.tenant_id_2 IS NULL
    ORDER BY room.tenphong
");


$allArea = getRaw("SELECT id, tenkhuvuc FROM area ORDER BY tenkhuvuc");
$allRoomId = getRaw("SELECT room_id FROM contract");
$roomsByArea = [];
foreach ($allRoom as $room) {
    $areaIds = getRaw("SELECT area_id FROM area_room WHERE room_id = " . $room['id']);
    foreach ($areaIds as $area) {
        $roomsByArea[$area['area_id']][] = $room;
    }
}
// Xử lý thêm hợp đồng
if (isPost()) {
    // Validate form
    $body = getBody(); // lấy tất cả dữ liệu trong form
    $errors = [];  // mảng lưu trữ các lỗi

    //Valide họ tên: Bắt buộc phải nhập, >=5 ký tự
    if (empty(trim($body['room_id']))) {
        $errors['room_id']['required'] = '** Bạn chưa chọn phòng lập hợp đồng!';
    } else {
        // Kiểm tra trùng phòng lập hợp đồng
        $dataRoom = trim($body['room_id']);
        foreach ($allRoomId as $item) {
            if ($dataRoom == $item['room_id']) {
                $errors['room_id']['exists'] = '** Phòng này đã lập hợp đồng';
                break;
            }
        }
    }
    if (empty(trim($body['tenant_id'])) && empty(trim($body['tenant_id_2']))) {
        $errors['tenant_id']['required'] = '** Hãy chọn ít nhất 1 người thuê!';
    }



    if (empty(trim($body['ngaylaphopdong']))) {
        $errors['ngaylaphopdong']['required'] = '** Bạn chưa nhập ngày lập hợp đồng!';
    }


    // Kiểm tra mảng error
    if (empty($errors)) {
        // không có lỗi nào
        $dataInsert = [
            'room_id' => $body['room_id'],
            'tenant_id' => $body['tenant_id'],
            'tenant_id_2' => empty(trim($body['tenant_id_2'])) ? null : $body['tenant_id_2'], // Sử dụng null nếu không có giá trị
            'tinhtrangcoc' => $body['tinhtrangcoc'],
            'ngaylaphopdong' => $body['ngaylaphopdong'],
            'ngayvao' => $body['ngayvao'],
            'ngayra' => $body['ngayra'],
            'create_at' => date('Y-m-d H:i:s'),
        ];


        $insertStatus = insert('contract', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm thông tin hợp đồng mới thành công');
            setFlashData('msg_type', 'suc');
            redirect('?module=contract');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'err');
            redirect('?module=contract&action=add');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra chính xác thông tin nhập vào');
        setFlashData('msg_type', 'err');
        setFlashData('errors', $errors);
        setFlashData('old', $body);  // giữ lại các trường dữ liệu hợp lê khi nhập vào
        redirect('?module=contract&action=add');
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<?php
layout('navbar', 'admin', $data);
?>

<div class="container">
    <div id="MessageFlash">
        <?php getMsg($msg, $msgType); ?>
    </div>

    <div class="box-content">
        <form action="" method="post" class="row">
            <div class="col-5">
                <div class="form-group">
                    <label for="">Chọn khu vực <span style="color: red">*</span></label>
                    <select name="area_id" id="area-select" class="form-select">
                        <option value="">Chọn khu vực</option>
                        <?php
                        if (!empty($allArea)) {
                            foreach ($allArea as $item) {
                        ?>
                                <option value="<?php echo $item['id'] ?>" <?php echo (!empty($areaId) && $areaId == $item['id']) ? 'selected' : '' ?>><?php echo $item['tenkhuvuc'] ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('area_id', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Chọn phòng lập hợp đồng <span style="color: red">*</span></label>
                    <select name="room_id" id="room-select" class="form-select">
                        <option value="">Chọn phòng</option>
                        <!-- Danh sách phòng sẽ được cập nhật qua JavaScript -->
                    </select>
                    <?php echo form_error('room_id', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Người thuê 1 <span style="color: red">*</span></label>
                    <select name="tenant_id" id="" class="form-select">
                        <option value="">Chọn người thuê</option>
                        <?php
                        if (!empty($allTenant)) {
                            foreach ($allTenant as $item) {
                        ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo (!empty($tenantId) && $tenantId == $item['id']) ? 'selected' : ''; ?>>
                                    <?php echo $item['tenkhach']; ?> - <?php echo $item['tenphong']; ?>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('tenant_id', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Người thuê 2</label>
                    <select name="tenant_id_2" id="" class="form-select">
                        <option value="">Trống</option> <!-- Tùy chọn không có người thuê -->
                        <?php
                        if (!empty($allTenant)) {
                            foreach ($allTenant as $item) {
                        ?>
                                <option value="<?php echo $item['id']; ?>" <?php echo (!empty($tenantId2) && $tenantId2 == $item['id']) ? 'selected' : ''; ?>>
                                    <?php echo $item['tenkhach']; ?> - <?php echo $item['tenphong']; ?>
                                </option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    <?php echo form_error('tenant_id_2', $errors, '<span class="error">', '</span>'); ?>
                </div>





                <div class="form-group">
                    <label for="">Ngày lập hợp đồng <span style="color: red">*</span></label>
                    <input type="date" name="ngaylaphopdong" id="" class="form-control" value="<?php echo old('ngaylaphopdong', $old); ?>">
                    <?php echo form_error('ngaylaphopdong', $errors, '<span class="error">', '</span>'); ?>
                </div>
            </div>

            <div class="col-5">
                <div class="form-group">
                    <label for="">Ngày vào ở <span style="color: red">*</span></label>
                    <input type="date" name="ngayvao" id="" class="form-control" value="<?php echo old('ngayvao', $old); ?>">
                    <?php echo form_error('ngayvao', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Ngày hết hạn hợp đồng <span style="color: red">*</span></label>
                    <input type="date" name="ngayra" id="" class="form-control" value="<?php echo old('ngayra', $old); ?>">
                    <?php echo form_error('ngayra', $errors, '<span class="error">', '</span>'); ?>
                </div>

                <div class="form-group">
                    <label for="">Tình trạng cọc</label>
                    <select name="tinhtrangcoc" class="form-select">
                        <option value="">Chọn trạng thái</option>
                        <option value="0">Chưa thu tiền</option>
                        <option value="1">Đã thu tiền</option>
                    </select>
                </div>

            </div>
            <div class="from-group">
                <div class="btn-row">
                    <a style="margin-right: 20px" href="<?php echo getLinkAdmin('contract') ?>" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Quay lại </a>
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-edit"></i> Thêm hợp đồng</button>
                </div>
            </div>
    </div>
    </form>
</div>
</div>

<script>
    const roomsByArea = <?php echo json_encode($roomsByArea); ?>; // Chuyển đổi mảng PHP sang JS
    const areaSelect = document.getElementById('area-select');
    const roomSelect = document.getElementById('room-select');

    areaSelect.addEventListener('change', function() {
        const areaId = this.value;
        roomSelect.innerHTML = '<option value="">Chọn phòng</option>'; // Reset danh sách phòng

        if (areaId && roomsByArea[areaId]) {
            roomsByArea[areaId].forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.tenphong;
                roomSelect.appendChild(option);
            });
        }
    });
</script>


<?php
layout('footer', 'admin');