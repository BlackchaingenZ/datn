<?php

$body = getBody();

if(!empty($body['id'])) {
    $serviceId = $body['id'];

    // Kiểm tra Id có tồn tại trong hệ thống hay không
    $serviceDetail = getRows("SELECT id FROM category_collect WHERE id=$serviceId");

    if($serviceDetail > 0) {
        // Thực hiện xóa
       
            $deleteService = delete('category_collect', "id=$serviceId");
            if($deleteService) {
                setFlashData('msg', 'Xóa danh mục thu thành công');
                setFlashData('msg_type', 'suc');
            }else {
                setFlashData('msg', 'Lỗi hệ thống! Vui lòng thử lại sau');
                setFlashData('msg_type', 'err');
            }
    }else {
        setFlashData('msg', 'Danh mục không tồn tại trên hệ thống');
        setFlashData('msg_type', 'err');
    }
}

redirect('?module=collect');