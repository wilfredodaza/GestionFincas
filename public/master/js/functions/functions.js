function alert(title = 'Alert', msg = 'Alert', icon = 'success', time=0, maxOpened = 5){
  var shortCutFunction = icon,
      prePositionClass = 'toast-top-right';

  prePositionClass =
      typeof toastr.options.positionClass === 'undefined' ? 'toast-top-right' : toastr.options.positionClass;
  toastr.options = {
      maxOpened,
      autoDismiss: true,
      closeButton: true,
      newestOnTop: true,
      progressBar: false,
      preventDuplicates: true,
      timeOut: time,             // Duración en milisegundos (0 significa que no se cierra automáticamente)
      extendedTimeOut: time,
      onclick: null,
      tapToDismiss: true,
  };
  var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
  if (typeof $toast === 'undefined') {
    return;
  }
}

function validData(id_form){
  const form = $(`#${id_form}`);
  const inputs = form.find('input, select, textarea');
  const data = {};
  let isValid = true;
  inputs.each(function () {
      const input = $(this);
      const value = input.val() ? input.val().trim() : "";
      const isRequired = input.hasClass('required');
      const isSelect2 = input.hasClass('select2-hidden-accessible');
      const id = input.attr('id');

      if(id != undefined){
    
        if (isRequired && !value){
            isValid = false;
            if (isSelect2) {
                input.next('.select2-container').find('.select2-selection').addClass('invalid');
            } else {
                input.addClass('invalid');
            }
        } else {
            if (isSelect2) {
                input.next('.select2-container').find('.select2-selection').removeClass('invalid');
            } else {
                input.removeClass('invalid');
            }
        }
        data[input.attr('id').replace(/\-/g, "_")] = value;

      }

  });

  $(`#${id_form} .full-editor`).each(function () {
    const id = $(this).attr('id');
    const quill = quillInstances[id];
    if (quill) {
        const htmlContent = quill.root.innerHTML.trim();
        const plainText = quill.getText().trim();
        const isRequired = $(this).hasClass('required');

        if (isRequired && plainText === '') {
            isValid = false;
            $(this).addClass('invalid');
        } else {
            $(this).removeClass('invalid');
        }

        data[id.replace(/\-/g, "_")] = htmlContent == '<p><br></p>' ? '' : htmlContent;
    }
});

  return {isValid: isValid, data: data};
}

function base_url(array = []) {
  var url = localStorage.getItem('url');
  if (array.length == 0) return `${url}`;
  else return `${url}${array.join('/')}`;
}

function formatPrice(price){
  const formatter = new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      minimumFractionDigits: 2
  })
  return formatter.format(price)
}

const separador_miles = (numero) => {
  const formatter = new Intl.NumberFormat('es-CO', {
      style: 'decimal',
      minimumFractionDigits: 2,
  });
  return formatter.format(numero);
};

const format_number = (numero) => {
  return parseFloat(numero.replace(/[a-zA-Z]/g, '').replace(/\./g, '').replace(',', '.'));
}

function updateFormattedValue(input) {
  let value = input.value;

  // Remover letras, puntos de miles y convertir coma decimal a punto
  value = value.replace(/[a-zA-Z]/g, '').replace(/\./g, '').replace(',', '.');

  // Convertir el valor en número flotante
  let numericValue = parseFloat(value);

  if (!isNaN(numericValue)) {
      // Formatear el valor como número con separadores de miles
      const formattedValue = separador_miles(numericValue);

      // Posición del cursor antes de actualizar el valor
      const cursorPosition = input.selectionStart;

      // Actualizar el valor del input
      input.value = formattedValue;

      // Restaurar la posición del cursor
      setTimeout(() => {
          input.setSelectionRange(cursorPosition, cursorPosition);
      }, 0);
  }
}

function onlyNumericKeypress(event) {
  const input = event.target;
  input.value = input.value.replace(/[^0-9]/g, '');
}

const previewTemplate = `<div class="dz-preview dz-file-preview">
  <div class="dz-details">
    <div class="dz-thumbnail">
      <img data-dz-thumbnail>
      <span class="dz-nopreview">No preview</span>
      <div class="dz-success-mark"></div>
      <div class="dz-error-mark"></div>
      <div class="dz-error-message"><span data-dz-errormessage></span></div>
      <div class="progress">
        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
      </div>
    </div>
    <div class="dz-filename" data-dz-name></div>
    <div class="dz-size" data-dz-size></div>
  </div>
</div>`;

async function uploadSupport(){
  let movement_type;
  let movement;
  if (typeof getMovementType === "function") {
    movement_type = getMovementType();
  }
  if (typeof getMovement === "function") {
    movement = getMovement();
  }
  let myDropzone = null;

  const value_name_file = $('#support_name').val();
  const value_base64_file = $('#support_file').val();

  const {value: support} = await Swal.fire({
    title: `Soporte de ${movement_type ? movement_type.name : movement.type.name }`,
    html: `
      <div class="col-12">
        <div class="card mb-6">
          <div class="card-body">
            <form action="/upload" class="dropzone needsclick" id="dropzone-basic">
              <div class="dz-message needsclick">
                Drop files here or click to upload
                <span class="note needsclick"
                  >(This is just a demo dropzone. Selected files are
                  <span class="fw-medium">not</span> actually uploaded.)</span
                >
              </div>
              <div class="fallback">
                <input name="file" type="file" />
              </div>
            </form>
          </div>
        </div>
      </div>
    `,
    confirmButtonText: 'Guardar',
    didOpen: () => {
        const dropzoneBasic = document.querySelector('#dropzone-basic');
        if (dropzoneBasic) {
          myDropzone  = new Dropzone(dropzoneBasic, {
            // autoProcessQueue: false, // 👈 evita que lo suba automáticamente
            previewTemplate: previewTemplate,
            parallelUploads: 1,
            maxFilesize: 5,
            addRemoveLinks: true,
            maxFiles: 1
          });

          if (value_name_file) {
            let mockFile = { name: value_name_file, size: 12345 }; // el size es simulado
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("complete", mockFile);
            myDropzone.files.push(mockFile);
          }
        }
    },
    customClass: {
      confirmButton: 'btn btn-primary waves-effect'
    },
    preConfirm: () => {
      if (myDropzone && myDropzone.files.length > 0) {
        const file = myDropzone.files[0];

        // ⚠️ Caso EDITAR: si es mockFile, no hay contenido real
        if (!file.type && value_base64_file) {
          return {
            name: file.name,
            base64: value_base64_file
          };
        }

        // ⚠️ Caso CREAR: archivo nuevo
        return new Promise((resolve) => {
          const reader = new FileReader();
    
          reader.onload = function (e) {
            const base64 = e.target.result.split(",")[1]; 
            resolve({
              name: file.name,
              base64: base64
            });
          };
    
          reader.onerror = function () {
            Swal.showValidationMessage("Error leyendo el archivo");
            resolve(false);
          };
    
          reader.readAsDataURL(file);
        });
      } else {
        return {
          name: "",
          base64: ""
        };
      }
    }
  })

  if (support) {
    const support_file = $('#support_file'), support_name = $('#support_name');
    if(support_file){
      support_file.val(support.base64);
      support_name.val(support.name)
    }
  }
}

function toYMD(dateString) {
  const d = new Date(dateString);
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, "0"); // Mes empieza en 0
  const day = String(d.getDate()).padStart(2, "0");
  return `${year}-${month}-${day}`;
}