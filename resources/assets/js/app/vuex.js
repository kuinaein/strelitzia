import Vue from 'vue';
import Vuex from 'vuex';

import { AccountModule } from '@/account/AccountModule';

Vue.use(Vuex);

export const store = new Vuex.Store({
  state: {
    apiRoot: '/api',
  },
  modules: {
    [AccountModule.namespace]: AccountModule.vuexModule,
  },
});
