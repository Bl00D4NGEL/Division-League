import React, {useState} from 'react';
import LoadPlayersFromMdrService from "../../../services/LoadPlayersFromMdrService";
import LoadPlayersService from "../../../services/LoadPlayersService";
import AddPlayerService from "../../../services/AddPlayerService";
import Table from "../../BaseReactComponents/Table/Table";
import Checkbox from "../../BaseReactComponents/Checkbox/Checkbox";
import Loader from "../../BaseReactComponents/Loader/Loader";
import SubmitButton from "../../BaseReactComponents/SubmitButton/SubmitButton";
import Button from "../../BaseReactComponents/Button/Button";
import CustomForm from "../../BaseReactComponents/Form/Form";

export default function LoadPlayerWidget({divisionToLoad, defaultLeague}) {
    const [isLoaded, setIsLoaded] = useState(true);
    const [error, setError] = useState(undefined);
    const [members, setMembers] = useState([]);
    const [players, setPlayers] = useState([]);
    const membersToTransfer = {};

    const loadPlayers = async () => {
        LoadPlayersFromMdrService({
            division: divisionToLoad,
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

    const setIsAlreadyLoaded = (arr1, arr2) => {
        if (arr1 !== undefined && Array.isArray(arr1) && arr2 !== undefined && Array.isArray(arr2)) {
            arr2 = arr2.map(normalizePlayer);
            return arr1.forEach(x => {
                x.isAlreadyLoaded = inArray(normalizePlayer(x), arr2);
                return x;
            });
        }
        return arr1;
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

    const generateCheckbox = (member) => {
        return <Checkbox
            isDisabled={member.isAlreadyLoaded}
            name='members'
            onClick={e => membersToTransfer[member.id] = {transfer: e.currentTarget.checked, data: member}}
        />
    };

    const generateRows = () => {
        setIsAlreadyLoaded(members, players);
        return members.map((member) => {
            return [
                member.name,
                member.id,
                member.rank,
                generateCheckbox(member)
            ];
        });
    };

    const saveSelectedPlayers = (e) => {
        e.preventDefault();
        for (let id in membersToTransfer) {
            const member = membersToTransfer[id];
            if (member.transfer) {
                AddPlayerService({
                    name: member.data.name,
                    division: member.data.division.replace('DI-', ''),
                    playerId: member.data.id,
                    league: defaultLeague,
                });
            }
        }
    };

    return divisionToLoad !== undefined && divisionToLoad !== '' ? <div>
        <CustomForm onSubmit={saveSelectedPlayers} formFields={
            <div>
                <div>
                    <Button text="Load Players" onClick={loadPlayers}/>
                    <SubmitButton value="Save Players"/>
                </div>
                <div>
                    <Loader isLoaded={isLoaded} error={error} content={generatePlayerTable()}/>
                </div>
            </div>
        }/>
    </div> : <div/>;
}
