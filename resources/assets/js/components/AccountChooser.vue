<template lang="pug">
select(:value="value" @input="handleInput")
  option(v-for="a of accountTitles"
      v-if="'' === a.systemKey && a.id !== parseInt(exclude)"
      :value="a.id" :key="'account-chooser-choice-' + a.id") [
    span(v-t="'enum.accountType.' + accountTitleMap[a.id].type")
    template ]{{ a.name }}
</template>

<script>
import { extendVue } from '@/core/vue';
import { AccountModule } from '@/account/AccountModule';

export default extendVue({
  props: ['value', 'exclude'],
  computed: {
    ...AccountModule.mapState([
      AccountModule.stateKey.accountTitles,
      AccountModule.stateKey.accountTitleMap,
    ]),
  },
  methods: {
    handleInput (ev, o) {
      const select = ev.target;
      this.$emit('input', select[select.selectedIndex]._value);
    },
  },
});
</script>
