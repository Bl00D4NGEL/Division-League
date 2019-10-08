export default class AddPlayerValidator {
    static isValid ({name, division, playerId, league}) {
        return (
            name !== undefined &&
            division !== undefined &&
            parseInt(playerId) > 0 &&
            league !== undefined
        );
    }

}