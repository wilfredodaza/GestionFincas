const resources_selected = [];
const resources             = getResources();
const movement_type         = getMovementType();

$(() => {

    const select2 = $('.newSelect');

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

    const date_movement = localStorage.getItem('dateMovement');

    if(date_movement){
        $('#movement_date').val(date_movement)
    }

    $('#movement_date').flatpickr({
        locale:             "es",
        monthSelectorType:  'dropdown',
    });

    // var $this_activity = $('#resource_id');
    // $this_activity.select2();

    const movement = getMovement();

    if(movement.details != undefined){
        movement.details.map(r => {
            if(r.resource_id == 1){
                r.movement_detail_id = r.id;
                r.isDelete = false;
                r.productNew = false;
                const resource = resources.find(re => re.id == r.resource_id);
                resource.value = r.value;
                delete resource.presentation;
                r.item = (resources_selected.length + 1);
                let combined = $.extend({}, r, resource);
                resources_selected.push(combined);
            }
        })
    }


    loadTable();
});

function loadTable(){
    table_datatable[0] = $('#table_datatable').DataTable({
        data: resources_selected.slice().reverse(),
        columns: [
            {title: 'Item', data: 'item'},
            {title: 'Producto', data: 'name', render: (_, __, resource) => {
                return `${_}<br>(${resource.presentation.presentation} ${resource.measurement_unit.code})`
            }},
            {title: 'Cantidad', data: 'quantity', render: (q, _, p) => {
                return `
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" min="1" value="${q}" onchange="handleChange(this.value, ${p.id}, ${p.presentation.id}, 'quantity')">
                        </div>
                    </div>
                `;
            }},
            {title: 'Valor Unitario', data: 'value', render: (v, _, p) => {
                return movement_type.id == 2 ?`
                    ${formatPrice(p.presentation.presentation_value)} por ${p.measurement_unit.code} 
                `
                :`
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="text" class="form-control" min="1" onkeyup="updateFormattedValue(this)" value="${separador_miles(movement_type.id == 2 ? p.presentation.presentation_value : v )}" onchange="handleChange(this.value, ${p.id}, ${p.presentation.id}, 'value')">
                        </div>
                    </div>
                `;
            }},
            {title: 'Lote', data: 'lot_id', render: (v, _, p) => {
                const farm_id = $('#farm_id').val();
                const farm = user.farms.find(f => f.id == farm_id);
                const lots = farm ? farm.lots : [];
                return lots.length == 0 ? `Sin lotes para seleccionar` : 
                ` 
                    <div class="form-floating form-floating-outline">
                        <select
                            onchange="handleChange(this.value, ${p.id}, ${p.presentation.id}, 'lot_id')"
                            class="form-select form-select-lg newSelectProducts" placeholder="Seleccionar lote"
                            id="lot_product_${p.id}_${p.item}" name="lot_product_${p.id}_${p.item}">
                                <option value="" selected>Seleccionar lote</option>
                                ${lots.map(lot => 
                                    `<option value="${lot.id}" ${lot.id == v ? "selected" : ""}>${lot.name}</option>`
                                ).join("")}
                        </select>
                </div>
                `;
            }},
            {
                title: 'Total', data: 'value', render: (n, _, __) => {
                    switch (movement_type.id) {
                        case '2':
                            const quantity = parseFloat(__.presentation.presentation) * __.quantity;
                            return formatPrice(parseFloat(__.presentation.presentation_value) * quantity)
                            break;
                    
                        default:
                            return formatPrice(n * __.quantity)
                            break;
                    }
                }
            },


            {title: 'Acciones', data: 'id', render: (id, _, r) => {
                return `
                    <div class="d-flex">
                        ${r.measurement_unit.id != 1 ? `<a class="btn btn-default btn btn-icon me-2 btn-label-info rounded-pill"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Editar medida"
                                onclick="productEdit(${id}, ${r.presentation.presentation})" href="javascript:void(0);" role="button" target=""><i class="ri-ruler-line"></i></a>
                        ` : ``}
                        <a class="btn btn-default btn btn-icon me-2 btn-label-danger rounded-pill"
                            data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-danger" data-bs-original-title="Quitar producto"
                            onclick="productDelete(${id}, ${r.presentation.presentation})" href="javascript:void(0);" role="button" target=""><i class="ri-close-large-line"></i></a>
                    </div>
                `
            }}
        ],
        dom: 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>><"card-header flex-column flex-md-row border-bottom row"<"head-label col-sm-12 col-md-12 col-lg-4 text-left"><"dt-action-buttons col-sm-12 col-md-12 col-lg-8 text-end pt-0 pt-md-0"B>>',
        language: { url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json" },
        responsive: false,
        scrollX: true,
        ordering: false,
        initComplete: ()  => {
            let info = `
                <p id="id_descuento_customer"></p>
                <table>
                    <tbody>
                        <tr>
                            <td><b>Total ${movement_type.name}: </b></td>
                            <td id="td_gastos">$0.00</td>
                        </tr>
                    </tbody>
                </table>
            `
            $('div.head-label').html(info);
        },
        drawCallback: function(settings){
            $('#resource_id').removeClass('is-invalid');
            $('.btn-send-bill').prop('disabled', resources_selected.length == 0 ? true : false);
            var value_total = resources_selected.reduce((a, b) => {
                switch (movement_type.id) {
                    case '2':
                        return a + ((parseInt(b.quantity) * parseFloat(b.presentation.presentation)) * parseFloat(b.presentation.presentation_value));                        
                        break;
                
                    default:
                        return a + (parseInt(b.quantity) * parseFloat(b.value));
                        break;
                }
            }, 0);
            $('#td_gastos').html(formatPrice(parseFloat(value_total)));
            
            $('.btn-send-bill').prop('disabled', value_total == 0 ? true : false);
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            
            setTimeout(() => {
                const selects = $('.newSelectProducts');
            
                if (selects.length) {
                    selects.each(function () {
                        const $this = $(this);
            
                        // ✅ destruir select2 si ya está inicializado
                        if ($this.hasClass("select2-hidden-accessible")) {
                            $this.select2('destroy');
                            $this.unwrap(); // 🔑 quita el div extra que se iba acumulando
                        }
            
                        const placeholder = $this.attr('placeholder') || 'Seleccione una opción';
            
                        // ✅ ahora sí inicializamos limpio
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder,
                            dropdownParent: $('.card-datatable'),
                            width: "100%"
                        });
                    });

                    this.api().columns.adjust();
                }
            }, 300);
            

        },
        buttons: [
            {
                text: `<i class="ri-add-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Guardar ${movement_type.name}</span>`,
                className: `btn btn-primary waves-effect waves-light mt-2 btn-send-bill`,
                action: async (e, dt, node, config) => {
                    node.attr('disabled', true);
                    try {
                        await sendBill(); // Llama a la función asíncrona
                    } catch (error) {
                        console.error("Error al enviar gasto:", error);
                    } finally {
                        node.attr('disabled', false); // 🔓 Reactiva el botón siempre, incluso si hay error
                    }
                }
            },
            {
                text: '<i class="ri-arrow-go-back-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Regresar</span>',
                className: `btn btn-secondary waves-effect waves-light mx-2 mt-2`,
                action: () => window.location.href = base_url(['dashboard/movements', movement_type.id == 1 ? 'bills' : 'activities' ])
            }
        ]
    })
}



