
import {axiosInstance} from "./http-common"
import {JWT_TOKEN} from "./constants";

export class JWToken {
    token = null;

    setToken(token){
        this.token = token;
        axiosInstance.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        localStorage.setItem(JWT_TOKEN, token);
    }

    unsetToken(){
        this.token = null;
        delete axiosInstance.defaults.headers.common["Authorization"];
        localStorage.removeItem(JWT_TOKEN);
    }

    getPayload(){
        if(this.token){
            const parts = this.token.split(".");
            const decoded = atob(parts[1]);
            return JSON.parse(decoded);
        }
        return null;
    }

    getTokenKey(){
        return JWT_TOKEN;
    }

}
