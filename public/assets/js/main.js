/* Búsqueda de documentos y registro con PDF o fotografía. */
(function () {
  function iniciar() {
    var filas = Array.from(document.querySelectorAll("[data-documento]"));
    var buscador = document.getElementById("search-documentos");
    var estado = document.getElementById("estado-documentos");
    var desde = document.getElementById("fecha-desde");
    var hasta = document.getElementById("fecha-hasta");
    var errorFechas = document.getElementById("fechas-error");
    var limpiarFiltros = document.getElementById("limpiar-filtros");

    function filtrar() {
      var texto = buscador.value.toLowerCase().trim();
      var visibles = 0;
      var rangoInvalido = Boolean(desde.value && hasta.value && desde.value > hasta.value);
      errorFechas.hidden = !rangoInvalido;
      hasta.setAttribute("aria-invalid", rangoInvalido ? "true" : "false");

      filas.forEach(function (fila) {
        var coincide = fila.textContent.toLowerCase().includes(texto);
        var coincideEstado = !estado.value || fila.dataset.estado === estado.value;
        // Las fechas AAAA-MM-DD se pueden comparar directamente, incluyendo ambos límites.
        var coincideFecha = !rangoInvalido &&
          (!desde.value || fila.dataset.fecha >= desde.value) &&
          (!hasta.value || fila.dataset.fecha <= hasta.value);
        fila.hidden = !(coincide && coincideEstado && coincideFecha);
        if (!fila.hidden) visibles++;
      });

      document.getElementById("sin-resultados").hidden = visibles !== 0;
      document.getElementById("documentos-count").textContent =
        "Mostrando " + visibles + " de " + filas.length + " documentos";
    }

    if (buscador && estado && desde && hasta && errorFechas && limpiarFiltros) {
      buscador.addEventListener("input", filtrar);
      estado.addEventListener("change", filtrar);
      desde.addEventListener("change", filtrar);
      hasta.addEventListener("change", filtrar);
      limpiarFiltros.addEventListener("click", function () {
        buscador.value = "";
        estado.value = "";
        desde.value = "";
        hasta.value = "";
        filtrar();
      });
    }

    var modalPdf = document.getElementById("pdf-modal");
    if (modalPdf && typeof modalPdf.showModal === "function") {
      var visorPdf = document.getElementById("pdf-modal-frame");
      var abrirPdf = document.getElementById("pdf-modal-open");
      document.querySelectorAll("[data-pdf-preview]").forEach(function (enlace) {
        enlace.addEventListener("click", function (event) {
          if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
          event.preventDefault();
          visorPdf.src = enlace.href;
          abrirPdf.href = enlace.href;
          modalPdf.showModal();
          document.body.classList.add("pdf-modal-open");
        });
      });
      modalPdf.addEventListener("close", function () {
        visorPdf.removeAttribute("src");
        abrirPdf.removeAttribute("href");
        document.body.classList.remove("pdf-modal-open");
      });
      modalPdf.addEventListener("click", function (event) {
        if (event.target !== modalPdf) return;
        var limites = modalPdf.getBoundingClientRect();
        if (event.clientX < limites.left || event.clientX > limites.right || event.clientY < limites.top || event.clientY > limites.bottom) {
          modalPdf.close();
        }
      });
    }

    var formulario = document.getElementById("register-form");
    if (!formulario) return;

    var archivoInput = document.getElementById("archivo_pdf");
    var zonaArchivo = document.querySelector(".upload-box");
    var infoArchivo = document.getElementById("archivo-info");
    var aviso = document.getElementById("register-feedback");
    var boton = formulario.querySelector('button[type="submit"]');

    var fotoInput = document.getElementById("foto_documento");
    var botonCamara = document.getElementById("tomar-foto");
    var fotoPreview = document.getElementById("foto-preview");
    var quitarFoto = document.getElementById("quitar-foto");
    var urlFoto = null;
    var limiteArchivo = 25 * 1024 * 1024;

    function limpiarFoto() {
      fotoInput.value = "";
      fotoPreview.hidden = true;
      fotoPreview.removeAttribute("src");
      quitarFoto.hidden = true;
      botonCamara.textContent = "Tomar foto del documento";

      if (urlFoto) {
        URL.revokeObjectURL(urlFoto);
        urlFoto = null;
      }
    }

    function mostrarFoto() {
      var foto = fotoInput.files[0];
      if (!foto) return;

      if (foto.size === 0 || foto.size > limiteArchivo || (foto.type && !foto.type.startsWith("image/"))) {
        limpiarFoto();
        mostrarArchivo();
        resultado("Selecciona una fotografía con contenido y de hasta 25 MB.", false);
        return;
      }

      archivoInput.value = "";
      aviso.hidden = true;
      if (urlFoto) URL.revokeObjectURL(urlFoto);
      urlFoto = URL.createObjectURL(foto);
      fotoPreview.src = urlFoto;
      fotoPreview.hidden = false;
      quitarFoto.hidden = false;
      botonCamara.textContent = "Volver a tomar foto";
      infoArchivo.textContent = foto.name + " · Se convertirá a PDF al guardar.";
    }

    botonCamara.addEventListener("click", function () {
      fotoInput.click();
    });
    fotoInput.addEventListener("change", mostrarFoto);
    quitarFoto.addEventListener("click", function () {
      limpiarFoto();
      mostrarArchivo();
      aviso.hidden = true;
    });
    fotoPreview.addEventListener("error", function () {
      if (fotoPreview.hidden) return;
      limpiarFoto();
      mostrarArchivo();
      resultado("No se pudo leer la foto. Toma otra o utiliza una imagen JPG o PNG.", false);
    });

    async function convertirFotoAPdf(foto) {
      if (!window.jspdf) {
        throw new Error("No se pudo cargar el conversor a PDF. Revisa tu conexión e intenta nuevamente.");
      }

      var imagen = new Image();
      var url = URL.createObjectURL(foto);

      try {
        imagen.src = url;
        try {
          await imagen.decode();
        } catch (error) {
          throw new Error("No se pudo leer la foto. Toma otra o utiliza una imagen JPG o PNG.");
        }

        if (!imagen.naturalWidth || !imagen.naturalHeight) {
          throw new Error("La fotografía no tiene dimensiones válidas. Toma otra foto.");
        }

        var escala = Math.min(1, 2000 / Math.max(imagen.naturalWidth, imagen.naturalHeight));
        var canvas = document.createElement("canvas");
        canvas.width = Math.max(1, Math.round(imagen.naturalWidth * escala));
        canvas.height = Math.max(1, Math.round(imagen.naturalHeight * escala));
        var contexto = canvas.getContext("2d");

        if (!contexto) throw new Error("No se pudo procesar la fotografía.");

        contexto.fillStyle = "#ffffff";
        contexto.fillRect(0, 0, canvas.width, canvas.height);
        contexto.drawImage(imagen, 0, 0, canvas.width, canvas.height);

        var pdf = new window.jspdf.jsPDF({
          orientation: canvas.width > canvas.height ? "landscape" : "portrait",
          unit: "mm",
          format: "a4"
        });
        var anchoPagina = pdf.internal.pageSize.getWidth();
        var altoPagina = pdf.internal.pageSize.getHeight();
        var proporcion = Math.min((anchoPagina - 20) / canvas.width, (altoPagina - 20) / canvas.height);
        var ancho = canvas.width * proporcion;
        var alto = canvas.height * proporcion;

        pdf.addImage(canvas.toDataURL("image/jpeg", 0.9), "JPEG", (anchoPagina - ancho) / 2, (altoPagina - alto) / 2, ancho, alto);

        return new File([pdf.output("blob")], "foto-documento.pdf", { type: "application/pdf" });
      } finally {
        URL.revokeObjectURL(url);
      }
    }

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

    archivoInput.addEventListener("change", function () {
      limpiarFoto();
      mostrarArchivo();
      aviso.hidden = true;
    });
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
      if (boton.disabled || !event.dataTransfer.files.length) return;
      if (event.dataTransfer.files.length !== 1) {
        resultado("Adjunta un solo PDF o una foto por documento.", false);
        return;
      }

      if (event.dataTransfer.files[0].type.startsWith("image/")) {
        fotoInput.files = event.dataTransfer.files;
        mostrarFoto();
      } else {
        limpiarFoto();
        archivoInput.files = event.dataTransfer.files;
        mostrarArchivo();
        aviso.hidden = true;
      }
    });

    formulario.addEventListener("submit", async function (event) {
      event.preventDefault();
      if (boton.disabled || !formulario.reportValidity()) return;

      var archivo = archivoInput.files[0];
      var foto = fotoInput.files[0];
      if (!archivo && !foto) {
        resultado("Selecciona un PDF o toma una foto del documento.", false);
        return;
      }

      var datosEnvio = new FormData(formulario);
      boton.disabled = true;
      botonCamara.disabled = true;
      quitarFoto.disabled = true;
      archivoInput.disabled = true;
      fotoInput.disabled = true;
      boton.textContent = foto ? "Preparando PDF…" : "Guardando…";

      try {
        if (foto) {
          if (foto.size === 0 || foto.size > limiteArchivo) {
            resultado("La fotografía debe tener contenido y pesar como máximo 25 MB.", false);
            return;
          }
          try {
            archivo = await convertirFotoAPdf(foto);
          } catch (error) {
            resultado(error.message, false);
            return;
          }
        }

        if (archivo.size === 0 || archivo.size > limiteArchivo) {
          resultado("El PDF debe tener contenido y pesar como máximo 25 MB.", false);
          return;
        }

        datosEnvio.set("archivo_pdf", archivo, archivo.name);
        boton.textContent = "Guardando…";
        var respuesta = await fetch(formulario.action, {
          method: "POST",
          headers: { Accept: "application/json" },
          body: datosEnvio
        });
        var datos = await respuesta.json();

        if (!respuesta.ok || !datos.ok) {
          resultado(datos.mensaje || "No se pudo guardar el documento.", false);
          return;
        }

        formulario.reset();
        limpiarFoto();
        mostrarArchivo();
        resultado(datos.mensaje, true, datos.id_documento);
      } catch (error) {
        resultado("No se pudo completar el envío. Intenta nuevamente.", false);
      } finally {
        boton.disabled = false;
        botonCamara.disabled = false;
        quitarFoto.disabled = false;
        archivoInput.disabled = false;
        fotoInput.disabled = false;
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
