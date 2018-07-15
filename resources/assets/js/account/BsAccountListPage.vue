<template lang="pug">
include /components/mixin

.container
  button.btn.btn-default(type="button" @click="create")
    +bsIcon("plus")
    span &nbsp;追加
  table.table.table-bordered.table-striped
    thead
      tr
        th 操作
        th タイプ
        th パス
        th 名称
    tbody
      tr(v-for="a of bsAccounts")
        td: button.btn.btn-primary(type="button" @click="edit(a.id)"): +bsIcon("edit")
        td(v-t="'enum.accountType.' + a.type")
        td {{ a.path }}
        td {{ a.name }}
  modal(ref="createDlg")
    template(slot="title") 資産・負債科目の
      template(v-if="editing.bsAccount.id") 編集
      template(v-else) 追加
    template(v-if="null === editing.openingBalance") ロード中...
    form(v-else)
      .form-group
        label 科目名
        input.form-control(v-model="editing.bsAccount.name" required)
      .form-group
        label タイプ
        select.form-control(v-model="editing.bsAccount.type" required)
          option(v-for="t in Object.keys(targetTypes)"
              :value="t" v-t="'enum.accountType.' + t"
              :key="'editing-account-type-choice-' + t")
      .form-group
        label 親科目
        select.form-control(v-model="editing.bsAccount.parentId")
          option(value="0") （なし）
          option(v-for="a in parentCandidates" :value="a.id"
              :key="'editing-account-parent-choice-' + a.id") {{ a.name }}
      .form-group
        label 開始残高
        input.form-control(v-model="editing.openingBalance" type="number" required)
      button.btn.btn-primary(type="button" @click="doSave") 保存
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';

import { csvContains } from '@/util/lang';
import { mapConstants } from '@/util/vue-util';

import { AccountTitleType } from '@/account/constants';
import { AccountModule } from '@/account/AccountModule';

const targetTypes = {
  [AccountTitleType.ASSET]: true,
  [AccountTitleType.LIABILITY]: true,
  [AccountTitleType.NET_ASSET]: true,
};

export default {
  data () {
    return {
      editing: {
        bsAccount: {
          name: '',
          type: AccountTitleType.ASSET,
          parentId: 0,
        },
        openingBalance: 0,
      }
    };
  },
  computed: {
    ...mapConstants({targetTypes}),
    ...mapState(['apiRoot']),
    ...AccountModule.mapState(['accountTitles', 'accountTitleMap']),
    bsAccounts () {
      return (this.accountTitles || []).filter(a => targetTypes[a.type]);
    },
    parentCandidates () {
      return this.bsAccounts
        .filter(a => !csvContains(a.path, this.editing.bsAccount.path, ' / '));
    },
  },
  methods: {
    ...AccountModule.mapActions([AccountModule.actionKey.LOAD_ALL]),
    create () {
      this.editing = this.$options.data().editing;
      this.$refs.createDlg.open();
    },
    edit (id) {
      this.editing = {
        bsAccount: Object.assign({}, this.accountTitleMap[id]),
        openingBalance: null,
      };
      this.$refs.createDlg.open();
      axios.get(`${this.apiRoot}/journal/opening/${this.editing.bsAccount.id}`).then(res => {
        this.editing.openingBalance = res.data.data.amount;
      }).catch(() => {
        alert('開始残高を取得できません');
      });
    },
    doSave () {
      const promise = this.editing.bsAccount.id
        ? axios.put(`${this.apiRoot}/account/bs-account/${this.editing.bsAccount.id}`, this.editing)
        : axios.post(`${this.apiRoot}/account/bs-account`, this.editing);
      promise.then(() => {
        alert(`勘定科目「${this.editing.bsAccount.name}」を保存しました`);
        this[AccountModule.actionKey.LOAD_ALL]();
        this.$refs.createDlg.close();
      }).catch(err => {
        alert(`勘定科目「${this.editing.bsAccount.name}」の保存に失敗しました:  ${err}`);
      });
    },
  },
};
</script>