function reloadTable(){

    console.log(resources_selected)

    table_datatable[0].clear();
    table_datatable[0].rows.add(resources_selected.slice().reverse());
    table_datatable[0].draw(false);
}

async function changeResource(id_resource){
    let $select = $('#resource_id');
    if(id_resource.length > 0){
        // const [id_resource, presentation_id] = info.split(":");
        let resource = resources.find(p => p.id == id_resource);
        let presentation_base = resource.presentations.find(p => p.base == "Si");

        console.log([resource, presentation_base]);

        const resource_add = resources_selected.find(r => r.id == id_resource && r.presentation.id == presentation_base.id);
        if(resource_add){
            resource_add.quantity++;
        }else{
            if(resource){
                resource.presentation = resource.presentations.find(p => p.id == presentation_base.id)
                resource = {
                    ...resource,
                    quantity: 1,
                    value: (parseFloat(resource.presentation.presentation) * parseFloat(resource.presentation.presentation_value)),
                    note: null, lot_id: null, item: (resources_selected.length + 1)};
                resources_selected.push(resource);
            }
        }
        $select.val(null).trigger('change');
        reloadTable();
    }
}

function handleChange(value, id, presentation_id, campo){
    let resource = resources_selected.find(p => p.id == id && p.presentation.id == presentation_id);
    value = campo == 'value' ? format_number(value) : value;
    resource[campo] = value;
    reloadTable();
}

