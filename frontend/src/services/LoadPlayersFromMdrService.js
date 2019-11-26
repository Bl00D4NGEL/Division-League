import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadPlayersFromMdrService({division, setIsLoaded, setError, setPlayers}) {
    setIsLoaded(false);
    CustomRequest(
        Config.mdrDivisionMembersEndPoint(division.toLowerCase()),
        (result) => {
            if (Array.isArray(result) && result.length === 1) {
                setPlayers(result[0]);
            }
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}
