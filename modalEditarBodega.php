<?php
include("conexion.php");

$queryEstados = "SELECT id, nombre FROM estado ORDER BY id";
$resultEstados = pg_query($conn, $queryEstados);

$queryEncargados = "SELECT id, 
CONCAT(nombre, ' ', primer_apellido, ' ', segundo_apellido) AS nombre_completo 
FROM encargado 
ORDER BY id";
$resultEncargados = pg_query($conn, $queryEncargados);
?>

<div class="modal fade" id="modalEditar" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header bg-warning text-light">
        <h5 class="modal-title">Editar Bodega</h5>
        <button class="btn-close bg-light" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="edit_id">

        <div class="p-3 border rounded bg-light">

            <h6 class="mb-3 fw-bold text-primary">Datos de la Bodega</h6>
            <!-- Campos de Código, Nombre, Dirección y Dotación para modificar-->
            <div class="row g-2">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Código</label>
                    <input id="edit_codigo" class="form-control" placeholder="Código">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nombre</label>
                    <input id="edit_nombre" class="form-control" placeholder="Nombre">
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold">Dirección</label>
                    <input id="edit_direccion" class="form-control" placeholder="Dirección">
                </div>

                <div class="col-md-6 mt-2">
                    <label class="form-label fw-semibold">Dotación</label>
                    <input id="edit_dotacion" type="number" class="form-control" placeholder="Dotación">
                </div>

            </div>
        </div>

        <div class="p-3 border rounded bg-light mt-3">

            <h6 class="mb-3 fw-bold text-success">Asignación y Estado</h6>
            <!-- Campos de Estado y Encargado para modificar-->
            <div class="row g-2">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Estado</label>
                    <select id="edit_estado" class="form-select">
                        <?php while ($e = pg_fetch_assoc($resultEstados)) { ?>
                            <option value="<?= $e['id']; ?>"><?= $e['nombre']; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Encargado</label>
                    <select id="edit_encargado" class="form-select">
                        <?php while ($en = pg_fetch_assoc($resultEncargados)) { ?>
                            <option value="<?= $en['id']; ?>"><?= $en['nombre_completo']; ?></option>
                        <?php } ?>
                    </select>
                </div>

            </div>

        </div>

      </div>
    <!-- Footer con botones para cerrar el modal o guardar los cambios -->
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" onclick="guardarEdicion()">Guardar</button>
      </div>

    </div>
  </div>
</div>