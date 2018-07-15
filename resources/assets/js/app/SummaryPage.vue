<template lang="pug">
.container
  div(v-if="null === accountSummary") 集計中...
  template(v-else)
    .card(v-for="t of AccountTitleType"
        v-if="AccountTitleTypeDesc[t].statements[FinancialStatementType.BALANCE_SHEET]"
        :key="'summary-group-' + t" )
      .card-header(v-t="'enum.accountType.' + t")
      .card-body: table.table.table-striped: tbody
        tr(v-for="a of accountSummary[t]"
            :key="'summary-for-account-' + a.accountId")
          th {{ accountTitleMap[a.accountId].name }}
          td.text-right {{ a.amount.toLocaleString('ja-JP', {style: 'currency', currency: 'JPY'}) }}
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';

import { AccountTitleType, AccountTitleTypeDesc, FinancialStatementType } from '@/account/constants';

import { mapConstants } from '@/util/vue-util';
import { AccountModule } from '@/account/AccountModule';

export default {
  data () {
    return {
      accountSummary: null,
    };
  },
  computed: {
    ...mapConstants({AccountTitleType, AccountTitleTypeDesc, FinancialStatementType}),
    ...mapState(['apiRoot']),
    ...AccountModule.mapState(['accountTitleMap']),
  },
  mounted () {
    axios.post(this.apiRoot + '/journal/trial-balance', {
      accountTypes: [AccountTitleType.ASSET, AccountTitleType.LIABILITY, AccountTitleType.NET_ASSET]
    }).then(res => {
      const data = res.data.data;
      const accountSummary = {
        [AccountTitleType.ASSET]: [],
        [AccountTitleType.LIABILITY]: [],
        [AccountTitleType.NET_ASSET]: [],
      };
      for (const accountId in data) {
        accountSummary[this.accountTitleMap[accountId].type].push({
          accountId, amount: data[accountId]
        });
      }
      for (const s of Object.values(accountSummary)) {
        s.sort((a, b) => {
          return this.accountTitleMap[a.accountId].order -
              this.accountTitleMap[b.accountId].order;
        });
      }
      this.accountSummary = accountSummary;
    }).catch(err => {
      alert('エラー: ' + err);
    });
  },
};
</script>
