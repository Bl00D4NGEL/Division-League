import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadPlayersFromMdrService({division, setIsLoaded, setError, setPlayers}) {
    setIsLoaded(false);
    CustomRequest(
        Config.mdrDivisionMembersEndpoint(division),
        (result) => {
            setPlayers(result);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}