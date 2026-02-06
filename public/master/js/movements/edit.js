const movement              = getMovement();
const resources_selected    = [];
const resources             = getResources();

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

    $('#movement_date').flatpickr({
        locale:             "es",
        monthSelectorType:  'dropdown',
    });

    // var $this_activity = $('#resource_id');
    // $this_activity.select2();

    if (movement.details && Array.isArray(movement.details)) {
        movement.details.forEach(r => {

            r.movement_detail_id = r.id;
            r.isDelete = false;
            r.productNew = false;
            r.item = (resources_selected.length + 1);

            const resource = resources.find(re => Number(re.id) === Number(r.resource_id));
            // si no existe en resources, NO rompas: log y salta
            if (!resource) {
            console.warn('Recurso no encontrado en catálogo resources:', {
                resource_id: r.resource_id,
                detail: r,
                resources_count: resources.length
            });
            return;
            }

            // copia para no mutar el catálogo
            const resourceCopy = { ...resource };

            //asegura que siempre haya value (para DataTables)
            // si el detalle no trae value, usa presentation_value o 0
            resourceCopy.value = (r.value !== undefined && r.value !== null)
            ? r.value
            : (r.presentation_value ?? 0);

            // si tu tabla requiere presentation, NO la borres
            // delete resourceCopy.presentation;

            const combined = $.extend({}, r, resourceCopy);
            resources_selected.push(combined);
        });
    }


    resources_selected.forEach(r => {
        r.value = (r.value ?? r.presentation_value ?? 0);
    });




    loadTable();
});

