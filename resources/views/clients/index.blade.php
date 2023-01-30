<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Solicitar token") }}
                </div>
            </div>
        </div>
    </div>
    <section id="app" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <section  class="md:grid md:grid-cols-3 gap-6 text-white">
            <section class="md:col-span-1">
                <h3 class="text-center">
                    Crea un nuevo cliente 
                </h3>
                <p>
                    Ingrese los datos solicitados para poder crear un nuevo cliente
                </p>
            </section>
            <section  class="md:col-span-2 mt-5">
                <article class="p-6 bg-black text-white shadow rounded-lg">
                    <div>
                        <x-input-label for="name" :value="__('Nombre:')" />
                        <x-text-input  v-model="createForm.name" class="block mt-1 w-full" type="text" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-3">
                        <x-input-label for="url" :value="__('Url de redirección:')" />
                        <x-text-input id="url" v-model="createForm.redirect" class="block mt-1 w-full" type="text"  :value="old('url')" required autofocus />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>
                </article>
                <article class="p-3 mt-3 text-white shadow rounded-lg flex justify-end items-center">
                    <x-secondary-button v-on:click="store">
                        Guardar
                    </x-secondary-button>
                </article>
            </section>
        </section>
        <section class="md:grid md:grid-cols-3 gap-6 text-white mt-10">
            <section class="md:col-span-1">
                <h3 class="text-center">
                    Listado de  cliente 
                </h3>
                <p>
                    Aca puedes ver el listado de los clientes que has creado
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
                        <tr v-for="client in clients">
                            <td class="text-white py-3">
                                @{{client.name}}
                            </td>
                            <td class="flex text-white divide-x divide-gray-500 py-3">
                                <a href="" class="pr-2 hover:text-blue-600 cursor-pointer">
                                    Editar
                                </a>
                                <a href="" class="pl-2 hover:text-red-600 cursor-pointer">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
    @push('js')
        <script>
            new Vue ({
                el: '#app',
                data:{
                    clients: [],
                    createForm:{
                        errors: [],
                        name: null,
                        redirect: null,
                    }
                }, 
                mounted(){
                    this.getClients();
                },
                methods:{
                    getClients(){
                        axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data
                        });
                    },
                    store(){
                        axios.post('/oauth/clients', this.createForm)
                        .then(response => {
                            this.createForm.name = null;
                            this.createForm.redirect = null;
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Your work has been saved',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }).catch(error => {
                            alert('No has completado los datos suficientes');
                        })
                    }
                }
            });
            
        </script>
    @endpush
</x-app-layout>
