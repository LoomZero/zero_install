(function($) {
  'use strict';

  Drupal.zero.createComponent('zero-install-state', {

    current: null,

    init: function() {
      this.setMode(this.item.data('mode'), this.item.data('tooltip'));
      this.item.on('click', this.onClick.bind(this));
    },

    setMode: function(mode, tooltip = null) {
      if (this.current) this.state(this.current, false);
      this.current = mode;
      this.item.attr('title', tooltip);
      this.state(mode, true);
    },

    getCurrent: function() {
      return this.current;
    },

    onClick: function () {
      if (this.current && this.item.attr('title')) {
        alert(this.item.attr('title'));
      }
    },

  });

})(jQuery);
