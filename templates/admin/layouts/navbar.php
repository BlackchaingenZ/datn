<?php
// function isActive($module) {
//   return strpos($_SERVER['REQUEST_URI'], $module) !== false ? 'active' : '';
// }
/* Hiệu ứng phong to menu */
?>




<!-- Main content -->
<div class="">
  <section class="content">
    <div class="container-fluid">
      <div class="menu__list">
        <!-- Item 1 -->
        <a href="<?php echo getLinkAdmin('room') ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/apartment.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Quản lý phòng</p>
          </div>
        </a>

        <!-- Item 2 -->
        <a href="<?php echo getLinkAdmin('tenant') ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/clients.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Quản lý khách thuê</p>
          </div>
        </a>

        <!-- Item 3 -->
        <a href="<?php echo getLinkAdmin('contract'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/contracts.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Quản lý hợp đồng</p>
          </div>
        </a>

        <!-- Item 4 -->
        <a href="<?php echo getLinkAdmin('services'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/services.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Quản lý dịch vụ</p>
          </div>
        </a>

        <!-- Item 5 -->
        <a href="<?php echo getLinkAdmin('bill'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/bill.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Quản lý hóa đơn</p>
          </div>
        </a>

        <!-- Item 6 -->
        <a href="<?php echo getLinkAdmin('sumary'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/sum.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Thu/Chi - Tổng kết</p>
          </div>
        </a>

        <!-- Item 7 -->
        <a href="<?php echo getLinkAdmin('users'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/user.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Người dùng hệ thống</p>
          </div>
        </a>

        <!-- Item 8 -->
        <a href="<?php echo getLinkAdmin('groups'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/group.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Nhóm người dùng</p>
          </div>
        </a>

        <!-- Item 9 -->
        <a href="<?php echo getLinkAdmin('rental_history'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/history.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Lịch sử hợp đồng</p>
          </div>
        </a>

        <!-- Item 10 -->
        <a href="<?php echo getLinkAdmin('rental_history'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/icons8-report.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Báo cáo tổng hợp</p>
          </div>
        </a>

        <!-- Item 11 -->
        <a href="<?php echo getLinkAdmin('rental_history'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/icons8-tools.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Cơ sở vật chất</p>
          </div>
        </a>

        <!-- Item 12 -->
        <a href="<?php echo getLinkAdmin('rental_history'); ?>" class="link__menu ">
          <div class="menu__item">
            <img src="<?php echo _WEB_HOST_ADMIN_TEMPLATE; ?>/assets/img/icons8-average-price.png" class="menu__item-image" alt="">
            <p class="menu__item-title">Bảng giá</p>
          </div>
        </a>
      </div>
    </div>
  </section>
</div>
