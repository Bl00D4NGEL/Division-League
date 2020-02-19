import RouteConfig from "./RouteConfig";
import {BrowserRouter, Redirect, Route, Switch} from "react-router-dom";
import React from "react";
import Navigation from "./components/Navigation/Navigation";
import Footer from "./components/Footer/Footer";
import {useAuth} from "./customHooks/useAuth";
import './app-router.scss';

export default function AppRouter() {
    const {user, setUser, isLoggedIn, setIsLoggedIn} = useAuth();

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
        <div className="container">
            <Navigation isLoggedIn={isLoggedIn} user={user}/>
            <main>
                <Switch>
                    {generateRoutes()}
                    <Redirect to={RouteConfig.getDefaultPath()}/>
                </Switch>
            </main>
            <Footer/>
        </div>
    </BrowserRouter>
}
