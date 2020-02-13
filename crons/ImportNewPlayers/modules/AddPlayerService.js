import CustomRequest from "./CustomRequest.js";
import Config from "../Config.js";

export default function AddPlayerService({name, division, playerId, league}) {
    return new Promise((resolve, reject) => {
        CustomRequest(
            Config.addPlayerEndPoint(),
            result => resolve(result),
            error => reject(error),
            {name, division, playerId, league}
        );
    });

}
