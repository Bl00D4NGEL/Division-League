import React from 'react';
import './App.css';
import AddHistory from './components/AddHistory/AddHistory';
import PlayerTable from './components/PlayerTable/PlayerTable';
import HistoryTable from './components/HistoryTable/HistoryTable';
import AddPlayer from "./components/AddPlayer/AddPlayer";
import {BrowserRouter as Router, Link, Route} from "react-router-dom";

class App extends React.Component {
    render() {
        return (
            <Router>
                <div className="App">
                    <nav>
                        <ul>
                            <li>
                                <Link to="/">Home</Link>
                            </li>
                            <li>
                                <Link to="/history/">Histories</Link>
                            </li>
                            <li>
                                <Link to="/history/add/">Add History</Link>
                            </li>
                            <li>
                                <Link to="/player/add/">Add Player</Link>
                            </li>
                        </ul>
                    </nav>
                    <Route path="/" exact component={PlayerTable}/>
                    <Route path="/history" exact component={HistoryTable}/>
                    <Route path="/history/add" exact component={AddHistory}/>
                    <Route path="/player/add" exact component={AddPlayer}/>
                </div>
            </Router>
        );
    }
}
export default App;
