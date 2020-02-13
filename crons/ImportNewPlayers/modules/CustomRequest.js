import fetch from 'node-fetch';

export default function CustomRequest(endpoint, onSuccess, onError, data) {
    const _successCallback = (result) => {
        console.log(result);
    };
    const _errorCallback = (error) => {
        console.error(error);
    };

    if (onSuccess === undefined) {
        onSuccess = _successCallback;
    }

    if (onError === undefined) {
        onError = _errorCallback;
    }

    if (endpoint === undefined) {
        return;
    }

    const generateFetch = (endpoint, data) => {
        if (endpoint.method() === 'GET') {
            return fetch(endpoint.url());
        }
        return fetch(endpoint.url(), {
            method: endpoint.method(),
            body: JSON.stringify(
                data !== undefined ? data : {}
            )
        })
    };

    generateFetch(endpoint, data)
        .then(res => res.json())
        .then(
            res => {
                if (res.status === 'error') {
                    onError(res);
                } else {
                    onSuccess(res);
                }
            },
            onError
        );
}
