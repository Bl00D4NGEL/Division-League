import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadHistoriesService({setHistoryData, setIsLoaded, setError}) {
    CustomRequest(
        Config.recentHistoryEndpoint(),
        (result) => {
            setHistoryData(result.data);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}