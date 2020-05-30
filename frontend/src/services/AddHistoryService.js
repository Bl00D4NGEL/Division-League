import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddHistoryService({
                                              winner,
                                              loser,
                                              proofUrl,
                                              setError,
                                              winnerTeamName,
                                              loserTeamName,
                                              isSweep,
                                              setChanges
                                          }) {
    CustomRequest(
        Config.addHistoryEndPoint(),
        responseData => {
            setChanges(responseData.data);
        }, error => {
            setError(error.message);
        }, {
            winner,
            winnerTeamName,
            loser,
            loserTeamName,
            proofUrl,
            isSweep
        }
    );
}
