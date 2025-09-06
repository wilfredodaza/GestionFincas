const info = infoPage();
const lot = lotInfo();

$(() => {
    init();

    const select2 = $('.form-select');

    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            const placeholder = $this.attr('placeholder') || 'Seleccione una opción';
            select2Focus($this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder,
                dropdownParent: $this.parent()
            });
        });
    }

    $('.date-input').flatpickr({
        locale:             "es",
        monthSelectorType:  'dropdown',
    });

});

function init(){
    const url = `dashboard/productive_units/data/${lot.id}`;
    const columns = [
        {title: 'Producto', data:'product_name'},
        {title: 'Codigo', data: 'code'},
        {title: 'Fecha de sembrado', data: 'sowing_date'},
        {title: 'Remplantado', data: 'replanted'},
        {title: 'Estado', data: 'status'},
        {title: 'Acciones', data:'id', render: (id, __, m) => {

            let actions = id ? `
                <div class="d-inline-block">
                    <a href="javascript:void(0)" onclick="editProductiveUnit(${id})" class="btn btn-sm btn-text-info rounded-pill btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Editar"><i class="ri-edit-2-line"></i></a>
                </div>
            ` : ''
            return actions;
        }}
    ];

    const buttons = [
        {
            text: `<i class="ri-add-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Añadir Unidad Productiva</span>`,
            className: 'btn btn-primary waves-effect waves-light mx-2 mt-2',
            action: () => {

                $("#canvasProductiveUnitLabel").html("Añadir unidad productiva");

                const offCanvasElement = document.querySelector('#canvasProductiveUnit');
                let offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
                offCanvasEl.show();
            }
        },
        info.id == 3 ? {
            text: `<i class="ri-list-ordered ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Ver jornales</span>`,
            className: 'btn btn-primary waves-effect waves-light mx-2 mt-2',
            action: () => {
                window.location.href = base_url(['dashboard/resource/kardex/1']);
            }
        } : null,
    ].filter(Boolean)

    load_datatable(url, columns, buttons)
}

async function saveProductiveUnit(e){
    e.preventDefault();

    const url = base_url(['dashboard/productive_units/save']);

    const {isValid, data} = validData("form-productive-unit");
    if(!isValid){
        alert('Campos obligatorios', 'Por favor llenar los campos requeridos.', 'warning', 5000);
        return false;
    }

    const send = {
        productive_units: [data]
    }
    
    const response = await fetchHelper.post(url, send, {}, 500);

    $('#canvasProductiveUnit .btn-close').click();

    
    $('#recurso').val(null).trigger('change');
    $('#estado').val('Activo').trigger('change');
    $('#pu_id').val("");

    $('#form-productive-unit')[0].reset()
    
    reloadTable();

}

function editProductiveUnit(id){
    const productive_units = table_datatable[0].rows().data().toArray();
    const pu = productive_units.find(pu => pu.id == id);

    $('#pu_id').val(pu.id);
    $('#recurso').val(pu.resource_id).trigger('change');
    $('#codigo').val(pu.code);
    $('#siembra').val(pu.sowing_date);
    $('#replan').val(pu.replanted);
    $('#estado').val(pu.status).trigger('change');

    $("#canvasProductiveUnitLabel").html("Editar unidad productiva");

    const offCanvasElement = document.querySelector('#canvasProductiveUnit');
    let offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
    offCanvasEl.show();
}