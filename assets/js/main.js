/* main.js: controla las interacciones visuales del frontend sin comunicarse con el backend. */
(function () {
  function ready(callback) {
    if (window.jQuery) {
      window.jQuery(callback);
    } else if (document.readyState !== "loading") {
      callback();
    } else {
      document.addEventListener("DOMContentLoaded", callback);
    }
  }

  ready(function () {
    var $ = window.jQuery;
    var toast = function (message, type) {
      var node = document.querySelector(".ui-toast");
      if (!node) {
        node = document.createElement("div");
        node.className = "ui-toast";
        document.body.appendChild(node);
      }
      node.textContent = message;
      node.className = "ui-toast is-visible " + (type || "");
      window.clearTimeout(node._timer);
      node._timer = window.setTimeout(function () {
        node.classList.remove("is-visible");
      }, 2600);
    };

    /* Tabla: búsqueda, selección masiva y paginación visual. */
    /* Tabla: búsqueda, selección masiva y paginación visual. */
    var table = document.querySelector(".documents-table");
    if (table) {
      var rows = Array.prototype.slice.call(table.querySelectorAll("tbody tr"));
      var search = document.querySelector(".search-row input");
      var visibleLabel = document.querySelector(".select-visible span");
      var refreshRows = function () {
        var query = (search ? search.value : "").toLowerCase().trim();
        var visible = rows.filter(function (row) {
          var match =
            !query || row.textContent.toLowerCase().indexOf(query) !== -1;
          row.hidden = !match;
          return match;
        });
        if (visibleLabel) {
          visibleLabel.textContent =
            "Seleccionar visibles (" + visible.length + ")";
        }
      };
      if (search) {
        search.addEventListener("input", refreshRows);
      }
      var master = document.querySelector(".select-visible input");
      if (master) {
        master.addEventListener("change", function () {
          rows.forEach(function (row) {
            if (!row.hidden) {
              var input = row.querySelector('input[type="checkbox"]');
              if (input) input.checked = master.checked;
            }
          });
        });
      }
      document
        .querySelectorAll(".pagination button:not(:disabled)")
        .forEach(function (button) {
          button.addEventListener("click", function () {
            document
              .querySelectorAll(".pagination .current")
              .forEach(function (item) {
                item.classList.remove("current");
              });
            if (/^\d+$/.test(button.textContent.trim())) {
              button.classList.add("current");
            }
            toast("Página " + button.textContent.trim() + " seleccionada");
          });
        });
    }

    /* Filtros y acciones rápidas. */
    /* Filtros y acciones rápidas. */
    document.querySelectorAll(".clear-filters").forEach(function (button) {
      button.addEventListener("click", function () {
        var form = button.closest("form");
        if (form) {
          form.reset();
        }
        document
          .querySelectorAll(".active-filters span")
          .forEach(function (chip) {
            chip.remove();
          });
        toast("Filtros limpiados");
      });
    });
    document.querySelectorAll(".apply-filters").forEach(function (button) {
      button.addEventListener("click", function (event) {
        event.preventDefault();
        toast("Filtros aplicados", "success");
      });
    });
    document.querySelectorAll(".row-actions button").forEach(function (button) {
      button.addEventListener("click", function () {
        toast(button.getAttribute("aria-label") || "Acción seleccionada");
      });
    });

    /* Carga visual de archivos mediante selector o arrastre. */
    /* Carga visual de archivos mediante selector o arrastre. */
    var upload = document.querySelector(".upload-box");
    var fileInput = upload ? upload.querySelector('input[type="file"]') : null;
    if (upload && fileInput) {
      upload.addEventListener("click", function () {
        fileInput.click();
      });
      ["dragenter", "dragover"].forEach(function (eventName) {
        upload.addEventListener(eventName, function (event) {
          event.preventDefault();
          upload.classList.add("is-dragging");
        });
      });
      ["dragleave", "drop"].forEach(function (eventName) {
        upload.addEventListener(eventName, function (event) {
          event.preventDefault();
          upload.classList.remove("is-dragging");
        });
      });
      upload.addEventListener("drop", function (event) {
        if (event.dataTransfer.files.length) {
          fileInput.files = event.dataTransfer.files;
          fileInput.dispatchEvent(new Event("change"));
        }
      });
      fileInput.addEventListener("change", function () {
        if (fileInput.files[0]) {
          upload.querySelector("strong").textContent = fileInput.files[0].name;
          upload.classList.add("has-file");
          toast("Archivo listo para revisión", "success");
        }
      });
    }

    /* Validación visual del formulario de registro. */
    /* Validación visual del formulario de registro. */
    var registerForm = document.getElementById("register-form");
    if (registerForm) {
      registerForm.addEventListener("submit", function (event) {
        event.preventDefault();
        var required = Array.prototype.slice.call(
          registerForm.querySelectorAll(
            "input:not([readonly]), select, textarea",
          ),
        );
        var empty = required.filter(function (field) {
          return !field.value.trim();
        });
        required.forEach(function (field) {
          field.classList.toggle("field-error", !field.value.trim());
        });
        if (empty.length) {
          toast("Revisa los campos obligatorios", "error");
          empty[0].focus();
          return;
        }
        toast("Formulario listo para guardar", "success");
      });
      registerForm
        .querySelectorAll("input, select, textarea")
        .forEach(function (field) {
          field.addEventListener("input", function () {
            field.classList.remove("field-error");
          });
        });
    }
  });
})();
