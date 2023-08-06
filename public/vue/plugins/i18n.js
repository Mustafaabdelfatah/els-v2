export default function ({app, store}) {
  app.i18n.setLocale(store.state.locale);
}
