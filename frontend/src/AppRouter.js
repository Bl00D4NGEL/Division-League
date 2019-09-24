import RouteConfig from "./RouteConfig";
import {BrowserRouter as Router, NavLink, Redirect, Route} from "react-router-dom";
import React from "react";
import UserRoles from "./UserRoles";

export default class AppRouter extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            isLoggedIn: false,
            user: {
                role: UserRoles.normal
            }
        }
    }

    render() {
        return <Router>
            <Redirect to={RouteConfig.getDefaultPath()}/>
            <div>
                <nav>
                    <ul>
                        {this.generateNavLinks()}
                    </ul>
                </nav>
                {AppRouter.generateRoutes()}
            </div>
        </Router>
    }

    generateNavLinks() {
        return RouteConfig.get(['path', 'name', 'requiresLogin', 'requiredRole']).map((d) => {
            if (d[2] && !this.state.isLoggedIn || d[3] > this.state.user.role) { return; }
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