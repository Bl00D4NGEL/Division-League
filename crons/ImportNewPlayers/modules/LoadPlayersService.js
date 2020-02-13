import CustomRequest from "./CustomRequest.js";
import Config from "../Config.js";

export default function LoadPlayersService() {
    return new Promise((resolve, reject) => {
        CustomRequest(
            Config.getAllPlayersEndpoint(),
            result => {
                resolve(result.data);
            },
            error => reject(error)
        );
    });
}
