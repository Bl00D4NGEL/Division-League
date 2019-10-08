import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function AddHistoryService({setIsLoaded, winner, loser, proofUrl, setWinner, setLoser, setChanges, setError}) {
    setIsLoaded(false);
    const formData = {
        winner: winner.id,
        loser: loser.id,
        proofUrl: proofUrl
    };
    CustomRequest(
        Config.addHistoryEndPoint(),
        (responseData) => {
            const changes = {
                'winner': responseData.data.winner.elo - winner.elo,
                'loser': responseData.data.loser.elo - loser.elo
            };
            setWinner(responseData.data.winner);
            setLoser(responseData.data.loser);
            setChanges(changes);
            setIsLoaded(true);
        }, (error) => {
            setIsLoaded(true);
            setError(error);
        },
        formData
    );
}