
const info_page = infoPage();

function load_datatable(url, columns, buttons = []){
    let buttons_default = default_buttons();
    buttons = [...buttons_default, ...buttons];

    table_datatable[0] = $(`#table_datatable`).DataTable({
        ajax: {
            url: base_url([url]),
            data: function(d) {
                // d.date_init     = $('#date_init').val();
                const form = $(`#form-filter`);
                const inputs = form.find('input, select, textarea');
                inputs.each(function () {
                    const input = $(this);
                    d[input.attr('name')] = input.val();
                });
            },
            dataSrc: 'data',
            error: function (xhr, error, thrown) {
                console.error("Error en la petición AJAX:", error, thrown);
                console.log("Respuesta del servidor:", xhr.responseText);
    
                // Ejemplo: mostrar alerta con SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Error en la carga',
                    text: 'No se pudieron obtener los datos del servidor',
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'btn btn-primary waves-effect'
                    },
                });
            }
        },
        columns,
        dom: '<"card-header flex-column flex-md-row border-bottom"<"head-label text-center"><"dt-action-buttons text-end pt-0 pt-md-0"B>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
        language: { url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json" },
        responsive: false,
        scrollX: true,
        scrollY: false,
        ordering: false,
        processing: true,
        serverSide: true,
        drawCallback: async function(setting){
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const indicadores = setting.json.indicadores;
            if(indicadores){
                const info_indicadores = indicadoresMovimientos(indicadores);
                $('.indicadores').html(info_indicadores)
            }

            if ($('input[name="date_init"]').length && $('input[name="date_end"]').length) {
                const dates = [];
            
                if ($('#date_init').val() !== "")
                    dates.push(`Desde ${$('#date_init').val()}`);
            
                if ($('#date_end').val() !== "")
                    dates.push(`Hasta ${$('#date_end').val()}`);
            
                if ($("#title-page").length) {
                    $("#title-page").html(`${info.title}<br><small>${dates.join(" / ")}</small>`);
                }
            }
            

            Swal.close();
            setTimeout(() => {
                this.api().columns.adjust();
            }, 300);

        },
        initComplete: () => {

            if(info_page.form_filter && info_page.form_filter.length != 0){
                const select2 = $('.form-select');

                if (select2.length) {
                    select2.each(function () {
                        var $this = $(this);
                        const placeholder = $this.attr('placeholder') || 'Seleccione una opción';
                        const allowNew = $this.hasClass('allow-new');
                        select2Focus($this);
                        $this.wrap('<div class="position-relative"></div>').select2({
                            placeholder,
                            dropdownParent: $this.parent(),
                            tags: allowNew
                        });
                    });
                }

                $('.date-input').flatpickr({
                    locale:             "es",
                    monthSelectorType:  'dropdown',
                });
            }
        },


        buttons
    });
}