function productDelete(id, presentation){
    let new_data = resources_selected.filter(p => !(p.id == id && p.presentation.presentation == presentation));
    resources_selected.length = 0;
    resources_selected.push(...new_data);
    reloadTable();
}

async function productEdit(id, presentation){
    const resource = resources.find(r => r.id == id);
    const product = resources_selected.find(p => p.id == id && p.presentation.presentation == presentation);
    const {value: form} = await Swal.fire({
        title: `Editar presentación para ${product.name}`,
        html: `
        
            <div class="row pt-3 w-100">
                <div class="col-sm-12 col-lg-12 col-md-12 mb-3">
                    <div class="form-floating form-floating-outline">
                        <select
                        class="form-select form-select-lg selectMeasurement required" placeholder="Seleccionar presentación"
                        id="presentation" name="presentation">
                            ${resource.presentations.map(p => {
                                return `<option value="${p.presentation}"  ${p.presentation == presentation ? "selected" : ""}>${p.presentation} ${product.measurement_unit.code}</option>`
                            }).join("")}
                        </select>
                        <label for="presentation">Presentación</label>
                    </div>
                </div>
            </div>
        
        `,
        didOpen: () => {
            const selects = $('.selectMeasurement');
            
            if (selects.length) {
                selects.each(function () {
                    const $this = $(this);
        
                    // ✅ destruir select2 si ya está inicializado
                    if ($this.hasClass("select2-hidden-accessible")) {
                        $this.select2('destroy');
                        $this.unwrap(); // 🔑 quita el div extra que se iba acumulando
                    }
        
                    const placeholder = $this.attr('placeholder') || 'Seleccione una opción';
        
                    // ✅ ahora sí inicializamos limpio
                    $this.select2({
                        placeholder,
                        dropdownParent: $('.swal2-popup'),
                        width: '100%',
                        tags: true, // 🔑 permite agregar nuevos valores
                        createTag: function (params) {
                            const term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: term,
                                text: term,
                                newOption: true
                            }
                        },
                    });
                });
            }
        },
        confirmButtonText: 'Editar',
        customClass: {
            confirmButton: 'btn btn-primary waves-effect'
        },
        preConfirm: () => {
            const data = {
                presentation: $('#presentation').val(),
            }

            if(data.presentation == "" || data.presentation == undefined || data.presentation == 0){
                $('#presentation').addClass('invalid');
                Swal.showValidationMessage('La presentación es obligatoria');
                return false;
            }

            return data;
        }
    })

    if(form){
        let presentation = product.presentations.find(p => p.presentation == form.presentation);
        if(!presentation){
            presentation = {presentation: form.presentation, presentation_value: 0};
            product.presentations.push(presentation);
        }
        product.presentation = presentation;
        product.value = (parseFloat(product.presentation.presentation) * parseFloat(product.presentation.presentation_value));
        reloadTable();
    }
}

async function sendBill(){
    if(resources_selected.length == 0){
        $('#resource_id').addClass('is-invalid');
        alert('Campos obligatorios', 'Por favor ingrese como minimo un producto.', 'warning', 5000);
        return false;
    }
    const url = base_url(['dashboard/movements/store']);

    const {isValid, data} = validData("form_bill");
    if(!isValid){
        alert('Campos obligatorios', 'Por favor llenar los campos requeridos.', 'warning', 5000);
        return false;
    }

    if(movement_type.id == 1 || movement_type.id == 3){
        const {value: seller} = await Swal.fire({
            title: movement_type.id == 1 ? 'Comprado por: ' : 'Pagado por: ',
            input: "text",
            inputAttributes: {
                autocapitalize: "on"
            },
            customClass: {
              confirmButton: 'btn btn-primary waves-effect'
            },
            preConfirm: async (seller) => {
                if(!seller){
                    Swal.showValidationMessage(`
                        El nombre es necesario
                    `);
                    return false;
                }
                return seller
            }
        })

        if(seller){
            data.seller = seller;
        }
    }

    delete data.resource_id;
    data.movement_type_id = movement_type.id;
    data.resources = resources_selected;
    switch (movement_type.id) {
        case '1':
        case '3':
            data.state_id = 3;
            break;
            
        default:
            data.state_id = 1;
            break;
    }

    const response = await fetchHelper.post(url, data, {}, 500);

    localStorage.removeItem('dateMovement')
    console.log([url, data])
}