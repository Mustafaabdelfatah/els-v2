import Vue from 'vue';

const globals = {
  linkParser(text) {
    if (text != null)
      return text.replace(/(\b(https?|ftp|file):\/\/[\-A-Z0-9+&@#\/%?=~_|!:,.;]*[\-A-Z09+&@#\/%=~_|])/img, `<a href='$1' target='_blank'>$1</a>`).replace(/\n/g, '<br>')
    return '';
  },
}

Vue.prototype.$globals = globals;

export default ({app}, inject) => {
  inject('globals', globals);
}