function default_buttons(){
    const buttons = [
        {
            extend: 'excel',
            text: '<i class="ri-file-excel-line me-1"></i><span class="d-none d-sm-inline-block">Excel</span>',
            className: `btn rounded-pill btn-label-success waves-effect mx-2 mt-2 ${user.role_id == 3 ? 'd-none' : ''}`,
            filename: `Reporte_${info_page.title.replace(/\s+/g, "_").toLowerCase()}`,
            title: `Reporte de ${info_page.title}`,
            action: async function (e, dt, button, config) {
        
                // 🔹 Traer columnas visibles
                const visibleColumns = dt.columns(':visible').indexes().toArray();
        
                // 🔹 Armar HTML con checkboxes
                let html = '<div style="text-align:left">';
                visibleColumns.forEach(i => {
                    const colTitle = dt.column(i).header().textContent.trim();
                    // Última columna (acciones) la puedes excluir si quieres
                    if (i !== visibleColumns[visibleColumns.length - 1]) {
                        html += `
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="col_${i}" value="${i}" checked>
                                <label class="form-check-label" for="col_${i}">${colTitle}</label>
                            </div>`;
                    }
                });
                html += '</div>';
        
                // 🔹 Mostrar SweetAlert con checkboxes
                const { value: selected } = await Swal.fire({
                    title: 'Selecciona las columnas a exportar',
                    html: html,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Exportar',
                    preConfirm: () => {
                        return [...document.querySelectorAll('input[type=checkbox]:checked')]
                            .map(cb => parseInt(cb.value));
                    }
                });
        
                // Si no selecciona nada o cancela
                if (!selected || selected.length === 0) {
                    return;
                }
        
                // 🔹 Pasar columnas seleccionadas al botón Excel
                config.exportOptions = {
                    columns: selected,
                    format: {
                        body: function (inner, coldex, rowdex) {
                            if (inner.length <= 0) return inner;
                            var el = $.parseHTML(inner);
                            var result = '';
                            $.each(el, function (index, item) {
                                let text = "";
                                if (item.classList !== undefined && item.classList.contains('user-name')) {
                                    text = item.lastChild.firstChild.textContent;
                                } else if (item.innerText === undefined) {
                                    text = item.textContent;
                                } else {
                                    text = item.innerText;
                                }

                                if (/\$/.test(text)) {
                                    text = format_number(text.replace(/\$/g, '').trim());
                                }

                                result += text;
                            });
                            return result;
                        }
                    }
                };
        
                // 🔹 Ejecutar exportación normal de Excel
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
            }
        },
        info_page.form_filter && info_page.form_filter.length ? {
            text: `<i class="ri-filter-3-line ri-16px me-sm-2"></i> <span class="d-none d-sm-inline-block">Filtro</span>`,
            className: 'btn btn-label-warning waves-effect waves-light mx-2 mt-2',
            action: () => {

                const offCanvasElement = document.querySelector('#canvasFilter');
                let offCanvasEl = new bootstrap.Offcanvas(offCanvasElement);
                offCanvasEl.show();
            }
        }:null
    ].filter(Boolean);

    return buttons;
}

