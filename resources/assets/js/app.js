
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import Vue from 'vue';
import VueI18n from 'vue-i18n';

import strings from '@/app/strings.json';

import { router } from '@/app/router';
import { store } from '@/app/vuex';

import Modal from '@/components/Modal';

import Frame from '@/app/Frame';

if ('development' === process.env.NODE_ENV) {
  window.axios.interceptors.response.use(res => {
    console.log(res);
    return res;
  }, err => {
    console.error(err);
    console.error(err.response);
    throw err;
  });
}

window.axios.interceptors.response.use(res => res, err => {
  if (!err.response || !err.response.data) {
    throw err;
  }
  const data = err.response.data;
  if (422 === err.response.status && data.errors) {
    // バリデーションエラー
    let buf = '';
    for (const field in data.errors) {
      buf += `\n* ${field}`;
      for (const f of data.errors[field]) {
        buf += `\n  * ${f}`;
      }
    }
    throw new Error(buf);
  }
  throw new Error(JSON.stringify(data));
});

window.Vue = Vue;
Vue.use(VueI18n);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('modal', Modal);

const i18n = new VueI18n({
  locale: 'ja',
  messages: strings,
});

window.theApp = new Vue(Object.assign({}, Frame, {
  el: '#app',
  router,
  store,
  i18n,
}));
