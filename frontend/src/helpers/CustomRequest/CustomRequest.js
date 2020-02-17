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

    const prepareRequest = () => {
        const opts = {
            method: endpoint.method(),
            credentials: 'include'
        };
        if (data !== undefined) {
            opts.body = JSON.stringify(data);
        }
        return new Request(endpoint.url(), opts);
    };

    fetch(prepareRequest()).then(data => {
        console.log(data);
        return data;
    }).then(res => res.json())
        .then(
            (res) => {
                if (res.status === 'error') {
                    onError(res);
                } else {
                    onSuccess(res);
                }
            },
            onError
        );
}
