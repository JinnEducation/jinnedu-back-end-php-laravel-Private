import { createI18n } from "vue-i18n";
import messages from "@intlify/unplugin-vue-i18n/messages";

/**
 * Load locale messages
 * https://levelup.gitconnected.com/how-to-build-a-multi-language-vue-3-vite-app-b91c34c46097
 * The loaded `JSON` locale messages is pre-compiled by `@intlify/vue-i18n-loader`, which is integrated into `vue-cli-plugin-i18n`.
 * See: https://github.com/intlify/vue-i18n-loader#rocket-i18n-resource-pre-compilation
 * https://github.com/robinkloeckner/vue3_vite_i18n
 * https://stackoverflow.com/questions/68802337/vue-js-i18n-load-external-jsons-from-server-and-make-it-globally-accessible-i
 */
/*function loadLocaleMessages() {
  const locales = require.context(
    "./locales",
    true,
    /[A-Za-z0-9-_,\s]+\.json$/i
  );
  const messages = {};
  locales.keys().forEach((key) => {
    const matched = key.match(/([A-Za-z0-9-_]+)\./i);
    if (matched && matched.length > 1) {
      const locale = matched[1];
      messages[locale] = locales(key).default;
    }
  });
  return messages;
}*/

export const i18n = createI18n({
  legacy: false,
  globalInjection: true,
  locale: sessionStorage.getItem('LANGUAGE') || "ar",
  fallbackLocale: sessionStorage.getItem('LANGUAGE') || "ar",
  availableLocales: ["en", "ar", "fr"],
  messages: messages,
});

const loadedLanguages = []

function setI18nLanguage (lang) {
    i18n.locale = lang
    axios.defaults.headers.common['Accept-Language'] = lang
    document.querySelector('html').setAttribute('lang', lang)
    return lang
}

export function loadLanguageAsync (lang) {
    console.log('loadLanguageAsync=>'+lang);
    if (typeof lang === 'undefined') {
        console.log('loadLanguageAsync => undefined =>'+lang);
        lang = window.navigator.userLanguage || window.navigator.language;
        lang = lang.slice(0,2);
    }
    if (loadedLanguages.includes(lang)) {
        console.log('loadLanguageAsync => includes =>'+lang);
        if (i18n.locale !== lang) setI18nLanguage(lang)
        return Promise.resolve()
    }
    return fetch(`https://kwctf.com/vue/laravel-vue-survey/public/api/locales/lang/${lang}`)
          .then(response => response.json())
          .then(msgs => {
             console.log('loadLanguageAsync => fetch =>'+lang);
             loadedLanguages.push(lang)
             i18n.global.setLocaleMessage(lang, msgs)
             return setI18nLanguage(lang)
           });
}

/*export default createI18n({
  legacy: false,
  globalInjection: true,
  locale:  "ar", //process.env.VUE_APP_I18N_LOCALE ||
  fallbackLocale: "ar", // process.env.VUE_APP_I18N_FALLBACK_LOCALE ||
  availableLocales: ["en", "ar"],
  messages: messages, //loadLocaleMessages(),
});*/