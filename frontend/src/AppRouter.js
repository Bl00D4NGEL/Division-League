import RouteConfig from "./RouteConfig";
import {BrowserRouter as Router, NavLink, Redirect, Route} from "react-router-dom";
import React from "react";

export default class AppRouter extends React.Component{
    render() {
        return <Router>
            <Redirect to={RouteConfig.getDefaultPath()}/>
            <div>
                <nav>
                    <ul>
                        {AppRouter.generateNavLinks()}
                    </ul>
                </nav>
                {AppRouter.generateRoutes()}
            </div>
        </Router>
    }

    static generateNavLinks() {
        return RouteConfig.get(['path', 'name']).map((d) => {
            return <li key={d}>
                <NavLink to={d[0]} className="nav-link">{d[1]}</NavLink>
            </li>
        });
    }

    static generateRoutes() {
        return RouteConfig.get(['path', 'component']).map((d) => {
            return <Route key={d} path={d[0]} exact component={d[1]}/>;
        });
    }
}