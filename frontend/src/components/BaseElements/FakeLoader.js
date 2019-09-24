import React, {Component} from "react";
import Loader from "./Loader";

/**
 * @return {null}
 */
export default class FakeLoader extends Component {
    constructor(props) {
        super(props);

        this.state = {isLoaded: false};
        setTimeout(() => this.setState({isLoaded: true}), props.timeOut || 200);

    }

    render() {
        return <Loader
            isLoaded={this.state.isLoaded}
            content={this.props.content}
        />
    }
}