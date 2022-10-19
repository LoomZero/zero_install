(function($) {
  'use strict';

  Drupal.zero.createComponent('zero-install-button', {

    text: null,
    current: null,

    init: function() {
      this.text = this.element('text');

      this.setMode(this.settings().mode);
    },

    getCurrent: function() {
      return this.current;
    },

    setMode: function(mode) {
      if (this.current) {
        this.state('theme-' + this.current.theme, false);
        if (this.current.state) this.state(this.current.state, false);
      }
      this.current = this.getMode(mode);

      this.text.html(this.current.text);
      this.state('theme-' + this.current.theme, true);
      if (this.current.state) this.state(this.current.state, true);
    },

    getMode: function(mode) {
      this.getModes()[mode].mode = mode;
      return this.getModes()[mode];
    },

    getModes: function() {
      return this.settings().modes;
    },

  });

})(jQuery);
