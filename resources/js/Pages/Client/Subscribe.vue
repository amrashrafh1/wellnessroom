<script setup>
    import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
    import {
        Head
    } from '@inertiajs/inertia-vue3';

</script>
<template>

    <Head title="Dashboard" />

    <BreezeAuthenticatedLayout>
    <div class='min-h-screen bg-gray-400'>
        <div v-if="$page.props.flash.success" 
        class='flex justify-center items-center bg-gray-400'>
            <div class="m-8 w-96 bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                role="alert">
                <div class="flex">
                    <div class="py-1"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="ml-3">
                        <p class="font-bold"><strong>Success!</strong> You're now subscribed.</p>
                        <p class="text-sm">you've been successfully subscribed!.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-center items-center">
            <div class="card h-auto w-96 bg-white rounded-lg p-4">

                <p class="text-center text-[#323436] text-2xl font-semibold">Upgrade to Canva to Work and get 30 days
                    free.</p>
                <div class="flex flex-col mt-5">
                    <div class="flex items-center gap-2"> <i
                            class="text-[20px] text-[#95bc78] fa fa-check-circle-o"></i>
                        <p class="text-sm">Your first 30 days are free,cancel anytime </p>
                    </div>
                    <div class="flex items-center gap-2 mt-2"> <i
                            class="text-[20px] text-[#95bc78] fa fa-check-circle-o"></i>
                        <p class="text-sm">Your first 30 days are free,cancel anytime </p>
                    </div>
                    <div class="flex items-center gap-2 mt-2"> <i
                            class="text-[20px] text-[#95bc78] fa fa-check-circle-o"></i>
                        <p class="text-sm">Your first 30 days are free,cancel anytime </p>
                    </div>
                </div>
                <form id='payment-form'>
                    <div class="mt-5">
                        <label v-for='plan in plans' :for="'sub_'+ plan.id" :key='plan.id'
                            class="h-16 cursor-pointer w-full border rounded-lg flex">
                            <div class="flex p-3 items-center justify-start">
                                <input name='sub_type' :id="'sub_'+ plan.id" type="radio" :value="plan.id" checked
                                    v-model="form.sub_type"
                                    class="blue_color relative h-4 w-4 border border-blue-700 rounded-full mr-3" />
                                <p class="font-semibold">{{plan.title}}</p>
                            </div>
                        </label>
                    </div>

                    <div id="card-element" class="m-5">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>
                    <input type='submit' id='card-button'
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 border border-blue-700 rounded"
                        value="submit" />
                </form>
            </div>
        </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>

<script>
    export default {
        data() {
            return {
                form: this.$inertia.form({
                    sub_type: 0,
                    stripeToken: null,
                    _method: 'post'
                }),
            }
        },
        props: [
            'plans',
            'data',
            'auth',
            'flash',
        ],
        mounted() {
            const stripe = Stripe('pk_test_u6snBjmD2e0hFj5aQC5c4Ec300uI7omnec');

            const elements = stripe.elements()
            const cardElement = elements.create('card')
            cardElement.mount('#card-element')
            const form = document.getElementById('payment-form')
            const cardBtn = document.getElementById('card-button')

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                cardBtn.disabled = true
                const {
                    setupIntent,
                    error
                } = await stripe.confirmCardSetup(
                    this.data.intent.client_secret, {
                        payment_method: {
                            card: cardElement,
                            billing_details: {
                                name: this.auth.user.name,
                                email: this.auth.user.email
                            }
                        }
                    }
                )

                if (error) {
                    cardBtn.disable = false
                } else {
                    this.form.stripeToken = setupIntent.payment_method;
                    this.submit();
                }
            })

        },
        methods: {
            submit() {
                this.form.post(route('subscription'));
            },
        },
    }

</script>
