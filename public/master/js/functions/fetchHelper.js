const request = async (url, data, method = 'POST', time = 500, headers = {}) => {
    const options = {
        method,
        headers: {
            'Content-Type': url.includes("localhost") ? 'application/x-www-form-urlencoded' : 'application/json',
            ...headers,
        },
    };
    if (data) {
        options.body = JSON.stringify(data);
    }

    toastr.clear();

    if(time != 0){

        Swal.fire({
            showConfirmButton: false,
            allowOutsideClick: false,
            customClass: {},
            // timer: time,
            willOpen: function () {
                Swal.showLoading();
            }
        });
    }

    return fetch(url, options).then(async response => {
        if (response.redirected)
          window.location.href = response.url;
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(JSON.stringify({
                msg: errorData.msg || 'Error desconocido',
                title: errorData.title || 'Error en la consulta',
                error: errorData.error || 'Error general'
            }));
        }
        const responseData = await response.json();
        return new Promise(resolve => {
            Swal.close();
            resolve(responseData);
        });
    }).catch(error => {
        console.log(error.message);
        const error_parse = JSON.parse(error.message);
        console.log(error_parse);
        return new Promise((_, reject) => {
            if(error_parse.msg === 'Error desconocido'){
                Swal.fire({
                icon:'error',
                title: error_parse.title,
                text: error_parse.error,
                allowOutsideClick: false,
                customClass: {
                    confirmButton: 'btn btn-primary waves-effect'
                },
                })
            }else{
                Swal.close();
                alert(error_parse.title, error_parse.msg, 'error');
            }
            reject(error_parse);
        });
    });
}

const fetchHelper = {
    get: (url, headers = {}, time = 1) => request(url, null, 'GET', time, headers),
    post: (url, data, headers = {}, time = 1) => request(url, data, 'POST', time, headers),
    put: (url, data, headers = {}, time = 1) => request(url, data, 'PUT', time, headers),
    delete: (url, data, headers = {}, time = 1) => request(url, null, 'DELETE', time, headers),
};