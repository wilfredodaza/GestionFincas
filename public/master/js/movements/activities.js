$(() => {
    init();
});

function init(){
    const url = 'dashboard/movements/data/activities';
    const columns = [
        // {title: '# Resolución', data:'resolution'},
        // {title: 'Tipo de movimento', data: 'movement_type_name'},
        {title: 'Fecha', data: 'date'},
        {title: 'Valor', data: 'value', render: (v) => formatPrice(parseFloat(v))},
        // {title: 'Proveedor', data: 'provider_name'},
        // {title: '# Factura', data: 'number_bill'},
        {title: 'Finca', data: 'farm.name'},
        {title: 'Nota', data: 'note'},
        {title: 'Estado', data: 'state', render: (state) => {
            const info = state.color_font.split(" ");
            return `<span class="badge ${state.color_font} ${state.color_background}" >${state.name}</span>`
        }},
        {title: 'Acciones', data:'id', render: (_, __, m) => {

            let actions = _ ? `
                <div class="d-inline-block">
                    <a href="${base_url(['dashboard/movements/download', m.id])}" target="_blank" class="btn btn-sm btn-text-secondary rounded-pill btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-warning" data-bs-original-title="Descargar ${m.movement_type_name}"><i class="ri-file-pdf-2-line"></i></a>
                    <a href="javascript:void(0);" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-line"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end m-0" style="">
                        <li><a href="${base_url(['dashboard/movements/edit', m.id])}" class="dropdown-item">Editar</a></li>
                    <li><a href="javascript:void(0);" onclick="decline(${m.id})" class="dropdown-item text-danger">Rechazar</a></li>
                    </ul> 
                </div>
            ` : ''
            return actions;
        }}
    ];

    const buttons = [
        {
            text: '<i class="ri-add-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Registrar compra</span>',
            className: 'btn btn-primary waves-effect waves-light mx-2 mt-2',
            action: () => {
                window.location.href = base_url(['dashboard/movements/new/bill']);
            }
        }
    ]

    load_datatable(url, columns, buttons)
}