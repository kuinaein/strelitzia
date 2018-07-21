<template lang="pug">
include /components/mixins
- entryLabelClass = 'col-form-label col-sm-3'
- entryControlClass = 'col-sm-9'

.container
  h1 [
    span(v-t="'enum.accountType.' + accountTitleMap[accountId].type")
    template ] {{ accountTitleMap[accountId].name }} - {{ month }}
  p(v-if="null === journals") ロード中...
  template(v-else)
    p.clearfix
      router-link.btn.btn-info.pull-left(
          :to="{name: 'ledger', params: {accountId: accountId, month: prevMonth}}")
        +faIcon("arrow-left")
        template {{ prevMonth }}
      router-link.btn.btn-info.pull-right(
          :to="{name: 'ledger', params: {accountId: accountId, month: nextMonth}}")
        | {{ nextMonth }}
        +faIcon("arrow-right", "stre-fa--right")
    button.btn.btn-primary(type="button" @click="makeEntry")
      +faIcon("plus")
      template 記帳
    p(v-if="0 === journals.length") （記帳されたデータがありません）
    table.table.table-striped.table-bordered(v-else)
      thead: tr
        th 操作
        th 番号
        th 日付
        th 相手科目
        th 増加
        th 減少
        th 残高
      tbody: tr(v-for="j of journals" :key="'journal-' + j.id")
        td: button.btn.btn-warning(type="button" @click="editEntry(j)")
          +faIcon("edit")
          template 修正
        td {{ j.id }}
        td {{ j.journalDate }}
        th {{ accountTitleMap[j.debitAccountId === parseInt(accountId) ? j.creditAccountId : j.debitAccountId].name }}
        template(v-if="isDebitSide === (j.debitAccountId === parseInt(accountId))")
          td.text-right {{ formatCurrency(j.amount) }}
          td
        template(v-else)
          td
          td.text-right {{ formatCurrency(j.amount) }}
        td.text-right {{ formatCurrency(j.balance) }}
  modal(ref="entryDlg")
    template(slot="title") 記帳
      template(v-if="null !== editingEntry.id") 修正
    form
      .form-group.row
        label(class=entryLabelClass) 日付
        div(class=entryControlClass)
          input.form-control(type="date" v-model="editingEntry.journalDate" autofocus required)
      .form-groupr.row
        label(class=entryLabelClass) 口座
        div(class=entryControlClass)
          input.form-control-plaintext(readonly :value="accountTitleMap[accountId].name")
      .form-group.row
        label(class=entryLabelClass) 相手科目
        div(class=entryControlClass)
          select.form-control(v-model="editingEntry.anotherAccountId" required)
            option(v-for="a of accountTitles"
                v-if="'' === a.systemKey && a.id !== parseInt(accountId)"
                :value="a.id" :key="'ledger-another-account-' + a.id") [
              span(v-t="'enum.accountType.' + accountTitleMap[a.id].type")
              template ]{{ a.name }}
      .form-group.row
        label(class=entryLabelClass) 金額
        div(class=entryControlClass)
          input.form-control(type="number" v-model="editingEntry.amount" required)
      .form-group.row: div.offset-sm-3(class=controlClass)
        button.btn.btn-primary(type="button" @click="doMakeEntry()") 保存
        +modalCloseBtn("キャンセル")
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
      editingEntry: {
        id: null,
        journalDate: moment().format('YYYY-MM-DD'),
        anotherAccountId: null,
        amount: '',
      },
    };
  },
  props: ['accountId', 'month'],
  computed: {
    ...AccountModule.mapState([
      AccountModule.stateKey.accountTitles,
      AccountModule.stateKey.accountTitleMap,
    ]),
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
    streInit () {
      Object.assign(this, this.$options.data());
      axios.get(`${this.apiRoot}/journal/ledger/${this.accountId}/${this.month}`).then(res => {
        const data = res.data.data;
        data.journals.sort((a, b) => {
          if (a.journalDate !== b.journalDate) {
            return a.journalDate < b.journalDate ? 1 : -1;
          }
          return a.id < b.id ? 1 : -1;
        });
        let balance = data.beginningBalance;
        for (let i = data.journals.length - 1; 0 <= i; --i) {
          const j = data.journals[i];
          if (this.isDebitSide === (j.debitAccountId === parseInt(this.accountId))) {
            balance += j.amount;
          } else {
            balance -= j.amount;
          }
          j.balance = balance;
        }
        this.journals = data.journals;
      }).catch(err => {
        alert('エラー！: ' + err);
      });
    },
    makeEntry () {
      this.editingEntry = this.$options.data().editingEntry;
      this.$refs.entryDlg.open();
    },
    doMakeEntry () {
      if (!this.editingEntry.anotherAccountId) {
        alert('相手科目を選択してください');
        return;
      }
      const postData = {
        id: this.editingEntry.id || undefined,
        journalDate: this.editingEntry.journalDate,
        amount: Math.abs(this.editingEntry.amount),
      };
      const anotherAccountType =
          this.accountTitleMap[this.editingEntry.anotherAccountId].type;
      const isAnotherDebitSide = AccountTitleTypeDesc[anotherAccountType].isDebitSide;

      if (isAnotherDebitSide ===  (0 < this.editingEntry.amount)) {
        postData.debitAccountId = this.editingEntry.anotherAccountId;
        postData.creditAccountId = this.accountId;
      } else {
        postData.debitAccountId = this.accountId;
        postData.creditAccountId = this.editingEntry.anotherAccountId;
      }
      const promise = this.editingEntry.id
        ? axios.put(`${this.apiRoot}/journal/ledger/${this.accountId}/${postData.id}`, postData)
        : axios.post(`${this.apiRoot}/journal/ledger/${this.accountId}`, postData);
      promise.then(() => {
        alert('保存しました');
        this.$refs.entryDlg.close();
        this.streInit();
      }).catch(err => {
        alert('保存に失敗しました: ' + err);
      });
    },
    editEntry (journal) {
      this.editingEntry = Object.assign({}, journal);
      this.setAnotherAccount(this.editingEntry,
        journal.debitAccountId !== parseInt(this.accountId));
      this.$refs.entryDlg.open();
    },
    /**
     * @param {Boolean} isAnotherDebitSide
     */
    setAnotherAccount (entry, isAnotherDebitSide) {
      entry.anotherAccountId = isAnotherDebitSide ? entry.debitAccountId : entry.creditAccountId;
      const type = this[AccountModule.stateKey.accountTitleMap][entry.anotherAccountId].type;
      if (isAnotherDebitSide !== AccountTitleTypeDesc[type].isDebitSide) {
        entry.amount = -entry.amount;
      }
    },
  },
});
</script>
