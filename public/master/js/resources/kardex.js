$(() => {
    init();

});

function init(){
    const url = `dashboard/resource/data/${resource.id}`;
    const columns = [
        {title: 'Fecha', data:'date'},
        {title: '# Resolución', data:'resolution'},
        {title: 'Tipo movimiento', data:'type_name', visible: !(resource.id == 1)},
        {title: 'Presentación', data:'id', render: (_,__, r) => `${r.presentation}`, visible: !(resource.id == 1)},
        {title: 'Lote', data: 'name_lote'},
        {title: 'Cantidad', data:'quantity_detail'},
        {title: 'Cantidad total', data:'quantity', visible: !(resource.id == 1)},
        {title: 'Entrada', data:'quantity', render:(v,_,r) => `${r.movement_type_id == 1 ? v : 0}`, visible: !(resource.id == 1)},
        {title: 'Salida', data:'quantity', render:(v,_,r) => `${r.movement_type_id == 1 ? 0 : v}`, visible: !(resource.id == 1)},
        {title: 'Balance', data:'saldo'},
    ];

    load_datatable(url, columns)
}