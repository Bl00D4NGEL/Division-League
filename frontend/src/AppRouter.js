import RouteConfig from "./RouteConfig";
import {BrowserRouter as Router, NavLink, Redirect, Route} from "react-router-dom";
import React from "react";
import UserRoles from "./UserRoles";

export default function AppRouter() {
    const [isLoggedIn, setIsLoggedIn] = React.useState(false);
    const [user, setUser] = React.useState({role: UserRoles.normal});


    const generateNavLinks = () => {
        return RouteConfig.getAll().map((route) => {
            if (
                (route.requiresLogin && !isLoggedIn)
                || route.requiredRole > user.role
            ) {
                return null;
            }
            if (route.path === '/login' && isLoggedIn) {
                return null;
            }
            return <li key={route.path}>
                <NavLink to={route.path} className="nav-link">{route.name}</NavLink>
            </li>
        });
    };

    const generateRoutes = () => {
        return RouteConfig.getAll().map((route) => {
            if (route.shouldRender === true) {
                return <Route key={route.path} path={route.path} exact
                              render={() => <route.component isLoggedIn={isLoggedIn} setIsLoggedIn={setIsLoggedIn} setUserData={setUser}/>}/>;
            }
            return <Route key={route.path} path={route.path} exact component={route.component}/>;
        });
    };

    return <Router>
        <Redirect to={RouteConfig.getDefaultPath()}/>
        <div>
            <nav>
                <ul>
                    {generateNavLinks()}
                </ul>
            </nav>
            {generateRoutes()}
        </div>
    </Router>
}
