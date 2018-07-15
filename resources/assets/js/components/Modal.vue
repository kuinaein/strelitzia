<template lang="pug">
.modal.fade(tabindex="-1"): .modal-dialog: .modal-content
  .modal-header
    h5.modal-title: slot(name="title")
    button.close(type="button" data-dismiss="modal") x
  .modal-body
    slot
</template>

<script>
export default {
  methods: {
    open () {
      jQuery(this.$el).modal('show');
    },
    close () {
      jQuery(this.$el).modal('hide');
    },
  },
};
</script>
