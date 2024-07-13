<?php include "Views/Templates/header.php"; ?>
<ol class="breadcrumb mb-4">
  <li class="breadcrumb-item active" aria-current="page">Ordenes de Mantenimiento Cerradas</li>
</ol>

<div class="table-responsive-xl">
  <table class="table table-light table-hover w-100" data-order='[[ 0, "desc" ]]' data-page-length='25' id="tblOrdenesCerradas">
    <thead class="thead-dark">
      <tr>
        <th>Orden</th>
        <th>Preventivo</th>
        <th>Tecnico</th>
        <th>Fecha</th>
        <th>Tiempo</th>
        <th>Estado</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>

<?php include "Views/Templates/footer.php"; ?>