import { mapState } from 'vuex';

const BaseVue = {
  computed: mapState(['apiRoot']),
  watch: { '$route': '__routeChangeCallBack' },
  mounted () {
    this.streInit && this.streInit();
  },
  methods: {
    __routeChangeCallBack () {
      this.streInit && this.streInit();
    },
    formatCurrency (n) {
      return n.toLocaleString('ja-JP', {style: 'currency', currency: 'JPY'});
    },
  },
};

export function extendVue(options) {
  const v = Object.assign({}, BaseVue, options);
  v.computed = Object.assign({},BaseVue.computed, options.computed || {});
  v.methods = Object.assign({},BaseVue.methods, options.methods || {});
  return v;
}
