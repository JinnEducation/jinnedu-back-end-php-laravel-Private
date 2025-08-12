import {createStore} from "vuex";
import axiosClient from "@/axios";
import { shallowRef,  ref, computed } from 'vue'
import {Layout_4_Blocks_Icon, UserAccount_Icon} from '@/components/icons/SvgIcons.vue'
import {i18n} from '@/i18n'

const Layout4BlocksIcon = shallowRef(Layout_4_Blocks_Icon)
const UserAccountIcon = shallowRef(UserAccount_Icon)

//sessionStorage.removeItem('TOKEN');

const store = createStore({
    state: {
        lang: sessionStorage.getItem('LANGUAGE'),
        user: {
            token: sessionStorage.getItem('TOKEN'),
            data: {},
            navigation:[
                    {
                        title:"dashboard", 
                        type: 'parent', 
                        current: true,
                        link: '#',
                        icon: Layout4BlocksIcon,
                        children: [
                            {title: 'default', type: 'child', link: 'Dashboard'},
                            {title: 'pos-system', type: 'child', link: 'PosSystem'},
                        ]
                    },
                    {
                        title:'account', 
                        type: 'parent', 
                        current: false,
                        link: '#',
                        icon: UserAccountIcon,
                        children: [
                            {title: 'default', type: 'child', link: 'Dashboard'},
                            {title: 'pos-system', type: 'child', link: 'PosSystem'},
                        ]
                    }
            ]
        }
    },
    getters: {},
    actions: {
        setLang({commit},lang){
            i18n.global.locale.value=lang;
            //console.log(i18n.global.locale.value);
            //i18n.locale = lang;
            const elemsHtml = document.getElementsByTagName("html");
            const elemsBody = document.getElementsByTagName("body");
            
            elemsBody[0].classList.add("page-loading");
            //dispatchEvent(new Event('load'));
            //console.log(lang);

            if(lang=='en'){
                var prismjs_bundle_css = document.getElementById('prismjs_bundle_css');
                //prismjs_bundle_css.addEventListener('load', function () { console.log('loaded prismjs_bundle_css'); });
                prismjs_bundle_css.setAttribute('href', '/src/assets/plugins/custom/prismjs/prismjs.bundle.css');
                
                var datatables_bundle_css = document.getElementById('datatables_bundle_css');
                //datatables_bundle_css.addEventListener('load', function () { console.log('loaded datatables_bundle_css'); });
                datatables_bundle_css.setAttribute('href', '/src/assets/plugins/custom/datatables/datatables.bundle.css');
                
                var plugins_bundle_css = document.getElementById('plugins_bundle_css');
                //plugins_bundle_css.addEventListener('load', function () { console.log('loaded plugins_bundle_css'); });
                plugins_bundle_css.setAttribute('href', '/src/assets/plugins/global/plugins.bundle.css');
                
                var style_bundle_css = document.getElementById('style_bundle_css');
                //style_bundle_css.addEventListener('load', function () { console.log('loaded style_bundle_css'); });
                style_bundle_css.setAttribute('href', '/src/assets/css/style.bundle.css');
                
                elemsHtml[0].style.direction = 'ltr'; 
                elemsHtml[0].setAttribute('dir','ltr');
                elemsHtml[0].setAttribute('direction','ltr');
                elemsHtml[0].classList.remove("rtlApp");
                elemsHtml[0].classList.add("ltrApp");

                this.menuPlacementBottom = "bottom-start";
                this.menuPlacementStart = "right-start";
            }else{
                var prismjs_bundle_css = document.getElementById('prismjs_bundle_css');
                //prismjs_bundle_css.addEventListener('load', function () { console.log('loaded prismjs_bundle_css.rtl'); });
                prismjs_bundle_css.setAttribute('href', '/src/assets/plugins/custom/prismjs/prismjs.bundle.rtl.css');
                
                var datatables_bundle_css = document.getElementById('datatables_bundle_css');
                //datatables_bundle_css.addEventListener('load', function () { console.log('loaded datatables_bundle_css.rtl'); });
                datatables_bundle_css.setAttribute('href', '/src/assets/plugins/custom/datatables/datatables.bundle.rtl.css');
                
                var plugins_bundle_css = document.getElementById('plugins_bundle_css');
                //plugins_bundle_css.addEventListener('load', function () { console.log('loaded plugins_bundle_css.rtl'); });
                plugins_bundle_css.setAttribute('href', '/src/assets/plugins/global/plugins.bundle.rtl.css');
                
                var style_bundle_css = document.getElementById('style_bundle_css');
                //style_bundle_css.addEventListener('load', function () { console.log('loaded style_bundle_css.rtl'); });
                style_bundle_css.setAttribute('href', '/src/assets/css/style.bundle.rtl.css');
     
                elemsHtml[0].style.direction = 'rtl'; 
                elemsHtml[0].setAttribute('dir','rtl');
                elemsHtml[0].setAttribute('direction','rtl');
                elemsHtml[0].classList.remove("ltrApp");
                elemsHtml[0].classList.add("rtlApp");

                this.menuPlacementBottom = "bottom-end";
                this.menuPlacementStart = "left-start";
            }
            commit("setLang",lang);
            setTimeout(function() {
                elemsBody[0].classList.remove('page-loading');
            }, 2000);
            return lang;
        },
        logout({commit}){
            return axiosClient.post('/logout')
            .then(reponse => {
                commit("logout");
                //console.log(reponse);
                return reponse;
            });
        },
        register({commit}, user){
            return axiosClient.post('/register', user)
            .then(({data}) => {
                commit("setUser", data);
                //console.log(data);
                return data;
            });
        },
        login({commit}, user){
            return axiosClient.post('/login', user)
            .then(({data}) => {
                commit("setUser", data);
                //console.log(data);
                return data;
            });
        },
        loginFetch({commit}, user){
            return fetch('http://localhost:8000/api/login', {
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                },
                method: "POST",
                body: JSON.stringify(user),
            })
            .then((res) => res.json())
            .then((res) => {
                commit("setUser", res);
                console.log(res);
                return res;
            });
        },
    },
    mutations: {
        setLang: (state, langData) => {
            state.lang =langData;
            sessionStorage.setItem('LANGUAGE', langData);
        },
        logout: state => {
            state.user.data = {};
            state.user.token = null;
            sessionStorage.removeItem('TOKEN');
        },
        setUser: (state, userData) => {
            state.user.token = userData.token;
            state.user.data = userData.user;
            sessionStorage.setItem('TOKEN', userData.token);
        }
    },
    modules: {},
})

export default store;