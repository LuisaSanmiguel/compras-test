<template>
   <div class="container">
       <div class="row">
           <div class="col-sm-12">
               <div class="card">
                   <div class="card-header">
                       <h2>Lista de Productos</h2>

                    <button @click="initAddproduct()" class="btn btn-success " style="padding:5px">
                     Agregar Producto
                     </button>
                     </div>
                   <div class="card-body">

                        <table class="table table-bordered table-striped "  v-if="products.length > 0">
                                <thead>
                                        <tr>
                                            <th> No.</th>
                                            <th>Nombre</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                <tbody>
                                        <tr v-for="(product, index) in products">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ product.name }}</td>
                                            <td>
                                                <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button @click="deleteproduct(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-close" aria-hidden="true"></i></button>

                                            </td>
                                        </tr>

                                </tbody>
                            </table>
                   </div>
               </div>
           </div>
       </div>


<!-- modal para agregar nuevo producto -------------------------------------------------------- -->

       <div class="modal fade" tabindex="-1" role="dialog" id="add_product_model">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                   <h4 class="modal-title">Agregar Nuevo Producto</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="names">Nombre:</label>
                        <input type="text" name="name" id="name" placeholder="Escriba en nombre del producto" class="form-control"
                            v-model="product.name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" @click="createproduct" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

             </div>
           </div>
        </div>

 <!-- Fin de modal para agregar nuevo producto -------------------------------------------------------- -->


 <!-- modal para Editar producto -------------------------------------------------------- -->

<div class="modal fade" tabindex="-1" role="dialog" id="update_product_model">
    <div class="modal-dialog" role="document">
         <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Actualizar producto</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>
                    <div class="form-group">
                        <label>Nombre:</label>
                        <input type="text" placeholder="Escriba el nombre del producto" class="form-control"
                            v-model="update_product.name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" @click="updateproduct" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin de modal para Editar producto -------------------------------------------------------- -->

   </div>
</template>


<script>
export default {

        data(){
            return {
                product: {
                name: ''
                },
                errors: [],
                products: [],
                update_product: {}
            }
        },

        mounted()
                {
                    this.readproducts();
                },

        methods: {

            deleteproduct(index)
            {
                let conf = confirm("Esta seguro que quiere borrar este producto?");
                if (conf === true)
                {
                        axios.delete('/product/' + this.products[index].id)
                        .then(response => {
                            this.products.splice(index, 1);
                        })
                        .catch(error => {
                        });
                }
            },

            initAddproduct()
                {
                    $("#add_product_model").modal("show");
                },

            createproduct()
            {
                axios.post('/product',
                        {
                            name: this.product.name,
                        })

                    .then(response => {
                    this.reset();
                    this.products.push(response.data.product);
                        $("#add_product_model").modal("hide");
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
                this.product.name = '';
            },

            readproducts()
            {
                axios.get('/product')
                    .then(response => {
                        this.products = response.data.products;
                    });
            },

            initUpdate(index)

            {
                this.errors = [];
                $("#update_product_model").modal("show");
                this.update_product = this.products[index];
            },

            updateproduct()

            {
                axios.patch('/product/' + this.update_product.id, {
                name: this.update_product.name
                })

                    .then(response => {
                        $("#update_product_model").modal("hide");
                    })

                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                        }
                    });

            }

}

}

</script>
