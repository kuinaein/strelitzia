<template lang="pug">
include /components/mixins

.container
  h1 収益・費用科目マスタ
  button.btn.btn-secondary(type="button" @click="create")
    +faIcon("plus")
    span &nbsp;追加
  table.table.table-bordered.table-striped
    thead
      tr
        th 操作
        th タイプ
        th 名称
        th パス
    tbody
      tr(v-for="a of plAccounts")
        td: button.btn.btn-primary(type="button" @click="edit(a.id)")
          +faIcon("edit")
          template 編集
        td(v-t="'enum.accountType.' + a.type" :class="accountTypeClass(a.type)")
        th: span(:style="'display:inline-block;padding-left: ' + (Math.max(0, a.level - 1) * 2) + 'ex'")
          template(v-if="0 !== a.level") |-
          template {{ a.name }}
        td {{ a.path }}
  modal(ref="createDlg")
    template(slot="title") 収益・費用科目の
      template(v-if="editing.plAccount.id") 編集
      template(v-else) 追加
    form
      .form-group
        label 科目名
        input.form-control(v-model="editing.plAccount.name" required)
      .form-group
        label タイプ
        div.form-control-static(v-if="editing.plAccount.id"
            v-t="'enum.accountType.' + editing.plAccount.type")
        select.form-control(v-else v-model="editing.plAccount.type" required)
          option(v-for="t of targetTypes"
              :value="t" v-t="'enum.accountType.' + t"
              :key="'editing-account-type-choice-' + t")
      .form-group
        label 親科目
        select.form-control(v-model="editing.plAccount.parentId")
          option(value="0") （なし）
          option(v-for="a in parentCandidates" :value="a.id"
              :key="'editing-account-parent-choice-' + a.id") {{ a.name }}
      button.btn.btn-primary(type="button" @click="doSave") 保存
      template &nbsp;
      button.btn.btn-secondary(type="button" data-dismiss="modal") キャンセル
</template>

<script>
import axios from 'axios';

import { csvContains } from '@/util/lang';
import { mapConstants } from '@/util/vue-util';

import { extendVue } from '@/core/vue';
import {
  ACCOUNT_PATH_SEPARATOR,
  AccountTitleType,
  AccountTitleTypeDesc,
  FinancialStatementType,
} from '@/account/constants';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  data () {
    return {
      editing: {
        plAccount: {
          name: '',
          type: AccountTitleType.REVENUE,
          parentId: 0,
        },
      },
    };
  },
  computed: {
    ...mapConstants({AccountTitleType}),
    ...AccountModule.mapState(['accountTitles', 'accountTitleMap']),
    targetTypes () {
      return Object.keys(AccountTitleTypeDesc).reduce((r, k) => {
        if (AccountTitleTypeDesc[k].statements[FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]) {
          r[k] = k;
        }
        return r;
      }, {});
    },
    plAccounts () {
      return (this.accountTitles || [])
        .filter(a => AccountTitleTypeDesc[a.type].
          statements[FinancialStatementType.PROFIT_AND_LOSS_STATEMENT]);
    },
    parentCandidates () {
      return this.plAccounts
        .filter(a => !csvContains(a.path, this.editing.plAccount.path, ACCOUNT_PATH_SEPARATOR));
    },
  },
  methods: {
    ...AccountModule.mapActions([AccountModule.actionKey.LOAD_ALL]),
    accountTypeClass (type) {
      switch (type) {
      case AccountTitleType.REVENUE:
        return 'table-success';
      case AccountTitleType.EXPENSE:
        return 'table-danger';
      }
    },
    create () {
      this.editing = this.$options.data().editing;
      this.$refs.createDlg.open();
    },
    edit (id) {
      this.editing = {
        plAccount: Object.assign({}, this.accountTitleMap[id]),
      };
      this.$refs.createDlg.open();
    },
    doSave () {
      const promise = this.editing.plAccount.id
        ? axios.put(`${this.apiRoot}/account/pl-account/${this.editing.plAccount.id}`, this.editing)
        : axios.post(`${this.apiRoot}/account/pl-account`, this.editing);
      promise.then(() => {
        alert(`勘定科目「${this.editing.plAccount.name}」を保存しました`);
        this[AccountModule.actionKey.LOAD_ALL]();
        this.$refs.createDlg.close();
      }).catch(err => {
        alert(`勘定科目「${this.editing.plAccount.name}」の保存に失敗しました:  ${err}`);
      });
    },
  },
});
</script>
