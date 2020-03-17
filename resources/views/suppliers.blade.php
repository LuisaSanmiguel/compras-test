@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card" id="app">
                   <div class="card-header">
                       <h2>Lista de Proveedores</h2>

                    <button @click="initAddsupplier()" class="btn btn-success " style="padding:5px">
                     Agregar proveedor
                     </button>
                     </div>
                   <div class="card-body">

                        <table class="table table-bordered table-striped "  v-if="suppliers.length > 0">
                                <thead>
                                        <tr>
                                            <th> No.</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                <tbody>
                                        <tr v-for="(supplier, index) in suppliers">
                                            <td>@{{ index + 1 }}</td>
                                            <td>@{{ supplier.name }}</td>
                                            <td>
                                                <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button @click="deletesupplier(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-close" aria-hidden="true"></i></button>

                                            </td>
                                        </tr>

                                </tbody>
                            </table>
                   </div>
               </div>
        </div>
    </div>

    <!-- modal para agregar nuevo proveedor -------------------------------------------------------- -->

   <div class="modal fade" tabindex="-1" role="dialog" id="add_supplier_model">
      <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
               <h4 class="modal-title">Agregar Nuevo proveedor</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body">
                <div class="alert alert-danger" v-if="errors.length > 0">
                    <ul>
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                </div>
                <div class="form-group">
                    <label for="names">Nombre:</label>
                    <input type="text" name="name" id="name" placeholder="Escriba en nombre del proveedor" class="form-control"
                        v-model="supplier.name">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="createsupplier" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>

         </div>
       </div>
    </div>

     <!-- Fin de modal para agregar nuevo proveedor -------------------------------------------------------- -->


     <!-- modal para Editar proveedor -------------------------------------------------------- -->

    <div class="modal fade" tabindex="-1" role="dialog" id="update_supplier_model">
        <div class="modal-dialog" role="document">
             <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title">Actualizar Proveedor</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">@{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" placeholder="Escriba el nombre del proveedor" class="form-control"
                                v-model="update_supplier.name">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" @click="updatesupplier" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- Fin de modal para Editar proveedor -------------------------------------------------------- -->
</div>
@endsection

@section('js')
    <script type="text/javascript">
        var app = new Vue({
          el: '#app',
          data(){
            return {
                supplier: {
                    name: ''
                },
                errors: [],
                suppliers: [],
                update_supplier: {}
            }
        },

        mounted()
                {
                    this.readsuppliers();
                },

        methods: {

            deletesupplier(index)
            {
                let conf = confirm("Esta seguro que quiere borrar este proveedor?");
                if (conf === true)
                {
                        axios.delete('/api/supplier/' + this.suppliers[index].id)
                        .then(response => {
                            this.suppliers.splice(index, 1);
                        })
                        .catch(error => {
                        });
                }
            },

            initAddsupplier()
                {
                    $("#add_supplier_model").modal("show");
                },

            createsupplier()
            {
                axios.post('/api/supplier',
                        {
                            name: this.supplier.name,
                        })

                    .then(response => {
                    this.reset();
                    this.suppliers.push(response.data.supplier);
                        $("#add_supplier_model").modal("hide");
                    })

                    .catch(error => {
                                    this.errors = [];
                                    if (error.response.data.errors && error.response.data.errors.name) {
                                    this.errors.push(error.response.data.errors.name[0]);
                                    }



                    });

            },

            reset()

            {
                this.supplier.name = '';
            },

            readsuppliers()
            {
                axios.get('/api/supplier')
                    .then(response => {
                        this.suppliers = response.data.suppliers;
                    });
            },

            initUpdate(index)

            {
                this.errors = [];
                $("#update_supplier_model").modal("show");
                this.update_supplier = this.suppliers[index];
            },

            updatesupplier()

            {
                axios.patch('/api/supplier/' + this.update_supplier.id, {

                name: this.update_supplier.name
                })

                    .then(response => {
                        $("#update_supplier_model").modal("hide");
                    })

                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                        }
                    });

            }

}
        })
    </script>
@stop

