<template lang="pug">
include /components/mixins

.container
  h1 総勘定元帳 - {{ accountTitleMap[accountId].name }} [{{ month }}]
  p(v-if="null === journals") ロード中...
  template(v-else)
    p.clearfix
      router-link.btn.btn-info.pull-left(
          :to="{name: 'ledger', params: {accountId: accountId, month: prevMonth}}")
        +faIcon("arrow-left")
        template &nbsp;{{ prevMonth }}
      router-link.btn.btn-info.pull-right(
          :to="{name: 'ledger', params: {accountId: accountId, month: nextMonth}}")
        | {{ nextMonth }}&nbsp;
        +faIcon("arrow-right")
    p(v-if="0 === journals.length") （記帳データがありません）
    table.table.table-striped.table-bordered(v-else)
      thead: tr
        th 日付
        th 相手科目
        th 増加
        th 減少
      tbody: tr(v-for="j of journals" :key="'journal-' + j.id")
        td {{ j.journalDate }}
        th {{ accountTitleMap[j.debitAccountId === parseInt(accountId) ? j.creditAccountId : j.debitAccountId].name }}
        template(v-if="isDebitSide && j.debitAccountId === accountId")
          td.text-right {{ j.amount.toLocaleString('ja-JP', {style: 'currency', currency: 'JPY'}) }}
          td
        template(v-else)
          td
          td.text-right {{ j.amount.toLocaleString('ja-JP', {style: 'currency', currency: 'JPY'}) }}
</template>

<script>
import moment from 'moment';
import axios from 'axios';

import { extendVue } from '@/core/vue';
import { AccountTitleTypeDesc } from '@/account/constants';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  data () {
    return {
      journals: null,
    };
  },
  props: ['accountId', 'month'],
  computed: {
    ...AccountModule.mapState(['accountTitleMap']),
    prevMonth () {
      return moment(this.month, 'YYYY-MM').subtract(1, 'months').format('YYYY-MM');
    },
    nextMonth () {
      return moment(this.month, 'YYYY-MM').add(1, 'months').format('YYYY-MM');
    },
    isDebitSide () {
      return AccountTitleTypeDesc[this.accountTitleMap[this.accountId].type].isDebitSide;
    },
  },
  methods: {
    streReload () {
      axios.get(`${this.apiRoot}/journal/ledger/${this.accountId}/${this.month}`).then(res => {
        this.journals = res.data.data;
      }).catch(err => {
        alert('エラー！: ' + err);
      });
    },
  },
});
</script>
