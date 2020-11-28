/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
import Vuetify from 'vuetify';
import Router from 'vue-router';

import Snotify, { SnotifyPosition } from 'vue-snotify'

const options = {
  toast: {
    position: SnotifyPosition.rightTop
  }
}

Vue.use(Snotify, options)
Vue.use(Router);
Vue.use(Vuetify);
import App from './components/app';
import 'vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css';
import 'vue-snotify/styles/material.css';
import Home from './components/ExampleComponent.vue'
import Login from './components/login.vue'
import {Authcek,AuthLogout} from './Auth.js'
import prdk from './components/product.vue'
import cs from './components/custo.vue'
import inv from './components/inv.vue'
import dinv from './components/detailinv.vue'
import Editprdk from './components/editproduct.vue'
import Editcus from './components/editcustomers.vue'
Vue.use(Authcek);

const routes = [
    {
        path:'/',
        component:Home,

    },{
       path:'/login',
       component:Login
    },{
        path:'/product',
        component:prdk
    },{
        path:'/customer',
        component:cs
    },{
        path:'/invoice',
        component:inv
    },{
        path:'/detailINV/:id',
        name:'dinv',
        component:dinv
    },{
        path:'/Editprdk/:id',
        name:'Editprdk',
        component:Editprdk
    },{
        path:'/Editcus/:id',
        name:'Editcus',
        component:Editcus
    }
  ]

const router = new Router({routes})

const vuetifyOptions = {
    icons:{
        inconfont: 'mdi'
    }
 }

const app = new Vue({
    el: '#app',

    methods: {
        async Authcek_(){
            const data =await Authcek()
            console.log(data);
            localStorage.setItem('name',data.body.name)
            localStorage.setItem('email',data.body.email)
            if (data.body.status == 200 || data.status == 400) {
                router.push({path:'/login'})
            }
        },
        NoAuth(){
            router.push({path:'/login'})
        },
        token(){
            return localStorage.getItem("token");
        },
        notifWarning(isi,title){
            this.$snotify.warning(isi, title, {
                timeout: 2000,
                showProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true
              });
        },notifSucces(isi,title){
            this.$snotify.success(isi, title, {
                timeout: 2000,
                showProgressBar: true,
                closeOnClick: true,
                pauseOnHover: true
              });
        },
    },
    vuetify: new Vuetify(vuetifyOptions),
    router,
    components:{App},
});
