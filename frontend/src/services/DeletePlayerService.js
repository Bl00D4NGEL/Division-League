import CustomRequest from "../helpers/CustomRequest/CustomRequest";
import Config from "../Config";

export default function DeletePlayerService({player}) {
    console.log(Config.deletePlayerEndPoint());
    CustomRequest(
        Config.deletePlayerEndPoint(), (res) => {
            console.log(res);
        }, (error) => {
            console.error(error);
        },
        {id: player.id}
    );
}
