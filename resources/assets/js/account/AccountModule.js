import { createNamespacedHelpers } from 'vuex';
import axios from 'axios';

const mutaionKey = {
  CACHE_ACCOUNT_TITLES: 'CACHE_ACCOUNT_TITLES',
};

const actionKey = {
  LOAD_ALL: 'LOAD_ALL',
};

export const AccountModule = {
  namespaced: true,
  namespace: 'account',
  mutaionKey,
  actionKey,
  state: {
    accountTitles: null,
  },
  mutations: {
    [mutaionKey.CACHE_ACCOUNT_TITLES] (state, accountTitles) {
      state.accountTitles = accountTitles;
    },
  },
  actions: {
    [actionKey.LOAD_ALL] ({commit, rootState}) {
      commit(mutaionKey.CACHE_ACCOUNT_TITLES, null);
      return axios.get(rootState.apiRoot + '/account').then(res => {
        commit(mutaionKey.CACHE_ACCOUNT_TITLES, res.data.data);
      });
    },
  },
};

Object.assign(AccountModule, createNamespacedHelpers(AccountModule.namespace));
