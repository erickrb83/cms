<?php
use Core\H;

global $currentUser;

//H::dnd($currentUser->fname);
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary nav navbar bg-dark" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?=ROOT?>">My Blog Project</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mr-auto my-lg-0 navbar-nav-scroll">
        <?= H::navItem('blog/index', 'Home')?>
        <!-- Dropdown example -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Link
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <!--
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
      -->
      <ul class="navbar-nav mr-auto my-lg-0 navbar-nav-scroll">
        <li class="navbar-nav d-flex">
          <?php if(!$currentUser): ?>
            <?= H::navItem('auth/login', 'Log In') ?>
          <?php endif; ?>
          <?php if($currentUser): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Hello <?= $currentUser->fname?>
              </a>
                <ul class="dropdown-menu">
                  <?= H::navItem('admin/articles', 'Author Portal', true);?>
                  <li><hr class="dropdown-divider"></li>
                  <?= H::navItem('auth/logout', 'Logout', true)?>
                </ul>
            </li>
          <?php endif; ?>
        </li>
      </ul> 
    </div>
  </div>
</nav>