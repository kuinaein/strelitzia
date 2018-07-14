import { createNamespacedHelpers } from 'vuex';
import axios from 'axios';

import { AccountTitleType } from '@/account/constants';

const mutaionKey = {
  CACHE_ACCOUNT_TITLES: 'CACHE_ACCOUNT_TITLES',
};

const actionKey = {
  LOAD_ALL: 'LOAD_ALL',
};

const typePos = {
  [AccountTitleType.ASSET]: 0,
  [AccountTitleType.LIABILITY]: 10,
  [AccountTitleType.NET_ASSET]: 20,
  [AccountTitleType.REVENUE]: 30,
  [AccountTitleType.EXPENSE]: 40,
  [AccountTitleType.OTHER]: 50,
};

export const AccountModule = {
  namespaced: true,
  namespace: 'account',
  mutaionKey,
  actionKey,
  state: {
    accountTitles: null,
    accountTitleMap: null,
  },
  mutations: {
    [mutaionKey.CACHE_ACCOUNT_TITLES] (state, {accountTitles, accountTitleMap}) {
      state.accountTitles = accountTitles;
      state.accountTitleMap = accountTitleMap;
    },
  },
  actions: {
    [actionKey.LOAD_ALL] ({commit, rootState}) {
      commit(mutaionKey.CACHE_ACCOUNT_TITLES, {});
      return axios.get(rootState.apiRoot + '/account').then(res => {
        const accountTitles = res.data.data;
        const accountTitleMap = {};
        for (const a of accountTitles) {
          accountTitleMap[a.id] = a;
          a.children = [];
        }
        for (const a of accountTitles) {
          if (0 !== a.parentId) {
            accountTitleMap[a.parentId].children.push(a);
          }
          let path = a.name;
          let level = 0;
          let b = a;
          while (0 !== b.parentId) {
            ++level;
            b = accountTitleMap[a.parentId];
            if (b.path) {
              path = b.path + ' / ' + path;
              level += b.level;
              break;
            } else {
              path = b.name + ' / ' + path;
            }
          }
          a.path = path;
          a.level = level;
        }

        accountTitles.sort((o1, o2) => {
          if (o1.type !== o2.type) {
            return typePos[o1.type] - typePos[o2.type];
          }
          return o1.path === o2.path ? 0 : o1.path < o2.path ? -1 : 1;
        });
        commit(mutaionKey.CACHE_ACCOUNT_TITLES, {accountTitles, accountTitleMap});
      });
    },
  },
};

Object.assign(AccountModule, createNamespacedHelpers(AccountModule.namespace));
