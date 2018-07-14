<template lang="pug">
.container
  //-
    template(v-if="null === bsAccounts") ロード中...
    template(v-else)
  button.btn.btn-default(type="button" @click="create")
    span.glyphicon.glyphicon-plus
    span &nbsp;追加
  table.table.table-bordered.table-striped
    thead
      tr
        th タイプ
        th 名称
    tbody
      tr(v-for="a of bsAccounts")
        td(v-t="'enum.accountType.' + a.type")
        td {{ a.name }}
  modal(ref="createDlg")
    template(slot="title") 資産・負債科目の追加
    form
      .form-group
        label 科目名
        input.form-control(v-model="editingAccount.name" required)
      .form-group
        label タイプ
        select.form-control(v-model="editingAccount.type" required)
          option(v-for="t in [AccountTitleType.ASSET, AccountTitleType.LIABILITY, AccountTitleType.NET_ASSET]"
              :value="t" v-t="'enum.accountType.' + t"
              :key="'editing-account-type-choice-' + t")
      .form-group
        label 親科目
        select.form-control(v-model="editingAccount.parentId")
          option(value='0') （なし）
          option(v-for="a in bsAccounts" :value="a.id"
              :key="'editing-account-parent-choice-' + a.id") {{ a.name }}
      button.btn.btn-primary(type="button" @click="doCreate") 追加
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';

import { mapConstants } from '@/util/vue-util';

import { AccountTitleType } from '@/account/constants';

export default {
  data () {
    return {
      bsAccounts: null,
      editingAccount: {
        id: 0,
        name: '',
        type: AccountTitleType.ASSET,
        parentId: 0,
      },
    };
  },
  computed: {
    ...mapConstants({AccountTitleType}),
    ...mapState(['apiRoot']),
  },
  mounted () {
    axios.get(this.apiRoot + '/account/bs-account').then(res => {
      this.bsAccounts = res.data.data;
    }).catch(err => {
      alert('エラー！: ' + err);
    });
  },
  methods: {
    create () {
      this.editingAccount = this.$options.data().editingAccount;
      this.$refs.createDlg.open();
    },
    doCreate () {
      axios.post(this.apiRoot + '/account/bs-account', this.editingAccount).then(res => {
        this.bsAccounts.push(res.data.data);
        alert(`勘定科目「${this.editingAccount.name}」を追加しました`);
      }).catch(err => {
        alert(`勘定科目「${this.editingAccount.name}」の追加に失敗しました:  ${err}`);
      });
      this.$refs.createDlg.close();
    },
  },
};
</script>
