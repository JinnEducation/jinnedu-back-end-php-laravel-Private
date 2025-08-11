import {createStore} from "vuex";
import axiosClient from "../axios";
import { shallowRef,  ref, computed } from 'vue'
import {Layout_4_Blocks_Icon, UserAccount_Icon} from '../components/icons/SvgIcons.vue'

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
                        title:'Dashboard', 
                        type: 'parent', 
                        current: true,
                        link: '#',
                        icon: Layout4BlocksIcon,
                        children: [
                            {title: 'Default', type: 'child', link: 'Dashboard'},
                            {title: 'Pos System', type: 'child', link: 'PosSystem'},
                        ]
                    },
                    {
                        title:'Account', 
                        type: 'parent', 
                        current: false,
                        link: '#',
                        icon: UserAccountIcon,
                        children: [
                            {title: 'Default', type: 'child', link: 'Dashboard'},
                            {title: 'Pos System', type: 'child', link: 'PosSystem'},
                        ]
                    }
            ]
        }
    },
    getters: {},
    actions: {
        setLang({commit},lang){
            const elems = document.getElementsByTagName("html");
            console.log(lang);
            if(lang=='en'){
                document.getElementById('prismjs_bundle_css').setAttribute('href', '/src/assets/plugins/custom/prismjs/prismjs.bundle.css');
                document.getElementById('datatables_bundle_css').setAttribute('href', '/src/assets/plugins/custom/datatables/datatables.bundle.css');
                document.getElementById('plugins_bundle_css').setAttribute('href', '/src/assets/plugins/global/plugins.bundle.css');
                document.getElementById('style_bundle_css').setAttribute('href', '/src/assets/css/style.bundle.css');
                
                elems[0].style.direction = 'ltr'; 
                elems[0].setAttribute('dir','ltr');
                elems[0].setAttribute('direction','ltr');
                elems[0].classList.remove("rtlApp");
                elems[0].classList.add("ltrApp");

                this.menuPlacementBottom = "bottom-start";
                this.menuPlacementStart = "right-start";
            }else{
                document.getElementById('prismjs_bundle_css').setAttribute('href', '/src/assets/plugins/custom/prismjs/prismjs.bundle.rtl.css');
                document.getElementById('datatables_bundle_css').setAttribute('href', '/src/assets/plugins/custom/datatables/datatables.bundle.rtl.css');
                document.getElementById('plugins_bundle_css').setAttribute('href', '/src/assets/plugins/global/plugins.bundle.rtl.css');
                document.getElementById('style_bundle_css').setAttribute('href', '/src/assets/css/style.bundle.rtl.css');
     
                elems[0].style.direction = 'rtl'; 
                elems[0].setAttribute('dir','rtl');
                elems[0].setAttribute('direction','rtl');
                elems[0].classList.remove("ltrApp");
                elems[0].classList.add("rtlApp");

                this.menuPlacementBottom = "bottom-end";
                this.menuPlacementStart = "left-start";
            }
            commit("setLang",lang);
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