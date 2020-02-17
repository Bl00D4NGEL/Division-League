export default function CustomRequest(endpoint, onSuccess, onError, data) {
    if (endpoint === undefined) {
        return;
    }

    const _successCallback = console.log;
    const _errorCallback = console.error;

    if (onSuccess === undefined) {
        onSuccess = _successCallback;
    }

    if (onError === undefined) {
        onError = _errorCallback;
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

    fetch(prepareRequest())
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
