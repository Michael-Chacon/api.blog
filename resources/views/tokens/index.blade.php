<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('API Tokens') }}
        </h2>
    </x-slot>
    <main id="app">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Crear un token --}}
                <section  class="md:grid md:grid-cols-3 gap-6 text-white">
                    <section class="md:col-span-1 text-center">
                        <h3 class="text-center">
                            Access Token 
                        </h3>
                        <p>
                            Aqui podras unsar un access token
                        </p>
                    </section>
                    <section  class="md:col-span-2 mt-5">
                        <article class="p-6 bg-black text-white shadow rounded-lg">
                            <section v-if="createForm.errors.length > 0" class="mb-5 py-5 px-2 bg-red-200 text-black">
                                <strong>Whooops!!</strong>
                                <span>Also salio mal</span>
                                <ul>
                                    <li v-for="errores in createForm.errors">
                                        @{{ errores }}
                                    </li>   
                                </ul>
                            </section>
                            <div>
                                <x-input-label for="name" :value="__('Nombre:')" />
                                <x-text-input  v-model="createForm.name" class="block mt-1 w-full" type="text" :value="old('name')" required autofocus />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                        </article>
                        <article class="p-3 mt-3 text-white shadow rounded-lg flex justify-end items-center">
                            <x-secondary-button v-on:click="store" v-bind:disabled="createForm.disabled">
                                Guardar
                            </x-secondary-button>
                        </article>
                    </section>
                </section>
                {{-- Mostrar los tokens --}}
                <section v-if="tokens.length > 0" class="md:grid md:grid-cols-3 gap-6 text-white mt-10">
                    <section class="md:col-span-1">
                        <h3 class="text-center">
                            Listado de Access Tokens
                        </h3>
                        <p>
                            Aca puedes ver el listado de los access tokens que has creado
                        </p>
                    </section>
                    <section  class="md:col-span-2 mt-5 mb-5">
                        <table class="text-gray-600">
                            <thead class="border-b border-gray-500">
                                <tr class="text-left">
                                    <th class="py-2 w-full">Nombre:</th>
                                    <th class="py-2">Acción:</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-300">
                                <tr v-for="token in tokens">
                                    <td class="text-white py-3">
                                        @{{token.name}}
                                    </td>
                                    <td class="flex text-white divide-x divide-gray-500 py-3">
                                        <a v-on:click="" class="pr-2 hover:text-green-600 cursor-pointer">
                                            Ver
                                        </a>
                                        <a v-on:click="" class="px-2 hover:text-blue-600 cursor-pointer">
                                            Editar
                                        </a>
                                        <a v-on:click="" class="pl-2 hover:text-red-600 cursor-pointer">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </section>
                </section>
            </div>
        </div>
    </main>
    @push('js')
        <script>
            new Vue({
                el: '#app',
                data:{
                    tokens: [],
                    createForm:{
                        errors: [],
                        name: null,
                        disabled: false,
                    },
                },
                mounted(){
                    this.getTokens();
                },
                methods:{
                    getTokens(){
                        axios.get('/oauth/personal-access-tokens')
                            .then(response => {
                                this.tokens = response.data;
                            });
                    },
                    store(){
                        this.createForm.disabled = true;
                        axios.post('/oauth/personal-access-tokens', this.createForm)
                            .then(response => {
                                this.createForm.disabled = false;
                                this.createForm.name = null;
                                this.createForm.errors = [];
                                this.getTokens();
                            }).catch(error => {
                                this.createForm.errors = _.flatten(_.toArray(error.response.data.errors));
                                this.createForm.disabled = false;
                            });
                    },
                },
            });
        </script>
    @endpush
</x-app-layout>