<template lang="pug">
div
  .navbar.navbar-expand-sm.navbar-light.bg-light
    router-link.navbar-brand(to="/") すとれりちあ
    button.navbar-toggler(type="button" aria-label="メニュー開閉" data-toggle="collapse"
        data-target="#stre-navbar" area-controls="stre-navbar" aria-expanded="false")
      span.navbar-toggler-icon
    #stre-navbar.collapse.navbar-collapse: ul.navbar-nav.mr-auto
      li.nav-item: router-link.nav-link(to="/bs-account") 資産・負債科目
      li.nav-item: router-link.nav-link(to="/pl-account") 収益・費用科目
  .container(v-if="null === accountTitles") ロード中...
  router-view(v-else)
</template>

<script>
import { extendVue } from '@/core/vue';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  computed: AccountModule.mapState(['accountTitles']),
  methods: AccountModule.mapActions({
    loadAllAccountData: AccountModule.actionKey.LOAD_ALL,
    streReload() {
      this.loadAllAccountData().catch(err => {
        alert('エラー！: ' + err);
      });
    },
  }),
});
</script>
