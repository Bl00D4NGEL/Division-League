import RouteConfig from "./RouteConfig";
import {BrowserRouter as Router, NavLink, Redirect, Route} from "react-router-dom";
import React from "react";
import UserRoles from "./UserRoles";

export default function AppRouter() {
    const [isLoggedIn, setIsLoggedIn] = React.useState(false);
    const [user, setUser] = React.useState({role: UserRoles.normal});

    return <Router>
        <Redirect to={RouteConfig.getDefaultPath()}/>
        <div>
            <nav>
                <ul>
                    {generateNavLinks({isLoggedIn, user})}
                </ul>
            </nav>
            {generateRoutes({setIsLoggedIn, setUser})}
        </div>
    </Router>
}

function generateNavLinks(props) {
    return RouteConfig.getAll().map((route) => {
        if (
            (route.requiresLogin && !props.isLoggedIn)
            || route.requiredRole > props.user.role
        ) {
            return null;
        }
        if (route.path === '/login' && props.isLoggedIn) {
            return null;
        }
        return <li key={route.path}>
            <NavLink to={route.path} className="nav-link">{route.name}</NavLink>
        </li>
    });
}

function generateRoutes(setter) {
    return RouteConfig.getAll().map((route) => {
        if (route.shouldRender === true) {
            return <Route key={route.path} path={route.path} exact
                          render={(props) => <route.component {...props} setter={setter}/>}/>;
        }
        return <Route key={route.path} path={route.path} exact component={route.component}/>;
    });
}