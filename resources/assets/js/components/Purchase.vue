<template>
   <div class="container">
       <div class="row">
           <div class="col-sm-12">
               <div class="card">
                   <div class="card-header">
                       <h2>Lista de Compras</h2>

                    <button @click="initAddpurchase()" class="btn btn-success " style="padding:5px">
                     Agregar Compra
                     </button>
                     </div>
                   <div class="card-body">

                        <table class="table table-bordered table-striped "  v-if="purchases.length > 0">
                                <thead>
                                        <tr>
                                            <th> No.</th>
                                            <th>Fecha</th>
                                            <th>Proveedor</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                        </thead>
                                <tbody>
                                        <tr v-for="(purchase, index) in purchases">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ purchase.date }}</td>
                                            <td>{{ purchase.supplier_name }}</td>
                                            <td>{{ purchase.state }}</td>
                                            <td>
                                                <button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                                <button @click="deletepurchase(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-close" aria-hidden="true"></i></button>

                                            </td>
                                        </tr>

                                </tbody>
                            </table>
                   </div>
               </div>
           </div>
       </div>


<!-- modal para agregar nueva compra -------------------------------------------------------- -->

       <div class="modal fade" tabindex="-1" role="dialog" id="add_purchase_model">
          <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                   <h4 class="modal-title">Agregar Nueva Compra</h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>


                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" placeholder="Escriba la fecha de la compra" class="form-control"
                            v-model="purchase.date">
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" @click="createpurchase" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

             </div>
           </div>
        </div>

 <!-- Fin de modal para agregar nueva Compra -------------------------------------------------------- -->


 <!-- modal para Editar Compra -------------------------------------------------------- -->

<div class="modal fade" tabindex="-1" role="dialog" id="update_purchase_model">
    <div class="modal-dialog" role="document">
         <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Actualizar Compra</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>



                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" placeholder="Escriba la fecha de la compra" class="form-control"
                            v-model="updatepurchase.date">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" @click="updatepurchase" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin de modal para Editar Compra -------------------------------------------------------- -->

   </div>
</template>


<script>
export default {

data(){
    return {
        purchase: {
        date: '',
        supplier_name: '',
        state: '',
        total_cost: '',
        },



        errors: [],
        purchases: [],

        update_purchase: {}
    }
},

mounted()
        {
            this.readpurchases();
        },

methods: {

    deletepurchase(index)
    {
        let conf = confirm("Esta seguro que quiere borrar este purchaseo?");
        if (conf === true)
        {
                axios.delete('/purchase/' + this.purchases[index].id)
                .then(response => {
                    this.purchases.splice(index, 1);
                })
                .catch(error => {
                });
        }
    },

    initAddpurchase()
        {
            $("#add_purchase_model").modal("show");
        },



    createpurchase()
    {


        axios.post('/purchase',
                {
                    date: this.purchase.date,
                })

            .then(response => {
            this.reset();
            this.purchases.push(response.data.purchase);
                $("#add_purchase_model").modal("hide");
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

        this.purchase.date= '';
        this.purchase.supplier_name= '';
        this.purchase.state= '';
        this.purchase.total_cost= '';
    },

    readpurchases()
    {
        axios.get('/purchase')
            .then(response => {
                this.purchases = response.data.purchases;
            });
    },

    initUpdate(index)

    {
        this.errors = [];
        $("#update_purchase_model").modal("show");
        this.update_purchase = this.purchases[index];
    },

    updatepurchase()

    {
        axios.patch('/purchase/' + this.update_purchase.id, {
        date: this.update_purchase.date
        })

            .then(response => {
                $("#update_purchase_model").modal("hide");
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
