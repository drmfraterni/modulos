<nav id="barra-menu" class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="#"><img class=img-fluid" src="<?php echo constant('URL'); ?>public/img/logo_new.png"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0 ">
      <li class="nav-item active">
        <a class="nav-link" href="<?php echo constant('URL'); ?>main"><i class="fas fa-home"></i> Inicio <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo constant('URL'); ?>nuevo"><i class="far fa-newspaper"></i> Nuevo</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo constant('URL'); ?>consulta">Consulta</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?php echo constant('URL'); ?>ayuda">Ayuda</a>
      </li>
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
  </div>
</nav>