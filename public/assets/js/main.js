/* Búsqueda de documentos y registro con PDF. */
(function () {
  function iniciar() {
    var filas = Array.from(document.querySelectorAll("[data-documento]"));
    var buscador = document.getElementById("search-documentos");
    var estado = document.getElementById("estado-documentos");

    function filtrar() {
      var texto = buscador.value.toLowerCase().trim();
      var visibles = 0;

      filas.forEach(function (fila) {
        var coincide = fila.textContent.toLowerCase().includes(texto);
        var coincideEstado = !estado.value || fila.dataset.estado === estado.value;
        fila.hidden = !(coincide && coincideEstado);
        if (!fila.hidden) visibles++;
      });

      document.getElementById("sin-resultados").hidden = visibles !== 0;
      document.getElementById("documentos-count").textContent =
        "Mostrando " + visibles + " de " + filas.length + " documentos";
    }

    if (buscador && estado) {
      buscador.addEventListener("input", filtrar);
      estado.addEventListener("change", filtrar);
    }

    var formulario = document.getElementById("register-form");
    if (!formulario) return;

    var archivoInput = document.getElementById("archivo_pdf");
    var zonaArchivo = document.querySelector(".upload-box");
    var infoArchivo = document.getElementById("archivo-info");
    var aviso = document.getElementById("register-feedback");
    var boton = formulario.querySelector('button[type="submit"]');

    function mostrarArchivo() {
      var archivo = archivoInput.files[0];
      infoArchivo.textContent = archivo
        ? archivo.name + " · " + (archivo.size / 1024 / 1024).toFixed(2) + " MB"
        : "Ningún archivo seleccionado.";
    }

    function resultado(mensaje, correcto, id) {
      aviso.hidden = false;
      aviso.className = "form-feedback " + (correcto ? "success" : "error");
      aviso.setAttribute("role", correcto ? "status" : "alert");
      aviso.replaceChildren(document.createTextNode(mensaje));

      if (correcto && Number.isInteger(id)) {
        var enlace = document.createElement("a");
        enlace.href = "index.php?page=detalle-documento&id=" + id;
        enlace.textContent = "Ver documento";
        aviso.appendChild(enlace);
      }
    }

    archivoInput.addEventListener("change", mostrarArchivo);
    ["dragenter", "dragover"].forEach(function (evento) {
      zonaArchivo.addEventListener(evento, function (event) {
        event.preventDefault();
        zonaArchivo.classList.add("is-dragging");
      });
    });
    ["dragleave", "drop"].forEach(function (evento) {
      zonaArchivo.addEventListener(evento, function (event) {
        event.preventDefault();
        zonaArchivo.classList.remove("is-dragging");
      });
    });
    zonaArchivo.addEventListener("drop", function (event) {
      if (event.dataTransfer.files.length) {
        archivoInput.files = event.dataTransfer.files;
        mostrarArchivo();
      }
    });

    formulario.addEventListener("submit", async function (event) {
      event.preventDefault();
      if (boton.disabled || !formulario.reportValidity()) return;

      var archivo = archivoInput.files[0];
      if (!archivo || archivo.size === 0 || archivo.size > 25 * 1024 * 1024) {
        resultado("Selecciona un PDF con contenido y de hasta 25 MB.", false);
        return;
      }

      boton.disabled = true;
      boton.textContent = "Guardando…";

      try {
        var respuesta = await fetch(formulario.action, {
          method: "POST",
          headers: { Accept: "application/json" },
          body: new FormData(formulario)
        });
        var datos = await respuesta.json();

        if (!respuesta.ok || !datos.ok) {
          resultado(datos.mensaje || "No se pudo guardar el documento.", false);
          return;
        }

        formulario.reset();
        mostrarArchivo();
        resultado(datos.mensaje, true, datos.id_documento);
      } catch (error) {
        resultado("No se pudo completar el envío. Intenta nuevamente.", false);
      } finally {
        boton.disabled = false;
        boton.textContent = "Guardar documento";
      }
    });
  }

  if (window.jQuery) {
    window.jQuery(iniciar);
  } else if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", iniciar);
  } else {
    iniciar();
  }
})();
