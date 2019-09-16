import * as React from "react";

export default class EloChangeDisplay extends React.Component {
    constructor(props) {
        super(props);
        this.state = {...props};
    }

    render() {
        if (!this.shouldRender()) {
            return null;
        }
        return (
            <div>
                <span>Results:</span>
                <div>
                    <div>{this.state.winner.name} wins against {this.state.loser.name}</div>
                    <br/>
                    <div>{this.state.winner.name} moves
                        from {this.state.winner.elo - this.state.changes.winner} to {this.state.winner.elo} elo
                        (+{this.state.changes.winner})
                    </div>
                    <br/>
                    <div>{this.state.loser.name} moves
                        from {this.state.loser.elo - this.state.changes.loser} to {this.state.loser.elo} elo
                        ({this.state.changes.loser})
                    </div>
                    <br/>
                </div>
            </div>
        )
    }

    shouldRender() {
        return !(isLoserAndWinnerNotSet(this.state) || areOpponentsEqual(this.state) || this.state.changes === undefined);
    }
}

function isLoserAndWinnerNotSet(state) {
    return !(state.winner.id !== null && state.loser.id !== null);
}

function areOpponentsEqual(state) {
    return (state.winner.id === state.loser.id);
}