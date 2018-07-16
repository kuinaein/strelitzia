import { mapState } from 'vuex';

const BaseVue = {
  computed: mapState(['apiRoot']),
  mounted () {
    this.streReload && this.streReload();
  },
  beforeRouteUpdate (to, from, next) {
    this.streReload && this.streReload();
    next();
  },
  methods: {
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
