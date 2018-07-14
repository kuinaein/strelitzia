import Vue from 'vue';
import VueRouter from 'vue-router';

import BsAccountListPage from '@/account/BsAccountListPage';

Vue.use(VueRouter);

export const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/bs-account',
      name: 'bs-account-list',
      component: BsAccountListPage,
    },
  ],
});
