import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadPlayersService({setError, setIsLoaded, setPlayers}) {
    CustomRequest(
        Config.getAllPlayersEndpoint(),
        (result) => {
            setPlayers(result.data);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}