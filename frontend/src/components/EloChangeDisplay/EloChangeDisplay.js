import * as React from "react";
import WinnerLoserValidator from "../../helpers/Validators/WinnerLoserValidator";

export default class EloChangeDisplay extends React.Component {
    render() {
        if (!this.shouldRender()) {
            return null;
        }
        return (
            <div>
                <span>Results:</span>
                <div>
                    <div>{this.props.winner.name} wins against {this.props.loser.name}</div>
                    <br/>
                    <div>{this.props.winner.name} moves
                        from {this.props.winner.elo - this.props.changes.winner} to {this.props.winner.elo} elo
                        (+{this.props.changes.winner})
                    </div>
                    <br/>
                    <div>{this.props.loser.name} moves
                        from {this.props.loser.elo - this.props.changes.loser} to {this.props.loser.elo} elo
                        ({this.props.changes.loser})
                    </div>
                    <br/>
                </div>
            </div>
        )
    }

    shouldRender() {
        return !(WinnerLoserValidator.isLoserAndWinnerNotSet(this.props) || WinnerLoserValidator.areOpponentsEqual(this.props) || this.props.changes === undefined);
    }
}