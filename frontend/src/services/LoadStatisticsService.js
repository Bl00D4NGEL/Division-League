import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function LoadStatisticsService({setError, setIsLoaded, setStatistics}) {
    CustomRequest(
        Config.getStatisticsEndPoint(),
        (result) => {
            setStatistics(result.data);
            setIsLoaded(true);
        },
        (error) => {
            setIsLoaded(true);
            setError(error);
        }
    );
}
