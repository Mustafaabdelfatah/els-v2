/**
 *
 * ComponentsToasts
 *
 * Interface.Components.Toasts.html page content scripts. Initialized from scripts.js file.
 *
 *
 */

class ComponentsToasts {
  constructor(message, type) {
    let toastsContainer = document.getElementById('toastsContainer');
    if (!toastsContainer) {
      toastsContainer = document.createElement('div');
      toastsContainer.id = 'toastsContainer';
      toastsContainer = document.body.appendChild(toastsContainer);
      toastsContainer.setAttribute('style', 'position: fixed;width: max-content;height: 100%;top: 0;z-index: 10000;');
    }
    let liveToastEl = document.getElementById('toast').cloneNode(true);
    liveToastEl.id = liveToastEl.id + '-' + Date.now();
    liveToastEl.getElementsByClassName('message')[0].innerHTML = message;
    liveToastEl.getElementsByClassName('toast')[0].classList.add('bg-'+type);
    liveToastEl = toastsContainer.appendChild(liveToastEl)

    if (liveToastEl) {
      let _liveToast = new bootstrap.Toast(liveToastEl.firstChild);
      _liveToast.show();
      liveToastEl.addEventListener('hidden.bs.toast', function () {
        setTimeout(() => {
          liveToastEl.remove();
        }, 1000);
      });
    }
  }
}
