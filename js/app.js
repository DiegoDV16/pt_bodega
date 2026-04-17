// Crea una nueva bodega con los datos ingresados en el formulario.
function crear() {

    let data = new FormData();

    data.append("codigo", codigo.value);
    data.append("nombre", nombre.value);
    data.append("direccion", direccion.value);
    data.append("dotacion", dotacion.value);
    data.append("estado", estado.value);
    data.append("encargado", encargado.value);
    //metodo para enviar los datos al servidor y crear la bodega
    fetch("Controllers/crear.php", { method: "POST", body: data })
    .then(r => r.json())
    .then(r => {
        alert(r.mensaje);
        if (r.status === "ok") listar();
    });
}
// Carga la fecha del sistema en el campo de fecha de creación.
function cargarFechaSistema() {

    const input = document.getElementById("fecha_creacion");
    if (!input) return;

    const hoy = new Date();

    const dia = String(hoy.getDate()).padStart(2, '0');
    const mes = String(hoy.getMonth() + 1).padStart(2, '0');
    const anio = hoy.getFullYear();

    input.value = `${dia}/${mes}/${anio}`;
}
// Lista las bodegas según los filtros seleccionados.
function listar() {

    let estado = filtroEstado.value || "";
    let encargado = filtroEncargado.value || "";
    let desde = fecha_desde.value || "";
    let hasta = fecha_hasta.value || "";

    fetch(`Controllers/listar.php?estado=${estado}&encargado=${encargado}&desde=${desde}&hasta=${hasta}`)
    .then(r => r.json())
    .then(data => {

        let html = "";

        data.forEach(b => {
            // Construye la fila de la tabla para cada bodega con sus datos y botones de acción.
            html += `
            <tr>
                <td>${b.codigo}</td>
                <td>${b.nombre}</td>
                <td>${b.direccion}</td>
                <td>${b.dotacion}</td>
                <td>${b.encargado}</td>
                <td>${b.fecha_creacion}</td>
                <td>${b.estado}</td>
                <td>
                    <button class="btn btn-warning btn-sm text-light"
                        onclick="abrirEditar(
                            ${b.id},
                            '${b.codigo}',
                            '${b.nombre}',
                            '${b.direccion}',
                            ${b.dotacion},
                            ${b.estado_id},
                            ${b.encargado_id}
                        )">
                        Editar
                    </button>

                    <button class="btn btn-danger btn-sm"
                        onclick="eliminar(${b.id})">
                        Eliminar
                    </button>
                </td>
            </tr>`;
        });

        tabla.innerHTML = html;
    });
}
// Abre el modal de edición y carga los datos de la bodega seleccionada.
function abrirEditar(id, codigo, nombre, direccion, dotacion, estado_id, encargado_id) {

    edit_id.value = id;
    edit_codigo.value = codigo;
    edit_nombre.value = nombre;
    edit_direccion.value = direccion;
    edit_dotacion.value = dotacion;
    edit_estado.value = estado_id;
    edit_encargado.value = encargado_id;

    new bootstrap.Modal(modalEditar).show();
}
// Guarda los cambios realizados en la edición de una bodega.
function guardarEdicion() {

    let data = new FormData();

    data.append("id", edit_id.value);
    data.append("codigo", edit_codigo.value);
    data.append("nombre", edit_nombre.value);
    data.append("direccion", edit_direccion.value);
    data.append("dotacion", edit_dotacion.value);
    data.append("estado", edit_estado.value);
    data.append("encargado", edit_encargado.value);

    fetch("Controllers/editar.php", { method: "POST", body: data })
    .then(r => r.json())
    .then(r => {
        alert(r.mensaje);
        if (r.status === "ok") {
            listar();
            bootstrap.Modal.getInstance(modalEditar).hide();
        }
    });
}
// Elimina una bodega después de confirmar la acción.
function eliminar(id) {

    if (!confirm("¿Eliminar?")) return;

    let data = new FormData();
    data.append("id", id);

    fetch("Controllers/eliminar.php", { method: "POST", body: data })
    .then(() => listar());
}

window.onload = function () {
    listar();
    cargarFechaSistema();
};