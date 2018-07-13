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
  router-view
</template>

<script>
export default {
};
</script>
