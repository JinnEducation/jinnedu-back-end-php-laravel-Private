import { createApp } from 'vue'
import store from './store'
import router from './router'
import {i18n} from './i18n'
//import './style.css'
import App from './App.vue'

const app = createApp(App);
app.config.globalProperties.scriptEle = [];
    app.use(store)
    .use(router)
    .use(i18n)
    .mount('#app')
