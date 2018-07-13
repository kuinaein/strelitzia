
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import Vue from 'vue';
import VueRouter from 'vue-router';
import VueI18n from 'vue-i18n';
import Vuex from 'vuex';

import strings from '@/strings.json';

import Frame from '@/core/Frame';
import BsAccountListPage from '@/core/BsAccountListPage';


window.Vue = Vue;
Vue.use(VueRouter);
Vue.use(VueI18n);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/bs-account',
      name: 'bs-account-list',
      component: BsAccountListPage,
    },
  ],
});

const i18n = new VueI18n({
  locale: 'ja',
  messages: strings,
});

const store = new Vuex.Store({
  state: {
    apiRoot: '/api'
  },
});

window.theApp = new Vue(Object.assign({}, Frame, {
  el: '#app',
  router,
  i18n,
  store,
}));
