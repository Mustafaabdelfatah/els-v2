import Vue from 'vue';
import {ValidationProvider, ValidationObserver, extend} from 'vee-validate';

import { configure } from 'vee-validate';
configure({
  classes: {
    valid: 'is-valid',
    invalid: 'is-invalid',
    dirty: ['is-dirty', 'is-dirty'], // multiple classes per flag!
    // ...
  }
});

import * as rules from 'vee-validate/dist/rules';
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});

// Add a rule.
// extend('secret', {
//   validate: value => value === 'example',
//   message: 'This is not the magic word'
// });

// Register it globally
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);


// import { localize } from 'vee-validate';
//
// function loadLocale(code) {
//   return import(`vee-validate/dist/locale/${code}.json`).then(locale => {
//     localize(code, locale);
//   });
// }
