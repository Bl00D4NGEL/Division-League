import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddHistoryService({setIsLoaded, winner, loser, proofUrl, setChanges, setError}) {
    setIsLoaded(false);
    CustomRequest(
        Config.addHistoryEndPoint(),
        responseData => {
            setChanges({ ...responseData.data });
            setIsLoaded(true);
        }, error => {
            setIsLoaded(true);
            setError(error);
        },{
            winner,
            loser,
            proofUrl
        }
    );
}
