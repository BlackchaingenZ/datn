<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "datn";

try {
    // Tạo kết nối PDO
    $pdo = new PDO('mysql:host=localhost;dbname=datn', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra xem có dữ liệu được gửi hay không
if (isset($data['cmnd'])) {
    $cmnd = trim($data['cmnd']); // Loại bỏ khoảng trắng
    
    // Kiểm tra số CMND
    if ($cmnd) {
        // Truy vấn để kiểm tra nếu CMND đã tồn tại và lấy thông tin khách
        $stmt = $pdo->prepare('SELECT id, tenkhach, gioitinh, diachi, ngaysinh, room_id FROM tenant WHERE cmnd = :cmnd');
        $stmt->execute(['cmnd' => $cmnd]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($customer) {
            // Kiểm tra xem khách đã có phòng chưa (dựa vào trường room_id)
            $hasRoom = $customer['room_id'] ? true : false;  // Nếu room_id không rỗng, khách đã có phòng

            // Trả về thông tin khách và trạng thái phòng (đã có phòng hoặc chưa)
            echo json_encode([
                'exists' => true,
                'customer' => $customer,
                'hasRoom' => $hasRoom // true nếu khách đã có phòng
            ]);
        } else {
            // CMND không tồn tại trong cơ sở dữ liệu
            echo json_encode(['exists' => false]);
        }
    } else {
        echo json_encode(['error' => 'CMND không hợp lệ']);
    }
} else {
    // Không có dữ liệu gửi đến
    echo json_encode(['error' => 'Không có dữ liệu CMND được gửi.']);
}
?>
