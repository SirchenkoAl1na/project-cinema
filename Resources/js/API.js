class API {
    static get(url) {
        var promise = new Promise((resolve, reject) => {
            fetch(url, {
                method: 'GET',
            }).then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`Error: ${response.status}`);
                }
            }).then((result) => {
                if (result.status == 1) {
                    let res = result.res != undefined ? result.res : result.data;
                    resolve(res);
                }
                else console.error("При читанні даних з API виникла помилка!", "З url "+url+" - "+result.error);
            }).catch((error) => reject(error));
        });

        return promise;
    }

    static post(url,body,errorMessage="При надсилані даних виникла помилка! Спробуйте пізніше") {
        var promise = new Promise((resolve, reject) => {
            fetch(url, {
                method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify(body)
            }).then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error(`Error: ${response.status}`);
                }
            }).then((result) => {
                if (result.status == 1) {
                    let res = result.res != undefined ? result.res : result.data;
                    resolve(res);
                }
                else console.error(errorMessage, "З url "+url+" - "+result.error);
            }).catch((error) => reject(error));
        });

        return promise;
    }

}