
import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from "./pages/Home";
import ProductsList from "./pages/ProductsList";
import ProductDetails from "./pages/ProductDetails";
import Error from "./pages/Error";
import Cart from "./pages/Cart";
import Helper from "./pages/Helper";


Vue.use(VueRouter);

const routes = [

    {
        path: '',
        name: 'home',
        component: Home
    },
    {
        path: '/error/:code',
        name: 'error',
        component: Error
    },
    {
        path: '/products/:category/page/:page',
        name: 'productsList',
        component: ProductsList
    },
    {
        path: '/item/:productName',
        name: 'productDetails',
        component: ProductDetails
    },
    {
        path: '/cart',
        name: 'cart',
        component: Cart
    },
    {
        path: '/helper/:category',
        name: 'helper',
        component: Helper
    },

    // all routes must be placed before this one
    {
        path: '/*',
        redirect: '/error/404',
        name: 'notFound'
    },
];

const router = new VueRouter({
    routes,
    mode: 'history',
    linkExactActiveClass: 'active',
});

export default router;
