import React, {useState} from 'react';
import Button from "../BaseElements/Button";
import LoadPlayersFromMdrService from "../../services/LoadPlayersFromMdrService";
import Loader from "../BaseElements/Loader";
import Table from "../BaseElements/Table";
import LoadPlayersService from "../../services/LoadPlayersService";
import CheckboxInput from "../BaseElements/CheckboxInput";

export default function LoadPlayerWidget({divisionToLoad}) {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [members, setMembers] = useState([]);
    const [players, setPlayers] = useState([]);
    const [division] = useState(divisionToLoad);

    const loadAndCompare = async () => {
        await loadPlayers();
    };

    const loadPlayers = async () => {
        LoadPlayersFromMdrService({
            division,
            setError,
            setIsLoaded,
            setPlayers: setMembers
        });
        LoadPlayersService({
            setIsLoaded,
            setError,
            setPlayers
        });
    };

    const getDifferenceBetween = (arr1, arr2) => {
        arr2 = arr2.map(normalizePlayer);
        return arr1.map(x => {
            x.isAlreadyLoaded = inArray(normalizePlayer(x), arr2);
            return x;
        });
    };

    const normalizePlayer = (player) => {
        return {
            id: player.playerId || player.id,
            name: player.name,
            division: player.division.replace('DI-', '')
        };
    };

    const inArray = (element, arr) => {
        return arr.filter(x => {
            return x.name === element.name
                && x.division === element.division
                && x.id === element.id;
        }).length > 0;
    };

    const generatePlayerTable = () => {
        return members.length === 0 ? null : <Table
            sortable={false}
            tableHead={['Name', 'ID', 'Rank', 'Save']}
            tableData={generateRows()}
        />
    };

    const generateCheckbox = (memberData) => {
        return <CheckboxInput labelText='Test' isDisabled={memberData.isAlreadyLoaded} name='members' value={JSON.stringify(memberData)}/>
    };

    const generateRows = () => {
        console.log(getDifferenceBetween(members, players));
        return members.map((member) => {
            return [
                member.name,
                member.id,
                member.memberRank,
                generateCheckbox(member)
            ];
        });
    };

    return <div>
        <div>
            <Button text="Load Players" onClick={loadAndCompare}/>
        </div>
        <div>
            <Loader isLoaded={isLoaded} error={error} content={generatePlayerTable()}/>
        </div>
    </div>;
}