const info = infoPage();

$(() => {
    init();

});

function init(){
    const url = `dashboard/movements/data/${info.id}`;
    const columns = [
        {title: '# Resolución', data:'resolution'},
        {title: 'Tipo de movimento', data: 'movement_type_name', visible: info.id == 1},
        {title: 'Fecha', data: 'date'},
        {title: 'Valor', data: 'value', render: (v, _, m) => {
            if(info.id == 3){
                const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID) ? Number(JORNAL_ID) : null;

                v = (m.details || []).reduce((acc, d) => {
                    if (jornalId && Number(d.resource_id) === jornalId) {
                        acc += (parseFloat(d.quantity) || 0) * (parseFloat(d.value) || 0);
                    }
                    return acc;
                }, 0);
            }
            return formatPrice(parseFloat(v) || 0);
        }},

        {title: 'Jornales', data: 'id', render: (id, _, m) => {
            const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID) ? Number(JORNAL_ID) : null;

            const jornales = (m.details || []).reduce((acc, detail) => {
                if (jornalId && Number(detail.resource_id) === jornalId) {
                    acc += (parseFloat(detail.quantity) || 0);
                }
                return acc;
            }, 0);
            //console.log('JORNAL_ID:', JORNAL_ID, 'details ids:', (m.details||[]).map(d=>d.resource_id));

            return jornales;
        }, visible: info.id == 3},
        {title: 'Actividad', data: 'title', visible: info.id == 2 || info.id == 3},
        {title: 'Proveedor', data: 'provider_name', visible: info.id == 1},
        {title: '# Referencia', data: 'number_bill', render: (n, _, m) => {
            return m.custom_number_bill ? m.custom_number_bill : n
        } , visible: info.id == 1},
        {title: 'Finca', data: 'farm.name'},
        {title: 'Pagado por', data: 'seller', visible: [1].includes(info.id)},
        {title: 'Estado', data: 'state', render: (state) => {
            return `<span class="badge ${state.color_font} ${state.color_background}" >${state.name}</span>`
        }},
        {title: 'Acciones', data:'id', render: (_, __, m) => {

            let actions = _ ? `
                <div class="d-inline-block">
                    <a href="${base_url(['dashboard/movements/download', m.id])}" target="_blank" class="btn btn-sm btn-text-secondary rounded-pill btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-warning" data-bs-original-title="Descargar ${info.title}"><i class="ri-file-pdf-2-line"></i></a>
                    ${m.state_id != 4 ? `
                        <a href="javascript:void(0);" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><i class="ri-more-2-line"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end m-0" style="">
                            ${info.id != 3 ? `<li><a href="${base_url(['dashboard/movements/edit', m.id])}" class="dropdown-item">Editar</a></li>` : ""}
                            ${info.id == 3 ? `<li><a href="${base_url(['dashboard/movements/new', `${info.id}_${m.id}`])}" class="dropdown-item">Pagar</a></li>` : ""}
                            ${m.support ? `<li><a target="_blank" href="${base_url(['uploads', m.support])}" class="dropdown-item">Soporte</a></li>` : ""}
                        <li><a href="javascript:void(0);" onclick="decline(${m.id})" class="dropdown-item text-danger">Rechazar</a></li>
                        </ul>
                    ` : ""}
                </div>
            ` : ''
            return actions;
        }}
    ];

    const buttons = [
        info.button != "" ? {
            text: `<i class="ri-add-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">${info.button}</span>`,
            className: 'btn btn-primary waves-effect waves-light mx-2 mt-2',
            action: () => {
                window.location.href = base_url(['dashboard/movements/new', info.id]);
            }
        } : null,
        info.id == 3 ? {
            text: `<i class="ri-list-ordered ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Ver jornales</span>`,
            className: 'btn btn-primary waves-effect waves-light mx-2 mt-2',
            action: () => {
                if (typeof JORNAL_ID === 'undefined' || !JORNAL_ID) {
                    alert('No se encontró el recurso "Jornal" para tu usuario/fincas.');
                    return;
                }
                window.location.href = base_url([`dashboard/resource/kardex/${JORNAL_ID}`]);
            } 
        }:null,
    ].filter(Boolean)

    load_datatable(url, columns, buttons)

}
console.log('index.js cargó, info:', infoPage());


async function decline(movement_id){
    const data = {
        movement_id,
        state_id: 4,
        movement_type_id: info.id
    }
    const url = base_url(['dashboard/movements/state']);
    await fetchHelper.post(url, data, {}, 500);

    reloadTable();
}