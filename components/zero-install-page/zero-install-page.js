(function($) {
  'use strict';

  Drupal.zero.createComponent('zero-install-page', {

    info: null,
    items: {},

    init: function () {
      this.info = this.element('info');
      this.element('item').each((index, element) => {
        const el = $(element);
        const item = {
          id: el.data('id'),
          item: el,
          button: Drupal.zero.getComponent(el.find('.zero-install-button')),
          state: Drupal.zero.getComponent(el.find('.zero-install-state')),
        };
        this.items[item.id] = item;

        item.button.item.on('click', (e) => {
          e.preventDefault();

          this.onClick(item);
        });
        this.check(item);
      });
    },

    check: function (item) {
      this.request('zero.install', { plugin_id: item.id, action: 'check' }, 'json', (ajax, data, meta) => {
        this.info.html('');
        if (data.valid) {
          item.state.setMode('success', 'Fully Installed');
          item.button.setMode('overwrite');
        } else {
          console.log(data);
          const moreInfo = {};
          for (const missing of data.missing) {
            switch (missing.context.type) {
              case 'component':
                item.state.setMode('error', 'Component not installed.');
                item.button.setMode('install');
                return;
              case 'component.file':
                moreInfo.components = moreInfo.components || {};
                const key = missing.context.component.category + '/' + missing.context.component.component;

                item.state.setMode('warning', 'Component not fully installed.');
                item.button.setMode('complete');
                moreInfo.components[key] = moreInfo.components[key] || [];
                moreInfo.components[key].push('Missing file: ' + missing.file);
                break;
              case 'config':
                moreInfo.configs = moreInfo.configs || [];
                item.state.setMode('warning', 'Config not fully installed.');
                item.button.setMode('complete');
                moreInfo.configs.push('Missing config: ' + missing.config);
                break;
            }
          }

          let string = '';
          if (moreInfo.components) {
            for (const name in moreInfo.components) {
              string += '<div>Component: ' + name + '</div><ul>';
              for (const line of moreInfo.components[name]) {
                string += '<li>' + line + '</li>'
              }
              string += '</ul>';
            }
          }
          if (moreInfo.configs) {
            string += '<div>Configs:</div><ul>';
            for (const index in moreInfo.configs) {
              string += '<li>' + moreInfo.configs[index] + '</li>'
            }
            string += '</ul>';
          }
          this.info.html(string);
        }
      });
    },

    onClick: function(item) {
      const mode = item.button.getCurrent().mode;
      if (mode === 'progress') return;
      item.button.setMode('progress');
      this.request('zero.install', { plugin_id: item.id, action: 'install', mode: mode }, 'json', (ajax, data, meta) => {
        if (data.state === 'ok') {
          this.check(item);
          item.button.setMode('overwrite');
        }
      });
    },

  });

})(jQuery);
