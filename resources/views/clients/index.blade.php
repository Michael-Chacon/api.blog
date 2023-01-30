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
    <div id="app">
    <section class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                    <div class="mt-3">
                        <x-input-label for="url" :value="__('Url de redirección:')" />
                        <x-text-input id="url" v-model="createForm.redirect" class="block mt-1 w-full" type="text"  :value="old('url')" required autofocus />
                        <x-input-error :messages="$errors->get('url')" class="mt-2" />
                    </div>
                </article>
                <article class="p-3 mt-3 text-white shadow rounded-lg flex justify-end items-center">
                    <x-secondary-button v-on:click="store" v-bind:disabled="createForm.disabled">
                        Guardar
                    </x-secondary-button>
                </article>
            </section>
        </section>
        <section v-if="clients.length > 0" class="md:grid md:grid-cols-3 gap-6 text-white mt-10">
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
                                <a v-on:click="edit(client)" class="pr-2 hover:text-blue-600 cursor-pointer">
                                    Editar
                                </a>
                                <a v-on:click="destroy(client)" class="pl-2 hover:text-red-600 cursor-pointer">
                                    Eliminar
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </section>
    </section>
    {{-- Modal --}}
    <x-dialog-modal modal="editForm.open">
        <x-slot name="title">
            Titulo dle modal
        </x-slot>
        <x-slot name="content" class="w-full">
                <section v-if="editForm.errors.length > 0" class="mb-5 py-5 px-2 bg-red-200 text-black">
                    <strong>Whooops!!</strong>
                    <span>Also salio mal</span>
                    <ul>
                        <li v-for="errores in editForm.errors">
                            @{{ errores }}
                        </li>   
                    </ul>
                </section>
                <div class="w-full">
                    <x-input-label for="name" :value="__('Nombre:')" />
                    <x-text-input  v-model="editForm.name" class="mt-1 w-full" type="text" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>
                <div class="mt-3">
                    <x-input-label for="url" :value="__('Url de redirección:')" />
                    <x-text-input id="url" v-model="editForm.redirect" class="mt-1 w-full" type="text"  :value="old('url')" required autofocus />
                    <x-input-error :messages="$errors->get('url')" class="mt-2" />
                </div>
                
        </x-slot>

    </x-dialog-modal>
    </div>
    @push('js')
        <script>
            new Vue ({
                el: '#app',
                data:{
                    clients: [],
                    createForm:{
                        errors: [],
                        disabled: false,
                        name: null,
                        redirect: null,
                    },
                    editForm:{
                        open: false,     
                        errors: [],
                        disabled: false,
                        name: null,
                        redirect: null,
                    },
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
                        this.createForm.disabled = true;
                        axios.post('/oauth/clients', this.createForm)
                        .then(response => {
                            this.createForm.name = null;
                            this.createForm.redirect = null;
                            this.createForm.errors = [];
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Tu cliente a sido creaco con éxito',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            this.getClients();
                            this.createForm.disabled = false;
                        }).catch(error => {
                            this.createForm.errors = _.flatten(_.toArray(error.response.data.errors));
                            this.createForm.disabled = false;
                        })
                    },
                    destroy(client){
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                axios.delete('/oauth/clients/'+client.id)
                                .then(response => {
                                    this.getClients();
                                })
                                Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                                )
                            }
                        })
                    },
                    edit(client){
                        this.editForm.open = true;
                    }
                }
            });
            
        </script>
    @endpush
</x-app-layout>
