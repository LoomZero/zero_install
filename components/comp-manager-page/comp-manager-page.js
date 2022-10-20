(function($) {
  'use strict';

  Drupal.zero.createComponent('comp-manager-page', {

    init: function () {
      this.search = this.element('search');

      this.search.on('keydown', this.debounce(this.onSearch.bind(this), 300));
    },

    debounce: function(func, wait) {
      const that = this;
      let timeout = null;
      return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
          timeout = null;
          func.apply(that, args);
        }, wait);
      };
    },

    onSearch: function (e) {
      this.doList();
    },

    doList: function () {
      const params = {
        action: 'list',
        search: this.search.val(),
      };

      this.request('zero.comp_manager', params, 'json', (ajax, data, meta) => {
        console.log(ajax, data, meta);
      });
    },

  });

})(jQuery);
