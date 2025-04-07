<script setup>
    import { useForm } from '@inertiajs/vue3';

    let form = useForm({
        email: null,
        password: null,
    });

</script>

<template>
    <div class="fixed inset-20 flex justify-center items-center">
        <div class="grid grid-cols-2 items-stretch rounded-xl h-full max-h-[600px] w-full max-w-[1000px] m-auto">
            <div class="bg-emerald-400"></div>
            <div class="flex items-center justify-center bg-gray-900/50">
                <form method="post" class="w-full flex flex-col justify-center items-start p-8" @submit.prevent="form.post('/login')">

                    <h1 class="text-2xl text-gray-200">Login</h1>
                    <span class="text-gray-400 text-xs">Lorem ipsum dolor sit amet consectetur adipisicing elit. Minima, optio.</span>

                    <!-- EMAIL INPUT -->
                    <div class="w-full group *:block mt-4">
                        <label
                            for="email"
                            class="w-full text-xs text-gray-500 p-1 group-invalid-within:text-red-400 group-focus-within:text-emerald-600">
                            E-mail
                        </label>
                        <input
                            v-model="form.email"
                            name="email"
                            id="email"
                            type="email"
                            placeholder="Email"
                            class="w-full p-3 outline-none bg-gray-900/50 border border-gray-800 rounded-md invalid:border-red-400
                            focus:border-emerald-600" >
                        <span class="text-red-600 text-xs p-1" v-if="form.errors.email">{{ form.errors.email }}</span>
                    </div>

                    <!-- PASSWORD INPUT -->
                    <div class="w-full group *:block mt-4">
                        <label
                            for="password"
                            class="w-full text-xs text-gray-500 p-1 group-focus-within:text-emerald-600">
                            Password
                        </label>
                        <input
                            v-model="form.password"
                            name="password"
                            id="password"
                            type="password"
                            placeholder="Password"
                            class="w-full p-3 outline-none bg-gray-900/50 border border-gray-800 rounded-md invalid:border-red-400
                            focus:border-emerald-600">
                        <span class="text-red-600 text-xs p-1" v-if="form.errors.password">{{ form.errors.password }}</span>

                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-emerald-600 hover:bg-emerald-700 disabled:bg-emerald-600/50 cursor-pointer text-white px-6 py-3
                        rounded-lg mt-6">
                        Sign in
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
