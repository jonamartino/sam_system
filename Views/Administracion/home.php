<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Inicio</li>
</ol>
<div class="row">
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Preventivos Activos
                <i class="fas fa-gears fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Preventivos" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['preventivos_activos']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Preventivos Inactivos
                <i class="fas fa-gears fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Preventivos/inactivos" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['preventivos_inactivos']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Preventivos a vencer
                <i class="fas fa-gears fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-warning d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Preventivos/avencer" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['preventivos_avencer']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Preventivos Vencidos
                <i class="fas fa-gears fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-danger d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Preventivos/vencidos" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['preventivos_vencidos']['total']?></span>
            </div>
        </div>
    </div>
    
</div>
<div class="row">

    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Ordenes en curso
                <i class="fas fa-wrench fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Ordenes" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['ordenes_activas']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Ordenes cerradas
                <i class="fas fa-wrench fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>OrdenesCerradas" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['ordenes_inactivas']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Ordenes a vencer
                <i class="fas fa-wrench fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-warning d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Ordenes" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['ordenes_avencer']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Ordenes vencidas
                <i class="fas fa-wrench fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-danger d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Ordenes" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['ordenes_vencidas']['total']?></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Usuarios
                <i class="fas fa-user fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Usuarios" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['usuarios']['total']?></span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mt-2">
        <div class="card bg-dark">
            <div class= "card-body d-flex text-white">
                Maquinas
                <i class="fas fa-gears fa-2x ml-auto"></i>
            </div>
            <div class ="card-footer bg-info d-flex align-item-center justify-content-between">
                <a href="<?php echo base_url; ?>Maquinas" class="text-white">Ver Detalle</a>
                <span class="text-white"><?php echo $data['maquinas']['total']?></span>
            </div>
        </div>
    </div>
</div>

<?php include "Views/Templates/footer.php"; ?>