function loadTable(){
    table_datatable[0] = $('#table_datatable').DataTable({
        data: resources_selected.filter(r => !r.isDelete).slice().reverse(),
        columns: [
            {title: 'Item', data: 'item'},
            {title: 'Producto', data: 'name', render: (_, __, resource) => {
                return `${_}<br>(${resource.presentation.presentation} ${resource.measurement_unit.code})`
            }},
            {title: 'Cantidad', data: 'quantity', render: (q, _, p) => {
                return `
                    <div class="input-group input-group-merge">
                        <div class="form-floating form-floating-outline">
                            <input type="number" class="form-control" min="1" value="${parseFloat(q).toFixed(2)}" onchange="handleChange(parseFloat(this.value).toFixed(2), ${p.id}, ${p.presentation.id}, 'quantity')">
                        </div>
                    </div>
                `;
            }},
            {title: 'Valor Unitario', data: 'value', defaultContent: 0, render: (v, _, row) => {
                const unit = row.value ?? row.presentation_value ?? 0;
                return `
                <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                    <input type="text"
                        class="form-control"
                        onkeyup="updateFormattedValue(this)"
                        value="${separador_miles(unit)}"
                        onchange="handleChange(this.value, ${row.resource_id}, ${row.presentation.id}, 'value')">
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
            { title:'Total', data:'value', defaultContent: 0, render:(n, _, row) => {
                    const pres = parseFloat(row.presentation?.presentation || 1);
                    const qty  = parseFloat(row.quantity || 0);
                    const unit = parseFloat(row.value ?? row.presentation_value ?? 0);

                    return formatPrice(unit * qty);
                }
            },


            {title: 'Acciones', data: 'id', render: (id, _, r) => {
                return `
                    <div class="d-flex">
                        <a class="btn btn-default btn btn-icon me-2 btn-label-info rounded-pill"
                                data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="tooltip-info" data-bs-original-title="Editar medida"
                                onclick="productEdit(${id}, ${r.presentation.presentation})" href="javascript:void(0);" role="button" target=""><i class="ri-ruler-line"></i></a>
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
                <table>
                    <tbody>
                        <tr>
                            <td><b>Total ${movement.type.name}: </b></td>
                            <td id="td_compra">${formatPrice(parseFloat(movement.value))}</td>
                        </tr>
                    </tbody>
                </table>
            `
            $('div.head-label').html(info);
        },
        drawCallback: function(settings){
            $('#resource_id').removeClass('is-invalid');
            $('.btn-send-bill').prop('disabled', resources_selected.filter(r => !r.isDelete).length == 0 ? true : false);
            var value_total = resources_selected.filter(r => !r.isDelete).reduce((a, b) => {
                if(movement.type.id == 1 || movement.type.id == 3){
                    return a + (parseFloat(String(b.quantity).replace(',', '.')) * parseFloat(b.value));
                }else if (movement.type.id == 2) {
                return a + (
                    (parseFloat(String(b.quantity).replace(',', '.')) * parseFloat(b.presentation.presentation)) *
                    parseFloat(b.resource_type_id != 1 ? b.presentation.presentation_value : b.value)
                );}
                return a; 
            }, 0);

            $('#td_compra').html(formatPrice(parseFloat(value_total)));
            
            $('.btn-send-bill').prop('disabled', value_total == 0 ? true : false);
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            if(movement.movement_type_id == 2 && movement.state_id == 2){
                const jornal = resources_selected.find(r => r.id == 1);
                if(jornal){
                    $('.btn-pay').removeClass('hide');
                }else{
                    $('.btn-pay').addClass('hide');
                }
            }else{
                $('.btn-pay').removeClass('hide');
            }

            const isActivity = String(movement.movement_type_id) === '2';
            const stateId = String(movement.state_id);

            if (isActivity && (stateId === '2' || stateId === '3')) {
            $('.btn-send-bill').addClass('hide');
            } else {
            $('.btn-send-bill').removeClass('hide'); // ✅ clave
            }


            
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
                text: `<i class="ri-add-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Guardar ${movement.type.name}</span>`,
                className: `btn btn-primary waves-effect waves-light mx-2 mt-2 btn-send-bill`,
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
            movement.type.id == 2 && movement.state_id == 1 ? {
                text: '<i class="ri-arrow-right-circle-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Ejecutar</span>',
                className: `btn btn-label-info mt-2 mx-2`,
                action: async () => {
                    Swal.fire({
                        title: `¿Seguro de ejecutar actividad?`,
                        text: `Si se ejecuta la actividad no podra modificarla a futuro`,
                        confirmButtonText: 'Ejecutar',
                        cancelButtonText: 'Cancelar',
                        customClass: {
                            confirmButton: 'btn btn-primary waves-effect',
                            cancelButton: 'btn btn-danger waves-effect',
                        },

                    }).then(async (result) => {
                        if(result.isConfirmed){
                            const url = base_url(['dashboard/movements/state']);
                            const data = {
                                movement_id: movement.id,
                                state_id: 2,
                                movement_type_id: movement.movement_type_id,
                                resources: resources_selected
                            }
                            await fetchHelper.post(url, data, {}, 500);
                        }
                    })
                }
            } : null,
            movement.type.id == 2 && movement.state_id == 2 ? {
                text: '<i class="ri-money-dollar-circle-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Pagar jornales</span>',
                className: `btn btn-label-success mt-2 mx-2 btn-pay`,
                action: () => {
                    window.location.href = base_url(['dashboard/movements/new', `3_${movement.id}` ])
                }
            } : null,
            {
                text: '<i class="ri-arrow-go-back-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Regresar</span>',
                className: `btn btn-secondary waves-effect waves-light mx-2 mt-2`,
                action: () => window.location.href = base_url(['dashboard/movements', movement.type.id == 1 || movement.type.id == 3 ? 'bills' : 'activities' ])
            }
        ].filter(Boolean)
    })
}

function changeFarm(){
    resources_selected.map(resource => {
        resource.lot_id = null;
    });
    reloadTable();
}

function reloadTable(){
    let i = 1;
    resources_selected.map(r => {
        if(!r.isDelete){
            r.item = i;
            i = i + 1;
        }
    })

    table_datatable[0].clear();
    table_datatable[0].rows.add(resources_selected.filter(r => !r.isDelete).slice().reverse());
    table_datatable[0].draw(false);
}

function changeResource(id_resource){
    let $select = $('#resource_id');
    if(id_resource.length > 0){
        // const [id_resource, presentation_id] = info.split(":");
        let resource = resources.find(p => p.id == id_resource);
        let presentation_base = resource.presentations.find(p => p.base == "Si");

        const resource_add = resources_selected.find(r => r.id == id_resource && r.presentation.id == presentation_base.id);
        if(resource_add){
            resource_add.quantity++;
            resource_add.isDelete = false;
        }else{
            let resource = resources.find(p => p.id == id_resource);
            if(resource){
                resource.presentation = resource.presentations.find(p => p.id == presentation_base.id)
                resource = {
                    ...resource,
                    quantity: 1,
                    value: parseFloat(resource.presentation.presentation_value),
                    note: null,
                    lot_id: null,
                    item: (resources_selected.length + 1),
                    isDelete: false,
                    productNew: true
                };
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
    let resource = resources_selected.find(p => (p.id == id && p.presentation.presentation == presentation));
    resource.isDelete = true;
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
        product.value = parseFloat(product.presentation.presentation_value);
        reloadTable();
    }
}

async function sendBill(){
    try{
    if(resources_selected.length == 0){
        $('#resource_id').addClass('is-invalid');
        return alert('Campos obligatorios', 'Por favor ingrese como minimo un producto.', 'warning', 5000)
    }
    const url = base_url(['dashboard/movements/updated']);

    const {isValid, data} = validData("form_bill");
    if(!isValid){
        return alert('Campos obligatorios', 'Por favor llenar los campos requeridos.', 'warning', 5000)
    }

    if(movement.type.id == 1){
        const {value: seller} = await Swal.fire({
            title: 'Comprador',
            input: "text",
            inputValue: movement.seller,
            inputAttributes: {
                autocapitalize: "on"
            },
            customClass: {
              confirmButton: 'btn btn-primary waves-effect'
            },
            preConfirm: async (seller) => {
                if(seller == "" || seller == undefined){
                    Swal.showValidationMessage(`
                        El comprador es necesario
                    `);
                    return false;
                }
                return seller
            }
        })

        if(seller){
            movement.seller = seller;
        }
    }

    movement.details        = resources_selected;
    movement.farm_id        = data.farm_id;
    movement.date           = data.movement_date;
    movement.provider_id    = data.provider_id;
    movement.number_bill    = data.number_bill;
    movement.note           = data.notes;
    
    console.log([movement, data])

    const response = await fetchHelper.post(url, movement, {}, 500);
    }catch(e){
        console.error(e)
        alert(e.message || 'Error guardando');
    }
}