/**
 *
 * ContextMenu
 *
 * Interface.Plugins.ContextMenu.html page content scripts. Initialized from scripts.js file.
 *
 *
 */

class ContextMenu {
  constructor(selector, type='basic') {
    // Initialization of the page plugins
    if (!jQuery().contextMenu) {
      console.log('contextMenu is null!');
      return;
    }

    switch (type) {
      case 'basic':
        this._initBasic(selector);
        break;
      case 'icons':
        this._initIcons(selector);
        break;
      case 'disabledItems':
        this._initDisabledItem(selector);
        break;
      case 'subItems':
        this._initSubItems(selector);
        break;
      case 'menuLeft':
        this._initMenuLeft(selector);
        break;
      case 'hove':
        this._initHover(selector);
        break;
      case 'active':
        this._initActive(selector);
        break;
    }
  }

  // Default context menu
  _initBasic(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy'},
        archive: {name: 'Archive'},
        delete: {name: 'Delete'},
      },
    });
  }

  // Context menu with icons
  _initIcons(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy', className: 'cs-duplicate'},
        archive: {name: 'Archive', className: 'cs-archive'},
        delete: {name: 'Delete', className: 'cs-bin'},
      },
    });
  }

  // Context menu with disabled item
  _initDisabledItem(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy', className: 'cs-duplicate'},
        archive: {name: 'Archive', className: 'cs-archive'},
        delete: {name: 'Delete', className: 'cs-bin', disabled: true},
      },
    });
  }

  // Context menu with sub menus
  _initSubItems(selector) {
    if (jQuery('#contextMenuSub') && typeof window.contextMenuItems != 'undefined' && Object.keys(window.contextMenuItems).length)
      jQuery.contextMenu({
        selector: selector,
        animation: {duration: 0},
        callback: function (key, e) {
          if (typeof window.contextMenuActions[key].action != 'undefined')
            window.contextMenuActions[key].action(e, ...window.contextMenuActions[key].params);
        },
        items: window.contextMenuItems || {},
      });
  }

  // Context menu with left mouse click
  _initMenuLeft(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      trigger: 'left',
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy'},
        archive: {name: 'Archive'},
        delete: {name: 'Delete'},
      },
    });
  }

  // Context menu with hover
  _initHover(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      trigger: 'hover',
      autoHide: true,
      delay: 200,
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy'},
        archive: {name: 'Archive'},
        delete: {name: 'Delete'},
      },
    });
  }

  // Context menu that sets card active via "activatable" class
  _initActive(selector) {
    jQuery.contextMenu({
      selector: selector,
      animation: {duration: 0},
      callback: function (key, options) {
        console.log('clicked: ' + key);
      },
      items: {
        copy: {name: 'Copy'},
        archive: {name: 'Archive'},
        delete: {name: 'Delete'},
      },
    });
  }
}
