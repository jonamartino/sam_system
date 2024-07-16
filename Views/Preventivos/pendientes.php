<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Tareas Pendientes</li>
</ol>
<div class="table-responsive-xl table-fixed text-nowrap">
  <table class="table table-light table-hover w-100" data-order='[[ 0, "desc" ]]' data-page-length='25' id="tblPreventivosPendientes">
  <caption>Lista de mantenimientos preventivos pendientes de acción.</caption>
    <thead class="thead-dark">
      <tr>
        <th>#</th>
        <th>Máquina</th>
        <th>Ultima Orden</th>
        <th>Próxima Fecha</th>
        <th class="w-25">Descripción</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
<?php include "Views/Templates/footer.php"; ?>