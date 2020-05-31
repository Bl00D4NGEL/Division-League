import React, {Fragment} from 'react';
import Table from "../BaseReactComponents/Table/Table";
import UserRoles from "../../UserRoles";
import Button from "../BaseReactComponents/Button/Button";
import DeletePlayerService from "../../services/DeletePlayerService";
import StreakFlame from '../../assets/img/grey-flame.png'

export default function LeagueDisplay({leagueName, players, ...props}) {
    const deletePlayer = p => {
        if (p.name === 'Bl00D4NGEL') {
            alert('Ha, nice try!');
            return;
        }
        DeletePlayerService({player: p});
    };

    const generateRows = () => players.map(p => {
        const streakImages = [];
        for (let i = 1; i <= p.streak; i++) {
            streakImages.push(<img src={StreakFlame} style={{'height': 20 + 'px'}} alt={"Week streak " + p.streak}
                                   title={"Week streak " + p.streak}/>);
        }

        let streakOutput = '';
        if (streakImages.length > 0) {
            streakOutput = <Fragment>({streakImages})</Fragment>;
        }

        const baseData = [
            p.rank,
            p.division,
            <Fragment>
                <span className='flex'>
                    <a key={p.name} target="_blank" rel="noopener noreferrer"
                       href={"https://dmginc.gg/profile/" + p.playerId + "-" + p.name}>
                        {p.name}
                    </a>
                    &nbsp;
                    {streakOutput}
                </span>
            </Fragment>,
            p.elo,
            p.wins,
            p.loses,
            getWinRate(p) + ' %',
        ];

        if (props.isLoggedIn && UserRoles[props.user.role] > 0) {
            baseData.push(
                <Button text="Delete player" onClick={() => deletePlayer(p)}/>
            );
        }
        return baseData;
    });

    const getWinRate = (entry) => {
        if (parseInt(entry.wins) === 0) {
            return 0;
        }
        return (parseInt(entry.wins) / (parseInt(entry.wins) + parseInt(entry.loses)) * 100).toPrecision(4);
    };

    return <div style={{paddingBottom: 20 + 'px'}}>
        <h1>League {leagueName} ({players.length} players)</h1>
        <Table
            sortable={true}
            defaultSortKey={0}
            tableHead={['Rank', 'Division', 'Player (Streak)', 'Elo', 'Wins', 'Loses', 'Win rate']}
            tableData={generateRows()}
        />
    </div>
}
