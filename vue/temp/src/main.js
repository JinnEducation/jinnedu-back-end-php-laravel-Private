import { createApp } from 'vue'
import store from './store'
import router from './router'
//import './style.css'
import App from './App.vue'

const app = createApp(App);
app.config.globalProperties.scriptEle = [];

app.config.globalProperties.menuPlacementBottom = "bottom-end";
app.config.globalProperties.menuPlacementStart = "left-start";


app.use(store)
    .use(router)
    .mount('#app')
