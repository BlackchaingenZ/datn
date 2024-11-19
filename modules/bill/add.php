<?php

if (!defined('_INCODE'))
    die('Access denied...');

$data = [
    'pageTitle' => 'Thêm hóa đơn mới'
];

layout('header', 'admin', $data);
layout('breadcrumb', 'admin', $data);

// Dịch vụ
$donGiaNuoc = firstRaw("SELECT giadichvu FROM services WHERE tendichvu = 'Tiền nước'");
$dongiaDien = firstRaw("SELECT giadichvu FROM services WHERE tendichvu = 'Tiền điện'");
$dongiaRac = firstRaw("SELECT giadichvu FROM services WHERE tendichvu = 'Tiền rác'");
$dongiaWifi = firstRaw("SELECT giadichvu FROM services WHERE tendichvu = 'Tiền Wifi'");

$allRoom = getRaw("
    SELECT 
        room.id, 
        tenphong, 
        cost.giathue, 
        soluong, 
        chuky, 
        room.ngayvao 
    FROM room 
    INNER JOIN contract ON contract.room_id = room.id
    INNER JOIN cost_room ON cost_room.room_id = room.id
    INNER JOIN cost ON cost.id = cost_room.cost_id
    ORDER BY tenphong
");
$allArea = getRaw("SELECT id, tenkhuvuc FROM area ORDER BY tenkhuvuc");
$roomsByArea = [];

foreach ($allRoom as $room) {
    $songayle = isset($_POST['songayle']) ? $_POST['songayle'] : 0;
    // Lấy giá trị chu kỳ từ input của người dùng
    $chuky = isset($_POST['chuky']) ? $_POST['chuky'] : 0;
    // Lấy số lượng người hiện tại trong phòng từ bảng tenant
    $soluong = getRaw("SELECT COUNT(*) AS soluong FROM tenant WHERE room_id = " . $room['id'])[0]['soluong'];

    // Truy vấn để lấy giá thuê từ bảng cost_room và cost
    $costData = getRaw(
        "
        SELECT c.giathue
        FROM cost_room cr
        JOIN cost c ON cr.cost_id = c.id
        WHERE cr.room_id = " . $room['id']
    );

    // Kiểm tra nếu có giá thuê, nếu không thì gán giá mặc định là 0
    $giaPhong = isset($costData[0]['giathue']) ? $costData[0]['giathue'] : 0;

    // Lấy thông tin khu vực của phòng
    $areaIds = getRaw("SELECT area_id FROM area_room WHERE room_id = " . $room['id']);
    foreach ($areaIds as $area) {
        // Thêm thông tin số người và giá thuê vào mỗi phòng theo khu vực
        $roomsByArea[$area['area_id']][] = [
            'id' => $room['id'],
            'tenphong' => $room['tenphong'],
            'soluong' => $soluong,
            'giathue' => $giaPhong, // Thêm giá thuê vào mảng
            'chuky' => $chuky,      // Thêm chu kỳ vào mảng
            'songayle' => $songayle // Thêm số ngày lẻ vào mảng
        ];
    }
}

// Xử lý thêm người dùng
if (isPost()) {
    // Validate form
    $body = getBody(); // lấy tất cả dữ liệu trong form
    $errors = [];  // mảng lưu trữ các lỗi

    // Kiểm tra mảng error
    if (empty($errors)) {
        // không có lỗi nào
        $dataInsert = [
            'room_id' => $body['room_id'],
            'mahoadon' => generateInvoiceCode(),
            'chuky' => $body['chuky'],
            'songayle' => $body['songayle'],
            'tienphong' => $body['tienphong'],
            'sodiencu' => $body['sodiencu'],
            'sodienmoi' => $body['sodienmoi'],
            'img_sodienmoi' => $body['img_sodienmoi'],
            'tiendien' => $body['tiendien'],
            'sonuoccu' => $body['sonuoccu'],
            'sonuocmoi' => $body['sonuocmoi'],
            'img_sonuocmoi' => $body['img_sonuocmoi'],
            'tiennuoc' => $body['tiennuoc'],
            'songuoi' => $body['soluong'],
            'tienrac' => $body['tienrac'],
            'tienmang' => $body['tienmang'],
            'nocu' => $body['nocu'],
            'tongtien' => $body['tongtien'],
            'sotienconthieu' => $body['tongtien'],
            'create_at' => date('Y-m-d H:i:s'),
        ];

        $insertStatus = insert('bill', $dataInsert);
        if ($insertStatus) {
            setFlashData('msg', 'Thêm thông tin hóa đơn thành công');
            setFlashData('msg_type', 'suc');
            redirect('?module=bill&action=bills');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'err');
            redirect('?module=bill&action=add');
        }
    } else {
        // Có lỗi xảy ra
        setFlashData('msg', 'Vui lòng kiểm tra chính xác thông tin nhập vào');
        setFlashData('msg_type', 'err');
        setFlashData('errors', $errors);
        setFlashData('old', $body);  // giữ lại các trường dữ liệu hợp lê khi nhập vào
        redirect('?module=bill&action=add');
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

    <div class="box-content2">
        <form action="" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <!-- hàng 1 -->
            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="">Chọn khu vực <span style="color: red">*</span></label>
                        <select name="area_id" id="area-select" class="form-select">
                            <option value="" disabled selected>Chọn khu vực</option>
                            <?php
                            if (!empty($allArea)) {
                                foreach ($allArea as $item) {
                            ?>
                                    <option value="<?php echo $item['id'] ?>"
                                        <?php echo (!empty($areaId) && $areaId == $item['id']) ? 'selected' : '' ?>>
                                        <?php echo $item['tenkhuvuc'] ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                        <?php echo form_error('area_id', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="">Chọn phòng lập hoá đơn <span style="color: red">*</span></label>
                        <select name="room_id" id="room-select" class="form-select">
                            <option value="" disabled selected>Chọn phòng</option>
                            <!-- Danh sách phòng sẽ được cập nhật qua JavaScript -->
                        </select>
                        <?php echo form_error('room_id', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
            </div>

            <!-- Hàng 2 -->
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Số ngày lẻ <span style="color: red">*</span></label>
                        <input type="text" name="songayle" id="songayle" class="form-control">
                        <?php echo form_error('songayle', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="">Số tháng <span style="color: red">*</span></label>
                        <input type="text" name="chuky" id="chuky" class="form-control">
                        <?php echo form_error('chuky', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="tienphong">Tiền Phòng</label>
                        <input type="text" class="form-control" id="tienphong" name="tienphong">
                    </div>
                </div>
            </div>

            <!-- Hàng 3 -->
            <div class="row">
                <div class="col-3">
                    <div class="water">
                        <div class="form-group">
                            <label for="sodiencu">Số điện cũ (KWh)</label>
                            <input type="number" min="0" id="sodiencu" class="form-control" name="sodiencu" required oninput="calculateTienDien()">
                        </div>

                        <div class="form-group">
                            <label for="sodienmoi">Số điện mới (KWh)</label>
                            <input type="number" min="0" id="sodienmoi" class="form-control" name="sodienmoi" required oninput="calculateTienDien()">
                        </div>

                        <!-- <div class="form-group">
                            <label for="name">Ảnh <span style="color: red">*</span></label>
                            <div class="row ckfinder-group">
                                <div class="col-10">
                                    <input type="text" placeholder="Ảnh chỉ số điện mới" name="img_sodienmoi" id="name" class="form-control image-render" value="<?php echo old('img_sodienmoi', $old); ?>">
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary btn-sm choose-image"><i class="fa fa-upload"></i></button>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="tiennuoc">Tiền điện (4.000đ/1KWh)</label>
                            <input type="text" class="form-control" id="tiendien" name="tiendien">
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="water">
                        <div class="form-group">
                            <label for="sonuoccu">Số nước cũ (m/3)</label>
                            <input type="number" min="0" id="sonuoccu" class="form-control" name="sonuoccu" required oninput="calculateTienNuoc()">
                        </div>

                        <div class="form-group">
                            <label for="sonuocmoi">Số nước mới (m/3)</label>
                            <input type="number" min="0" id="sonuocmoi" class="form-control" name="sonuocmoi" required oninput="calculateTienNuoc()">
                        </div>

                        <!-- <div class="form-group">
                            <label for="name">Ảnh <span style="color: red">*</span></label>
                            <div class="row ckfinder-group">
                                <div class="col-10">
                                    <input type="text" placeholder="Ảnh chỉ số nước mới" name="img_sonuocmoi" id="name" class="form-control image-render" value="<?php echo old('img_sonuocmoi', $old); ?>">
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-primary btn-sm choose-image"><i class="fa fa-upload"></i></button>
                                </div>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="tiennuoc">Tiền Nước (20.000đ/1m3)</label>
                            <input type="text" class="form-control" id="tiennuoc" name="tiennuoc">
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="water">
                        <div class="form-group">
                            <label for="soluong">Số lượng người</label>
                            <input type="text" min="0" id="soluongNguoi" class="form-control" name="soluong" required onchange="calculateTienRac()">
                        </div>

                        <div class="form-group">
                            <label for="tienrac">Tiền rác (10.000đ/1người)</label>
                            <input type="text" class="form-control" id="tienrac" name="tienrac">
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="water">
                        <div class="form-group">
                            <label for="tienmang">Tiền Wifi (50.000đ/1tháng)</label>
                            <input type="text" class="form-control" id="tienmang" name="tienmang">
                        </div>
                    </div>
                    <div class="water">
                        <div class="form-group">
                            <label for="nocu">Cộng thêm</label>
                            <input type="text" class="form-control" id="nocu" name="nocu">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Hàng 4 -->
            <div class="row">
                <div class="col-5">
                    <div class="form-group">
                        <label for="tongtien">Tổng tiền</label>
                        <input type="text" class="form-control" id="tongtien" name="tongtien">
                    </div>
                </div>
            </div>
            <div class="from-group" style="margin-top: 20px">
                <div class="btn-row">
                    <a style="margin-right: 5px" href="<?php echo getLinkAdmin('bill', 'bills') ?>" class="btn btn-secondary"><i class="fa fa-arrow-circle-left"></i> Quay lại</a>
                    <button type="submit" class="btn btn-secondary"><i class="fa fa-plus"></i> Thêm hóa đơn</button>
                </div>
            </div>
        </form>

    </div>
</div>


<?php
layout('footer', 'admin');
?>
