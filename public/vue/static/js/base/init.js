/**
 *
 * Init.js
 *
 * Shows the template after initialization of the settings, nav, variables and common plugins.
 *
 *
 */

(function () {
  window.addEventListener('DOMContentLoaded', () => {
    // Variables to hold component instances that may require an update after certain events

    // Settings initialization
    if (typeof Settings !== 'undefined') {
      const settings = new Settings({
        attributes: {placement: 'horizontal'},
        showSettings: true,
        storagePrefix: 'acorn-standard-'
      });
    }

    // Variables initialization of Globals.js file which contains valus from css
    if (typeof Variables !== 'undefined') {
      const variables = new Variables();
    }

    // Initializing component and plugin classes
    function initBase() {
      // Should be before everything
      window.waitForEl('#nav', (element) => {
        const nav = new Nav(document.getElementById('nav'));
      });

      // Search implementation
      if (typeof Search !== 'undefined') {
        const search = new Search();
      }

      // CS Line Icons initialization
      if (typeof csicons !== 'undefined') {
        csicons.replace();
      }
    }

    // Initializing of scripts.js file
    function initScripts() {
      if (typeof Scripts !== 'undefined') {
        const scripts = new Scripts();
      }
    }

    document.documentElement.addEventListener(Globals.menuPlacementChange, (event) => {
      setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
      }, 25);
    });

    document.documentElement.addEventListener(Globals.layoutChange, (event) => {
      setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
      }, 25);
    });

    document.documentElement.addEventListener(Globals.menuBehaviourChange, (event) => {
      setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
      }, 25);
    });

    window.waitForEl = function (selector, callback, tries = 0) {
      if (tries > 100)
        return;
      if (jQuery(selector).length)
        callback(jQuery(selector));
      else
        setTimeout(function () {
          waitForEl(selector, callback, ++tries);
        }, 100);
    }
    window.showModal = function (selector) {
      if (jQuery(selector).length)
        jQuery(selector).modal('show');
    }
    window.jQueryInit = function () {
      document.documentElement.setAttribute('data-show', 'true');
      document.body.classList.remove('spinner');
      initBase();
      initScripts();
    }
    window.jQueryInitContextMenu = function (selector = '#contextMenu', type = 'basic') {
      if (jQuery(selector))
        new ContextMenu(selector, type);
    }

    window.jQueryStartLoading = function () {
      document.body.classList.add('spinner');
    }

    window.jQueryEndLoading = function () {
      document.body.classList.remove('spinner');
    }

    window.jQueryDatatableReload = function () {
      let closeButtons = document.getElementsByClassName('closeAndReload');
      if (closeButtons != null && closeButtons.length) {
        Object.values(closeButtons)[0].click();
      } else if (document.getElementById('addEditConfirmButton'))
        document.getElementById('addEditConfirmButton').click();
      else if (document.getElementById('assignButton'))
        document.getElementById('assignButton').click();
      else if (document.getElementById('addAssignAdminConfirmButton'))
        document.getElementById('addAssignAdminConfirmButton').click();
    }

    window.jQueryToast = function (message, type = 'secondary') {
      new ComponentsToasts(message, type);
    }

    window.jQueryAutocomplete = function (inputID, resultsID, url, callback = function (row) {
    }) {
      window.waitForEl('#' + inputID, () => {
        let input = document.getElementById(inputID);
        if (input) {
          new AutocompleteCustom(inputID, resultsID, {
            data: {
              src: () => {
                if (!input.value.length)
                  return [];
                return $.ajax({
                  url: url + '/' + input.value,
                  type: 'GET',
                  beforeSend: function (request) {
                    Object.keys(window.ajaxHeaders).map((key, i) => {
                      request.setRequestHeader(key, Object.values(window.ajaxHeaders)[i]);
                    });
                  }
                });
              },
              key: ['name'],
              cache: false,
            },
            placeHolder: 'Search',
            searchEngine: 'loose',
            onSelection: (feedback) => {
              let row = null;
              if (typeof feedback.selection.value != 'undefined')
                row = feedback.selection.value;
              callback(row);
              // document.getElementById(inputID).value = feedback.selection.value['name'];
              // document.getElementById(inputID).blur();
            },
          });
        }
      })
    }

    // Showing the template after waiting for a bit so that the css variables are all set
    // setTimeout(() => {
    //   document.documentElement.setAttribute('data-show', 'true');
    //   document.body.classList.remove('spinner');
    //   initBase();
    //   initScripts();
    // }, 200);
  });
})();
