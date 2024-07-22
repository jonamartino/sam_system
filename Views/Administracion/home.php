<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Inicio</li>
</ol>

<div class="container-fluid">
    <div class="row">
        <div class="card-header mb-4 text-center bg-dark text-white" style="font-size: 1.1rem;">Dashboard</div>
        <!-- Preventivos Section -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Preventivos</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mt-2">
                                <div class="card bg-dark h-100">
                                    <div class="card-body d-flex text-white">
                                        Activos
                                        <i class="fas fa-gears fa-2x ml-auto"></i>
                                    </div>
                                    <div class="card-footer bg-info text-center">
                                        <a href="<?php echo base_url; ?>Preventivos" class="text-white" style="font-size: 3rem;"><?php echo $data['preventivos_activos']['total']?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="card bg-dark h-100">
                                    <div class="card-body d-flex text-white">
                                        Inactivos
                                        <i class="fas fa-gears fa-2x ml-auto"></i>
                                    </div>
                                    <div class="card-footer bg-info text-center">
                                        <a href="<?php echo base_url; ?>Preventivos/inactivos" class="text-white" style="font-size: 3rem;"><?php echo $data['preventivos_inactivos']['total']?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="card bg-dark h-100">
                                    <div class="card-body d-flex text-white">
                                        Próximos a vencer
                                        <i class="fas fa-gears fa-2x ml-auto"></i>
                                    </div>
                                    <div class="card-footer bg-warning text-center">
                                        <a href="<?php echo base_url; ?>Preventivos/avencer" class="text-white" style="font-size: 3rem;"><?php echo $data['preventivos_avencer']['total']?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mt-2">
                                <div class="card bg-dark h-100">
                                    <div class="card-body d-flex text-white">
                                        Vencidos
                                        <i class="fas fa-gears fa-2x ml-auto"></i>
                                    </div>
                                    <div class="card-footer bg-danger text-center">
                                        <a href="<?php echo base_url; ?>Preventivos/vencidos" class="text-white" style="font-size: 3rem;"><?php echo $data['preventivos_vencidos']['total']?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Ordenes Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Ordenes</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="card bg-dark h-100">
                                <div class="card-body d-flex text-white">
                                    En curso
                                    <i class="fas fa-wrench fa-2x ml-auto"></i>
                                </div>
                                <div class="card-footer bg-info text-center">
                                    <a href="<?php echo base_url; ?>Ordenes" class="text-white" style="font-size: 3rem;"><?php echo $data['ordenes_activas']['total']?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="card bg-dark h-100">
                                <div class="card-body d-flex text-white">
                                    Cerradas
                                    <i class="fas fa-wrench fa-2x ml-auto"></i>
                                </div>
                                <div class="card-footer bg-info text-center">
                                    <a href="<?php echo base_url; ?>OrdenesCerradas" class="text-white" style="font-size: 3rem;"><?php echo $data['ordenes_inactivas']['total']?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="card bg-dark h-100">
                                <div class="card-body d-flex text-white">
                                    Tareas incompletas
                                    <i class="fas fa-wrench fa-2x ml-auto"></i>
                                </div>
                                <div class="card-footer bg-warning text-center">
                                    <a href="<?php echo base_url; ?>Ordenes" class="text-white" style="font-size: 3rem;"><?php echo $data['ordenes_avencer']['total']?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="card bg-dark h-100">
                                <div class="card-body d-flex text-white">
                                    Vencidas
                                    <i class="fas fa-wrench fa-2x ml-auto"></i>
                                </div>
                                <div class="card-footer bg-danger text-center">
                                    <a href="<?php echo base_url; ?>Ordenes/vencidas" class="text-white" style="font-size: 3rem;"><?php echo $data['ordenes_vencidas']['total']?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="card-header mb-4 text-center  bg-dark text-white" style="font-size: 1.1rem;">Backlog</div>
        <!-- Month Selector -->
        <div class="mb-4">
            <div class="col-md-12">
                <?php
                    // Obtener el mes actual
                    $currentMonth = date('n'); // 'n' devuelve el mes sin ceros iniciales (1-12)
                ?>  
                <select id="monthSelector" class="form-control" name="especialidad" placeholder="Especialidad">
                    <label>Especialidad</label>
                    <option value="1" <?php echo $currentMonth == 1 ? 'selected' : ''; ?>>Enero</option>
                    <option value="2" <?php echo $currentMonth == 2 ? 'selected' : ''; ?>>Febrero</option>
                    <option value="3" <?php echo $currentMonth == 3 ? 'selected' : ''; ?>>Marzo</option>
                    <option value="4" <?php echo $currentMonth == 4 ? 'selected' : ''; ?>>Abril</option>
                    <option value="5" <?php echo $currentMonth == 5 ? 'selected' : ''; ?>>Mayo</option>
                    <option value="6" <?php echo $currentMonth == 6 ? 'selected' : ''; ?>>Junio</option>
                    <option value="7" <?php echo $currentMonth == 7 ? 'selected' : ''; ?>>Julio</option>
                    <option value="8" <?php echo $currentMonth == 8 ? 'selected' : ''; ?>>Agosto</option>
                    <option value="9" <?php echo $currentMonth == 9 ? 'selected' : ''; ?>>Septiembre</option>
                    <option value="10" <?php echo $currentMonth == 10 ? 'selected' : ''; ?>>Octubre</option>
                    <option value="11" <?php echo $currentMonth == 11 ? 'selected' : ''; ?>>Noviembre</option>
                    <option value="12" <?php echo $currentMonth == 12 ? 'selected' : ''; ?>>Diciembre</option>
                </select>
            </div>
        </div>
    <!-- Preventivos Section -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Preventivos Backlog</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Completados
                                <i class="fas fa-gears fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-info text-center">
                                <a id="preventivoCompletado" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Cancelados
                                <i class="fas fa-gears fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-danger text-center">
                                <a id="preventivoCancelados" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Atrasados
                                <i class="fas fa-gears fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-warning text-center">
                                <a id="preventivoVencidos" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Nivel de servicio
                                <i class="fas fa-gears fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-secondary text-center">
                                <a id="preventivoServicio" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Ordenes Section -->
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header">Ordenes</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Completadas
                                <i class="fas fa-wrench fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-info text-center">
                                <a id ="ordenesCompletadas" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Canceladas
                                <i class="fas fa-wrench fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-danger text-center">
                                <a id="ordenesCanceladas" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Tareas incompletas
                                <i class="fas fa-wrench fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-warning text-center">
                                <a id="tareasIncompletasMes" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2">
                        <div class="card bg-dark h-100">
                            <div class="card-body d-flex text-white">
                                Nivel de servicio
                                <i class="fas fa-wrench fa-2x ml-auto"></i>
                            </div>
                            <div class="card-footer bg-secondary text-center">
                                <a id="ordenesServicio" href="#!" class="text-white" style="font-size: 3rem;"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Otros Indicadores Section -->
    
</div>
<?php include "Views/Templates/footer.php"; ?>