function indicadoresMovimientos(indicadores){

    //console.log(info_page.id);
    let info_indicadores;

    switch (info_page.id) {
        case 2:
            const total = (indicadores || []).reduce((acc, type) => {
            const states = type.states || [];
            acc += states.reduce((a, state) => a + (state.movements?.length || 0), 0);
            return acc;
        }, 0);

        const total_pendientes = (indicadores || []).reduce((acc, type) => {
            const states = type.states || [];
            acc += states.reduce((a, state) => a + (state.id == 1 ? (state.movements?.length || 0) : 0), 0);
            return acc;
        }, 0);

        const date_prox = (indicadores || []).reduce((acc, type) => {
            const state_pendiente = (type.states || []).find(s => s.id == 1);
            // movements_pend a veces es {} en tu backend, entonces lo normalizamos a []
            const pend = state_pendiente?.movements_pend;
            if (Array.isArray(pend)) return pend;
            return acc;
        }, []);

            info_indicadores = `
                <div class="col-sm-12 col-lg-4 mb-1">
                    <div class="card card-border-shadow-green h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-green">
                                    <i class="ri-functions"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">Total Actividades</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        <span class="me-1 fw-medium">Total: </span>
                                        <small class="text-muted">${total}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 mb-1">
                    <div class="card card-border-shadow-orange h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-orange">
                                    <i class="ri-information-2-line"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">Total Pendientes</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        <span class="me-1 fw-medium">Total: </span>
                                        <small class="text-muted">${total_pendientes}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 mb-1">
                    <div class="card card-border-shadow-pink h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-pink">
                                    <i class="ri-calendar-schedule-line"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">${date_prox.length != 0 ? `Próxima${date_prox.length == 1 ? "" : "s"}` : `Sin actividades pendientes`} a vencer</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        ${
                                            date_prox.length != 0 ? `
                                                ${date_prox.length == 1 ? `
                                                    <span class="me-1 fw-medium">${date_prox[0].title}</span>
                                                    <span class="text-muted">${date_prox[0].date}</span>
                                                ` : `
                                                    <span class="me-1 fw-medium">${date_prox.length} actividades: </span>
                                                    <span class="text-muted">${date_prox[0].date}</span>
                                                `}
                                            ` : ""
                                        }
                                        
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `
            break;
        case 3:
            const total_jornales = indicadores.reduce((acc, type) => {
                acc += type.states.reduce((acc2, state) => {
                    acc2 += (state.movements || []).reduce((acc3, m) => {
                    const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID) ? Number(JORNAL_ID) : null;
                    const j = (m.details || []).reduce((q, d) => {
                        if (jornalId && Number(d.resource_id) === jornalId) q += (parseFloat(d.quantity) || 0);
                        return q;
                    }, 0);
                    return acc3 + j;
                    }, 0);
                    return acc2;
                }, 0);
                return acc;
            }, 0);

            const total_jornales_pendientes = indicadores.reduce((acc, type) => {
                acc += type.states.reduce((acc2, state) => {
                    if (Number(state.id) !== 1) return acc2;

                    acc2 += (state.movements || []).reduce((acc3, m) => {
                    const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID) ? Number(JORNAL_ID) : null;
                    const j = (m.details || []).reduce((q, d) => {
                        if (jornalId && Number(d.resource_id) === jornalId) q += (parseFloat(d.quantity) || 0);
                        return q;
                    }, 0);
                    return acc3 + j;
                    }, 0);

                    return acc2;
                }, 0);
                return acc;
            }, 0);

            const total_jornales_realizados = indicadores.reduce((acc, type) => {
                acc += type.states.reduce((acc2, state) => {
                    if (Number(state.id) !== 2) return acc2;

                    acc2 += (state.movements || []).reduce((acc3, m) => {
                    const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID) ? Number(JORNAL_ID) : null;
                    const j = (m.details || []).reduce((q, d) => {
                        if (jornalId && Number(d.resource_id) === jornalId) q += (parseFloat(d.quantity) || 0);
                        return q;
                    }, 0);
                    return acc3 + j;
                    }, 0);

                    return acc2;
                }, 0);
                return acc;
            }, 0);



            info_indicadores = `
            <div class="row">
                <div class="col-sm-12 col-lg-4 mb-1 d-flex">
                    <div class="card card-border-shadow-green h-100 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-green">
                                    <i class="ri-functions"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">Total Jornales</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        <span class="me-1 fw-medium">Total: </span>
                                        <small class="text-muted">${total_jornales}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 mb-1 d-flex">
                    <div class="card card-border-shadow-orange h-100 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-orange">
                                    <i class="ri-information-2-line"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">Jornales Pendientes</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        <span class="me-1 fw-medium">Total: </span>
                                        <small class="text-muted">${total_jornales_pendientes}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-lg-4 mb-1 d-flex">
                    <div class="card card-border-shadow-blue h-100 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <div class="avatar me-4">
                                <span class="avatar-initial rounded-3 bg-label-blue">
                                    <i class="ri-check-double-line"></i>
                                </span>
                                </div>
                                <h4 class="mb-0">Jornales Realizados</h4>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                    <p class="mb-0 text-center" >
                                        <span class="me-1 fw-medium">Total: </span>
                                        <small class="text-muted">${total_jornales_realizados}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            `
            
            break;
    
        default:
            info_indicadores = indicadores.reduce((acc, type) => {
                let info = "";
                switch (info_page.id) {
                    case 2:
                        info = type.states.reduce((acc, state) => {
                            let info = `
                                <div class="col-sm-12 col-lg-${(12 / type.states.length)} mb-1">
                                    <div class="card card-border-shadow-${state.color_background.split(" ")[0]} h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-${state.color_background.split(" ")[0]}">
                                                    <i class="${state.icon}"></i>
                                                </span>
                                                </div>
                                                <h4 class="mb-0">${state.name}</h4>
                                            </div>
                                            <div class="row g-6">
                                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                                    <p class="mb-0 text-center" >
                                                        <span class="me-1 fw-medium">Total: </span>
                                                        <small class="text-muted">${state.movements.length}</small>
                                                        ${state.movements_pend ? `
                                                            <br>
                                                            <span class="me-1 fw-medium">${state.name}${state.movements_pend.length >= 2 ? `s (${state.movements_pend.length})`: ""}: </span>
                                                            <small class="text-muted">${state.movements_pend.length == 1 ? `
                                                                ${state.movements_pend[0].title} / ${state.movements_pend[0].date}
                                                                `: `${state.movements_pend[0].date}`}</small>
                                                        ` : ``}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            acc.push(info)
                            return acc;
                        }, []).join('');
                        acc.push(info)
                        
                        break;
                    
                    case 3:
                        info = type.states.reduce((acc, state) => {
                            let info = `
                                <div class="col-sm-12 col-lg-${(12 / type.states.length)}">
                                    <div class="card card-border-shadow-${state.color_background.split(" ")[0]} h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <div class="avatar me-4">
                                                <span class="avatar-initial rounded-3 bg-label-${state.color_background.split(" ")[0]}">
                                                    <i class="${state.icon}"></i>
                                                </span>
                                                </div>
                                                <h4 class="mb-0">${state.name}</h4>
                                            </div>
                                            <div class="row g-6">
                                                <div class="col-sm-12 col-lg-12 d-flex justify-content-center align-items-center">
                                                    <p class="mb-0 text-center" >
                                                        <span class="me-1 fw-medium">Total: </span>
                                                        <small class="text-muted">${state.movements.reduce((acc, m) => {
                                                            const jornalId = (typeof JORNAL_ID !== 'undefined' && JORNAL_ID)
                                                                    ? Number(JORNAL_ID)
                                                                    : null;

                                                                const total_detail = (m.details || []).reduce((acc, md) => {
                                                                    if (jornalId && Number(md.resource_id) === jornalId) {
                                                                        acc += parseFloat(md.quantity) || 0;
                                                                    }
                                                                    return acc;
                                                                }, 0);
                                                            acc += total_detail;
                                                            return acc;
                                                        }, 0)}</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            acc.push(info)
                            return acc;
                        }, []).join('');
                        acc.push(info)
        
                        break;
                
                    default:
                        const total = type.states.reduce((acc, state) => {
                            const total = state.movements.reduce((acc, m) => acc + (m.state_id == 3 ? parseFloat(m.value) : 0), 0);
                            acc += total;
                            return acc;
                        }, 0);
                
                        info = `
                            <div class="col-sm-12 col-lg-${(12 / indicadores.length)}">
                                <div class="card card-border-shadow-${type.id == 1 ? "primary" : (type.id == 2 ? "info" : "warning")} h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="avatar me-4">
                                            <span class="avatar-initial rounded-3 bg-label-${type.id == 1 ? "primary" : (type.id == 2 ? "info" : "warning")}">
                                                <i class="${
                                                    type.id == 1 ? `ri-draft-line` : 
                                                    (type.id == 2 ? `ri-timer-line` : 
                                                        (type.id == 3 ? `ri-currency-line` : "" )
                                                    )
                                                    
                                                }"></i>
                                            </span>
                                            </div>
                                            <h4 class="mb-0">${formatPrice(total)} | ${type.name}</h4>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            ${
                                                info_page.id != 3 ? 
                                                    type.states.reduce((acc, state) => {
                                                        let total_state = state.movements.length
                                                        const info_state = `
                                                            <button type="button" class="btn ${state.color_background} ${state.color_font} me-2 waves-effect waves-light">
                                                                ${state.name}s
                                                                <span class="badge bg-white ${state.color_font} ms-1">${total_state}</span>
                                                            </button>
                                                        `;
                                                        acc.push(info_state);
                                                        return acc;
                                                    }, []).join('') :
                                                    `<button type="button" class="btn light-blue lighten-5 text-blue me-2 waves-effect waves-light">
                                                        Jornales
                                                        <span class="badge bg-white text-blue ms-1">${
                                                            type.states.reduce((acc, state) => {
                                                                const total_m = state.movements.reduce((acc, m) => {
                                                                    const total_d = m.details.reduce((acc, md) => acc += (md.resource_id == 1 ? parseInt(md.quantity) : 0), 0)
                                                                    acc += total_d;
                                                                    return acc;
                                                                    }, 0)
                                                                acc += total_m;
                                                                return acc;
                                                            }, 0)
                                                        }</span>
                                                    </button>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `
                        acc.push(info)
                        break;
                }
                
                return acc;
            }, []).join('')
            break;
    }


    return info_indicadores;
}

function reloadTable(){
    table_datatable[0].ajax.reload();
}

async function sendFilter(e){
    e.preventDefault();
    Swal.fire({
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: {},
        // timer: time,
        willOpen: function () {
            Swal.showLoading();
        }
    });



    $('#canvasFilter .btn-close').click();
    await reloadTable();

}