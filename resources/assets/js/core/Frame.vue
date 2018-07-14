<template lang="pug">
div
  .navbar.navbar-default: .container-fluid
    .navbar-header
      button.navbar-toggle.collapsed(type="button"
          data-toggle="collapse" data-target="#stre-navbar")
        span.sr-only メニュー開閉
        span.icon-bar
        span.icon-bar
        span.icon-bar
      router-link.navbar-brand(to="/") すとれりちあ
    .collapse.navbar-collapse#stre-navbar: ul.nav.navbar-nav
      li: router-link(to="/bs-account") 資産・負債科目
  .container(v-if="null === accountTitles") ロード中...
  router-view(v-else)
</template>

<script>
import { AccountModule } from '@/account/AccountModule';

export default {
  computed: AccountModule.mapState(['accountTitles']),
  mounted () {
    this.loadAllAccountData().catch(err => {
      alert('エラー！: ' + err);
    });
  },
  methods: AccountModule.mapActions({
    loadAllAccountData: AccountModule.actionKey.LOAD_ALL,
  }),
};
</script>
