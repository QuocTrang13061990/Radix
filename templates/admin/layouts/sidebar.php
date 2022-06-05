 <?php
    $userId = isLogin()['user_id'];
    $userInfo = firstRaw("SELECT * FROM users WHERE id=$userId");
?>

 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="<?php echo _WEB_HOST_ROOT_ADMIN; ?>" class="brand-link">
         <span class="brand-text font-weight-light text-uppercase">Radix Admin</span>
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="<?php echo _WEB_HOST_TEMPLATE_ADMIN; ?>/assets/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="<?php echo getLinkAdmin('users', 'profile') ?>" class="d-block"><?php echo $userInfo['fullname']; ?></a>
             </div>
         </div>

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                 <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                 <li class="nav-item">
                     <a href="<?php echo _WEB_HOST_ROOT_ADMIN; ?>" class="nav-link <?php echo activeMenuSidebar('') ? 'active' : false; ?>">
                         <i class="nav-icon fas fa-tachometer-alt"></i>
                         <p>
                             Tổng quan
                         </p>
                     </a>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('services') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('services') ? 'active' : false; ?>">
                         <i class="nav-icon fab fa-servicestack"></i>
                         <p>
                             Dịch vụ
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('services'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('services', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('groups') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('groups') ? 'active' : false; ?>">
                         <i class="nav-icon fa fa-users"></i>
                         <!-- <i class="nav-icon fab fa-people-group"></i> -->
                         <p>
                             Nhóm người dùng
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('groups'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('groups', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('users') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('users') ? 'active' : false; ?>">
                         <i class="nav-icon fas fa-user"></i>
                         <p>
                             Người dùng
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('users'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('pages') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('pages') ? 'active' : false; ?>">
                         <!-- <i class="nav-icon fas fa-file"></i> -->
                         <i class="nav-icon fas fa-book"></i>
                         <p>
                             Trang
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('pages'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('pages', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('portfolios') || activeMenuSidebar('portfolio_categories') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('portfolios') || activeMenuSidebar('portfolio_categories') ? 'active' : false; ?>">
                         <i class="nav-icon fas fa-project-diagram"></i>
                         <p>
                             Dự án
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('portfolios'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách dự án</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('portfolios', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới dự án</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('portfolio_categories'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh mục dự án</p>
                             </a>
                         </li>
                     </ul>
                 </li>
                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('blogs') || activeMenuSidebar('blog_categories') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('blogs') || activeMenuSidebar('blog_categories') ? 'active' : false; ?>">
                         <i class="nav-icon fas fa-blog"></i>
                         <p>
                             Blog
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('blogs'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách blog</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('blogs', 'add'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thêm mới blog</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('blog_categories'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh mục blog</p>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('contacts') || activeMenuSidebar('contact_type') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('contacts') || activeMenuSidebar('contact_type') ? 'active' : false; ?>">
                         <!-- <i class="nav-icon fas fa-project-diagram"></i> -->
                         <i class="nav-icon fas fa-file-contract"></i>
                         <p>
                             Liên hệ 
                             <span class="badge badge-danger"><?php echo countContacts(); ?></span>
                             <i style="right: 1rem;" class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('contacts'); ?>" class="nav-link">
                                 <!-- <i class="fas fa-caret-right nav-icon"></i> -->
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Danh sách <span class="badge badge-danger"><?php echo
                                 countContacts(); ?></span></p>
                             </a>
                         </li>

                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('contact_type'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Phòng ban</p>
                             </a>
                         </li>
                     </ul>
                 </li>

                 <li class="nav-item has-treeview <?php echo activeMenuSidebar('options') ? 'menu-open' : false; ?>">
                     <a href="#" class="nav-link <?php echo activeMenuSidebar('options') ? 'active' : false; ?>">
                         <i class="nav-icon fas fa-cog"></i>
                         <p>
                             Thiết lập
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('options', 'general'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thiết lập chung</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('options', 'header'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thiết lập header</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('options', 'footer'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thiết lập footer</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="<?php echo getLinkAdmin('options', 'home'); ?>" class="nav-link">
                                 <i class="fas fa-caret-right nav-icon"></i>
                                 <p>Thiết lập Trang chủ</p>
                             </a>
                         </li>
                     </ul>
                 </li>
             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>

 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">