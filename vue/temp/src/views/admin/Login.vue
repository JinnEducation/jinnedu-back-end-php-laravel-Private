<template>
    <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10 order-2 order-lg-1">
        <!--begin::Form-->
        <div class="d-flex flex-center flex-column flex-lg-row-fluid">
            <!--begin::Wrapper-->
            <div class="w-lg-500px p-10">
                <!--begin::Form-->
                <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" @submit="login">
                    <Heading title="Sign In" subtitle="Your Social Campaigns"></Heading>
                    <LoginOptions v-if="false"></LoginOptions>
                    <Separator v-if="false"></Separator>
                    <!--begin::Input group=-->
                    <div class="fv-row mb-8">
                        <!--begin::Email-->
                        <input type="text" placeholder="Email" name="email" v-model="user.email" autocomplete="off" class="form-control bg-transparent" />
                        <!--end::Email-->
                    </div>
                    <!--end::Input group=-->
                    <div class="fv-row mb-3">
                        <!--begin::Password-->
                        <input type="password" placeholder="Password" name="password" v-model="user.password" autocomplete="off" class="form-control bg-transparent" />
                        <!--end::Password-->
                    </div>
                    <!--end::Input group=-->
                    <!--begin::Wrapper-->
                    <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                        <div></div>
                        <!--begin::Link-->
                        <a href="/metronic8/demo1/../demo1/authentication/layouts/corporate/reset-password.html" class="link-primary">Forgot Password ?</a>
                        <!--end::Link-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Submit button-->
                    <div class="d-grid mb-10">
                        <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                            <!--begin::Indicator label-->
                            <span class="indicator-label">Sign In</span>
                            <!--end::Indicator label-->
                            <!--begin::Indicator progress-->
                            <span class="indicator-progress">Please wait... 
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            <!--end::Indicator progress-->
                        </button>
                    </div>
                    <!--end::Submit button-->
                    <!--begin::Sign up-->
                    <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? 
                        <router-link :to="{name: 'Register'}"  class="link-primary">Sign up</router-link>
                    </div>
                    <!--end::Sign up-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Form-->
        <Footer></Footer>
    </div>
</template>

<script setup>
import Heading from '../../components/admin/auth/Heading.vue'
import Separator from '../../components/admin/auth/Separator.vue'
import LoginOptions from '../../components/admin/auth/LoginOptions.vue'
import Footer from '../../components/admin/auth/Footer.vue'

import store from '../../store'
import {useRouter} from 'vue-router'
import {ref} from 'vue'

const router = useRouter();
const user = {
    email: '',
    password: '',
    remeber: false
}

let errorMsg = ref('');


function login(ev){
    ev.preventDefault();

    var form = document.querySelector('#kt_sign_in_form');
    var submitButton = document.querySelector('#kt_sign_in_submit');
    // Show loading indication
    submitButton.setAttribute('data-kt-indicator', 'on');

    // Disable button to avoid multiple click 
    submitButton.disabled = true;

    store
        .dispatch('login', user)
        .then((res) => {
            // Hide loading indication
            submitButton.removeAttribute('data-kt-indicator');

            // Enable button
            submitButton.disabled = false;
            //====Swal START
            Swal.fire({
                text: "You have successfully logged in!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            }).then(function (result) {
                if (result.isConfirmed) { 
                    router.push({
                        name: 'Dashboard'
                    })
                }
            });
            //====Swal END
        })
        .catch(err => {
            errorMsg.value=err.response.data.error;
            // Hide loading indication
            submitButton.removeAttribute('data-kt-indicator');

            // Enable button
            submitButton.disabled = false;
            //====Swal START
            Swal.fire({
                text: errorMsg.value,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
            //====Swal END
        })
}
</script>

<style scoped>

</style>