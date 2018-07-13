<template lang="pug">
.container
  //-
    template(v-if="null === bsAccounts") ロード中...
    template(v-else)
  button.btn.btn-default(type="button")
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
</template>

<script>
import { mapState } from 'vuex';
import axios from 'axios';

export default {
  data () {
    return {
      bsAccounts: null,
    };
  },
  computed: mapState(['apiRoot']),
  mounted () {
    axios.get(this.apiRoot + '/account/bs-account').then(res => {
      console.log(res);
      this.bsAccounts = res.data.data;
    }).catch(err => {
      console.error(err);
      alert('エラー！: ' + err);
    });
  },
};
</script>
