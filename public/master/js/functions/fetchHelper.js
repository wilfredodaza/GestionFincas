const request = async (url, data, method = 'POST', time = 500, headers = {}) => {


    const options = {
        method,
        headers: {
        'Content-Type': 'application/json; charset=UTF-8',
        ...headers,
        },
    };

    options.body = data ? JSON.stringify(data) : null;

    toastr.clear();

    if (time != 0) {
        Swal.fire({
        showConfirmButton: false,
        allowOutsideClick: false,
        customClass: {},
        willOpen: function () {
            Swal.showLoading();
        }
        });
    }

    try {
        const response = await fetch(url, options);

        if (response.redirected) {
        window.location.href = response.url;
        return; // corta
        }

        // leer UNA SOLA VEZ
        const text = await response.text();

        // intenta parsear json si se puede
        const tryJson = () => {
        try { return JSON.parse(text); } catch (e) { return null; }
        };

        if (!response.ok) {
        //console.log('SERVER RAW (status=' + response.status + '):', text);
        let errorData = tryJson();
        try { errorData = JSON.parse(text); } catch (e) {}
        
        // si backend devolvió json con estructura {msg,title,error}
        if (errorData && typeof errorData === 'object') {
            throw new Error(JSON.stringify({
            msg: errorData.msg || 'Error desconocido',
            title: errorData.title || 'Error en la consulta',
            error: errorData.error || text || 'Error general'
            }));
        }
        //console.log('RESPUESTA CRUDA ERROR:', text);

        // si devolvió HTML / texto plano
        throw new Error(JSON.stringify({
            msg: 'Error desconocido',
            title: `Error HTTP ${response.status}`,
            error: text || 'Respuesta no JSON del servidor'
        }));
        }

        // success: si viene json lo parsea, si no devuelve texto
        const okData = tryJson();
        Swal.close();
        return okData ?? text;

    } catch (error) {
        console.log(error.message);

        let error_parse;
        try {
        error_parse = JSON.parse(error.message);
        } catch (e) {
        // por si el error.message no era JSON
        error_parse = {
            title: 'Error',
            msg: 'Error desconocido',
            error: error.message
        };
        }

        Swal.close();

        return new Promise((_, reject) => {
        if (error_parse.msg === 'Error desconocido') {
            Swal.fire({
            icon: 'error',
            title: error_parse.title,
            text: error_parse.error,
            allowOutsideClick: false,
            customClass: {
                confirmButton: 'btn btn-primary waves-effect'
            },
            });
        } else {
            alert(error_parse.title, error_parse.msg, 'error');
        }
        reject(error_parse);
        });
    }
};

const fetchHelper = {
    get: (url, headers = {}, time = 1) => request(url, null, 'GET', time, headers),
    post: (url, data, headers = {}, time = 1) => request(url, data, 'POST', time, headers),
    put: (url, data, headers = {}, time = 1) => request(url, data, 'PUT', time, headers),
    delete: (url, data, headers = {}, time = 1) => request(url, null, 'DELETE', time, headers),
};
