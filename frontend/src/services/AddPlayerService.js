import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddPlayerService({setIsLoaded, setResult, setError, name, division, playerId, league}) {
    setIsLoaded(false);
    CustomRequest(
        Config.addPlayerEndPoint(), (res) => {
            setResult(JSON.stringify(res));
            setIsLoaded(true);
        }, (error) => {
            setError(error);
            setIsLoaded(true);
        },
        {name, division, playerId, league}
    );
}