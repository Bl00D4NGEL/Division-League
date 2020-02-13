import CustomRequest from "./CustomRequest.js";
import Config from "../Config.js";

export default function LoadPlayersFromMdrService({division}) {
    return new Promise((resolve, reject) => {
        CustomRequest(
            Config.mdrDivisionMembersEndPoint(division.toLowerCase()),
            result => {
                if (Array.isArray(result) && result.length === 1) {
                    resolve(result[0])
                } else {
                    reject();
                }
            },
            error => reject(error)
        );
    });
}
