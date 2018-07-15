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
};

export function extendVue(options) {
  const v = Object.assign({}, BaseVue, options);
  v.computed = Object.assign({},BaseVue.computed, options.computed || {});
  return v;
}
