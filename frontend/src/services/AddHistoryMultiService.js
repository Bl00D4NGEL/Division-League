import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddHistoryMultiService({winner, loser, proofUrl, setError, winnerTeamName, loserTeamName, setChanges}) {
    CustomRequest(
        Config.addHistoryMultiEndPoint(),
        responseData => {
            setChanges(responseData.data[0]);
        }, error => {
            setError(error.message);
        },{
            winner,
            winnerTeamName,
            loser,
            loserTeamName,
            proofUrl
        }
    );
}
