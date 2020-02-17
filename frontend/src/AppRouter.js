import RouteConfig from "./RouteConfig";
import {BrowserRouter, Redirect, Route, Switch} from "react-router-dom";
import React, {useState} from "react";
import UserRoles from "./UserRoles";
import Navigation from "./components/Navigation/Navigation";
import Footer from "./components/Footer/Footer";

export default function AppRouter() {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [user, setUser] = useState({role: UserRoles.NORMAL});

    const generateRoutes = () => {
        return RouteConfig.getAll().map(route => {
            if (route.requiresLogin && !isLoggedIn) {
                return null;
            }
            if (route.shouldRender === true) {
                return <Route key={route.path} path={route.path}
                              render={() => <route.component isLoggedIn={isLoggedIn} setIsLoggedIn={setIsLoggedIn}
                                                             user={user} setUserData={setUser}/>}/>;
            }
            return <Route key={route.path} path={route.path} exact component={route.component}/>;
        });
    };

    return <BrowserRouter>
        <div className="main">
            <Navigation isLoggedIn={isLoggedIn} user={user}/>
            <div className="content">
                <Switch>
                    {generateRoutes()}
                    <Redirect to={RouteConfig.getDefaultPath()}/>
                </Switch>
            </div>
            <Footer/>
        </div>
    </BrowserRouter>
}
