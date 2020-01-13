import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddHistoryMultiService({winner, loser, proofUrl, winnerTeamName, loserTeamName, setChanges}) {
    CustomRequest(
        Config.addHistoryMultiEndPoint(),
        responseData => {
            setChanges(responseData.data[0]);
        }, error => {
            console.error(error)
        },{
            winner,
            winnerTeamName,
            loser,
            loserTeamName,
            proofUrl
        }
    );
}
