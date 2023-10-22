 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light">
   <!-- Left navbar links -->
   <ul class="navbar-nav">
     <li class="nav-item">
       <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
     </li>
     <li class="nav-item">
       <a class="nav-link" href="<?= base_url() ?>" role="button">View WebPage</a>
     </li>


   </ul>
   <!-- Right navbar links -->
   <ul class="navbar-nav ml-auto">
     <li class="nav-item d-none d-sm-inline-block">
       <a href="<?= site_url("logout") ?>" class="nav-link">Logout</a>
     </li>
   </ul>
 </nav>
 <!-- /.navbar -->

 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
   <!-- Brand Logo -->
   <a href="<?= base_url() ?>dashboard" class="brand-link">
     <img src="<?= base_url() ?>public/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
     <span class="brand-text font-weight-light">CI4 SUPPORT TICKET</span>
   </a>

   <!-- Sidebar -->
   <div class="sidebar">
     <!-- Sidebar user panel (optional) -->
     <div class="user-panel mt-3 pb-3 mb-3 d-flex">
       <div class="image">
         <img src="<?= base_url() ?>public/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
       </div>
       <div class="info">
         <a href="#" class="d-block"><?= auth()->user()->username ?? "GUEST" ?></a>
       </div>
     </div>

     <!-- Sidebar Menu -->
     <nav class="mt-2">
       <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

         <li class="nav-header">TRANSACTIONS</li>
         <li class="nav-item">
           <a href="<?= base_url() ?>dashboard" class="nav-link">
             <i class="nav-icon fas fa-rocket"></i>
             <p>
               Dashboard
             </p>
           </a>
         </li>
         <li class="nav-item">
           <a href="<?= base_url() ?>tickets" class="nav-link">
             <i class="nav-icon fas fa-list"></i>
             <p>
               Tickets
             </p>
           </a>
         </li>
         <?php if (auth()->user()->inGroup('admin')) : ?>
           <li class="nav-header">MASTER FILE</li>
           <li class="nav-item">
             <a href="<?= base_url() ?>offices" class="nav-link">
               <i class="nav-icon fas fa-user"></i>
               <p>
                 Office
               </p>
             </a>
           </li>
           <li class="nav-header">ADMIN</li>
           <li class="nav-item">
             <a href="<?= base_url() ?>user" class="nav-link">
               <i class="nav-icon fas fa-user"></i>
               <p>
                 User Management
               </p>
             </a>
           </li>
           <li class="nav-item">
             <a href="<?= base_url() ?>role" class="nav-link">
               <i class="nav-icon fas fa-key"></i>
               <p>
                 Role Management
               </p>
             </a>
           </li>
         <?php endif; ?>
       </ul>
     </nav>
     <!-- /.sidebar-menu -->
   </div>
   <!-- /.sidebar -->
 </aside>