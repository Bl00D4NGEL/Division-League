import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddDeletedPlayerService({setIsLoaded, setResult, setError, name, division, playerId, league}) {
    if (isNotAFunctionOrUndefined(setIsLoaded)) {
        setIsLoaded = defaultEmptyFunc;
    }
    if (isNotAFunctionOrUndefined(setResult)) {
        setResult = defaultEmptyFunc;
    }
    if (isNotAFunctionOrUndefined(setError)) {
        setError = defaultEmptyFunc;
    }
    setIsLoaded(false);
    CustomRequest(
        Config.addDeletedPlayerEndPoint(), (res) => {
            setResult(JSON.stringify(res));
            setIsLoaded(true);
        }, (error) => {
            setError(error);
            setIsLoaded(true);
        },
        {name, division, playerId, league}
    );
}

const isNotAFunctionOrUndefined = (varInQuestion) => {
    return typeof varInQuestion !== 'function' || varInQuestion === undefined;
};

const defaultEmptyFunc = () => {};
