
import {RECENT_PRODUCTS_KEY} from "./constants";
import {RECENT_PRODUCTS_MEMORY} from "./constants";

export class Recently_viewed {
    recently_viewed = [];

    add(product){
        this.setFromLocalStorage();
        if (this.recently_viewed.filter( item => item.name.includes(product.name)).length > 0) return;
        if (this.recently_viewed.length >= RECENT_PRODUCTS_MEMORY) this.recently_viewed.shift();
        this.recently_viewed.push(product);
        localStorage.setItem(RECENT_PRODUCTS_KEY, JSON.stringify(this.recently_viewed));
    }

    get(){
        this.setFromLocalStorage();
        return this.recently_viewed;
    }

    setFromLocalStorage() {
        if (localStorage.getItem(RECENT_PRODUCTS_KEY)) this.recently_viewed = JSON.parse(localStorage.getItem(RECENT_PRODUCTS_KEY));
        else this.recently_viewed = [];
    }

}
