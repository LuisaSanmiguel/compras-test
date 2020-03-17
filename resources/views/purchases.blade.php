@extends('layouts.app') @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <div class="card" id="app">
                <div class="card-header">
                    <h2>Lista de Compras</h2>

                    <button @click="initAddPurchase()" class="btn btn-success " style="padding:5px">
                        Agregar Compra
                    </button>
                </div>
                <div class="card-body">

                    <table class="table table-bordered table-striped " v-if="purchases.length > 0">
                        <thead>
                            <tr>
                                <th> No.</th>
                                <th>Fecha</th>
                                <th>Proveedor</th>
                                <th>Estado</th>
                                <th>Costo Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchase, index) in purchases">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ purchase.date }}</td>
                                <td>@{{ purchase.supplier_name }}</td>
                                <td>@{{ purchase.state }}</td>
                                <td> $ @{{formatPrice(purchase.total_cost)}}</td>
                                <td style="display:flex">
                                        <div>
                                            <button title="ver Detalles" @click="purchase_Details(index)" class="btn btn-primary btn-xs" style="padding:8px"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                            </div>
                                    <div v-if="purchase.state == 'IN_PROGRESS'">
                                    <button title="Recibir compra" @click="receivedPurchase(index)" class="btn btn-primary btn-xs" style="padding:8px"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
                                    <button title="Anular Compra" @click="cancelledPurchase(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                    <button title="Eliminar Compra" @click="deletePurchase(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-close" aria-hidden="true"></i></button>
                                    <button title="Editar Compra" @click="initUpdate(index)"  class="btn btn-success btn-xs" style="padding:8px "><i class="fa fa-pencil" aria-hidden="true"></i></button>
                                    </div>

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
                    <h4 class="modal-title">Agregar Nueva Compra</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" placeholder="Escriba la fecha de la compra" class="form-control" v-model="purchase.date">
                    </div>

                    <div class="form-group">
                        <label>Proveedor:</label>
                        <select class="form-control" v-model="purchase.supplier_id">
                            <option value=""> Seleccione un proveedor</option>
                            <option v-for="(supplier, index) of suppliers" :value='supplier.id'>@{{supplier.name}}</option>
                        </select>
                    </div>


                </div>

                <div class="modal-footer">
                    <button type="button" @click="createPurchase" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Fin de modal para agregar nueva Compra -------------------------------------------------------- -->

    <!-- modal para Editar Compra -------------------------------------------------------- -->

    <div class="modal fade" tabindex="-1" role="dialog" id="update_purchase_model">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">

                <div class="modal-header">
                    <h4 class="modal-title">Actualizar Compra</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group">
                        <label>Fecha:</label>
                        <input type="date" placeholder="Escriba la fecha de la compra" class="form-control" v-model="update_purchase.date">
                    </div>

                    <div class="form-group">
                        <label>Proveedor:</label>
                        <select class="form-control" v-model="update_purchase.supplier_id">

                            <option v-for="(supplier, index) of suppliers" :value='supplier.id'>@{{supplier.name}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Estado:</label>
                        <input type="text" readonly class="form-control" v-model="update_purchase.state" value="IN_PROGRESS">
                    </div>

                    <div class="form-group">
                        <label>Costo Total:</label>
                        <input type="text" readonly placeholder="Escriba el costo total" class="form-control" v-model="update_purchase.total_cost">
                    </div>


                    <button type="button" @click="initDetailPurchase(update_purchase.id)" class="btn btn-primary">Nuevo producto</button>


                    <table class="table table-bordered table-striped " v-if="purchaseDetails.length > 0">
                        <thead>
                            <tr>
                                <th> No.</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Costo Unitario</th>
                                <th>Costo Total</th>
                                <th>Acciones</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchaseDetail, index) in purchaseDetails">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ purchaseDetail.product_name }}</td>
                                <td>@{{ purchaseDetail.quantity }}</td>
                                <td>$@{{ formatPrice(purchaseDetail.cost)}}</td>
                                <td>$@{{formatPrice(purchaseDetail.total_cost)}}</td>
                                <td>
                                    <button title="Actualizar detalle" @click="updateDetailPurchase(index)" class="btn btn-primary btn-xs" style="padding:8px"><i class="fa fa-pencil" aria-hidden="true"></i></button>  <button title="Anular Compra" @click="deleteDetailPurchase(index)" class="btn btn-danger btn-xs" style="padding:8px"><i class="fa fa-close" aria-hidden="true"></i></button></td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" @click="updatePurchase" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin de modal para Editar Compra -------------------------------------------------------- -->

    <!-- modal para ver detalle de Compra -------------------------------------------------------- -->

    <div class="modal fade" tabindex="-1" role="dialog" id="show_purchaseDetail_model">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Ver detalles de la Compra</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                    </div>
                    <div class="row">

                        <div class="col-md-6 form-group">
                            <label>Proveedor:</label>
                            <input class="form-control" readonly v-model="update_purchase.supplier_name" />

                        </div>
                        <div class="col-md-6 form-group">
                            <label>Fecha:</label>
                            <input type="date" class="form-control" readonly v-model="update_purchase.date">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Estado:</label>
                        <input type="text" class="form-control" readonly v-model="update_purchase.state" value="IN_PROGRESS">
                    </div>

                    <div class="form-group">
                        <label>Costo Total:</label>
                        <input type="text" readonly placeholder="Escriba el costo total" class="form-control" v-model="update_purchase.total_cost">
                    </div>

                    <table class="table table-bordered table-striped " v-if="purchaseDetails.length > 0">
                        <thead>
                            <tr>
                                <th> No.</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Costo Unitario</th>
                                <th>Costo Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(purchaseDetail, index) in purchaseDetails">
                                <td>@{{ index + 1 }}</td>
                                <td>@{{ purchaseDetail.product_name }}</td>
                                <td>@{{ purchaseDetail.quantity }}</td>
                                <td>$@{{ formatPrice(purchaseDetail.cost)}}</td>
                                <td>$@{{formatPrice(purchaseDetail.total_cost)}}</td>

                            </tr>

                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Fin de modal para ver detalle de Compra -------------------------------------------------------- -->

    <!-- modal para agregar nueva compra -------------------------------------------------------- -->

    <div class="modal fade" tabindex="-1" role="dialog" id="add_purchase_detail">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Agregar Detalles de Compra</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                </div>

                <div class="modal-body">
                    <div class="alert alert-danger" v-if="errors.length > 0">
                        <ul>
                            <li v-for="error in errors">@{{ error }}</li>
                        </ul>
                    </div>

                    <div class="form-group">
                         <label>Consecutivo de compra:</label>
                         <input type="text" readonly class="form-control" v-model="purchaseDetail.purchase_id">
                      </div>

                    <div class="form-group">
                        <label>Producto:</label>
                        <select class="form-control" v-model="purchaseDetail.product_id">
                            <option value=""> Seleccione un producto</option>
                            <option v-for="(product, index) of products" :value='product.id'>@{{product.name}}</option>
                        </select>
                    </div>



                    <div class="form-group">
                        <label>Cantidad:</label>
                        <input type="text" @keyup="multiplica()" placeholder="Escriba la cantidad" class="form-control" v-model="purchaseDetail.quantity">
                    </div>
                    <div class="form-group">
                        <label>Costo:</label>
                        <input type="text" @keyup="multiplica()" placeholder="Escriba el costo" class="form-control" v-model="purchaseDetail.cost">
                    </div>

                    <div class="form-group">
                        <label>Total Costo:</label>
                        <input type="text" placeholder="Escriba el costo total" readonly class="form-control" v-model="purchaseDetail.total_cost">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" @click="createDetailPurchase" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Fin de modal para agregar nueva Compra -------------------------------------------------------- -->

<!-- modal para modificar producto -------------------------------------------------------- -->

<div class="modal fade" tabindex="-1" role="dialog" id="edit_purchase_detail">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title">Modificar Detalles de Compra</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            </div>

            <div class="modal-body">
                <div class="alert alert-danger" v-if="errors.length > 0">
                    <ul>
                        <li v-for="error in errors">@{{ error }}</li>
                    </ul>
                </div>

                <div class="form-group">
                     <label>Consecutivo de compra:</label>
                     <input type="text" readonly class="form-control" v-model="purchase_Detail.purchase_id">
                  </div>

                <div class="form-group">
                    <label>Producto:</label>
                    <select class="form-control" v-model="purchase_Detail.product_id">
                        <option value=""> Seleccione un producto</option>
                        <option v-for="(product, index) of products" :value='product.id'>@{{product.name}}</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Cantidad:</label>
                    <input type="text"  @keyup="multiplica()" placeholder="Escriba la cantidad" class="form-control" v-model="purchase_Detail.quantity">
                </div>
                <div class="form-group">
                    <label>Costo:</label>
                    <input type="text" @keyup="multiplica()" placeholder="Escriba el costo" class="form-control" v-model="purchase_Detail.cost">
                </div>

                <div class="form-group">
                    <label>Total Costo:</label>
                    <input type="text" placeholder="Escriba el costo total" readonly class="form-control" v-model="purchase_Detail.total_cost">
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" @click="saveDetailPurchase" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>

        </div>
    </div>
</div>

<!-- Fin de modal para modifica producto en la Compra -------------------------------------------------------- -->


</div>
@endsection @section('js')
<script type="text/javascript">
    var app = new Vue({
        el: '#app',

        data() {
            return {
                product: {
                    name: ''
                },
                purchase: {
                    date: '',
                    supplier_id: '',
                    state: 'IN_PROGRESS',
                    total_cost: '',
                },

                purchaseDetail: {
                    product_id: '',
                    purchase_id: '',
                    quantity: '',
                    cost: '',
                    total_cost:'' ,
                },

                errors: [],
                purchases: [],

                purchaseDetails: {},
                suppliers: [],
                products: [],
                purchase_Detail: {},
                update_purchase: {}
            }
        },


        mounted() {
            this.readProducts();
            this.readPurchases();
            this.readSuppliers();

        },

        methods: {

            formatPrice(value) {
                    let val = (value / 1).toFixed(0).replace('.', ',')
                    val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                    return val
                },

          multiplica() {

            let total = this.purchaseDetail.quantity *  this.purchaseDetail.cost;
            this.purchaseDetail.total_cost =  this.purchaseDetail.quantity *  this.purchaseDetail.cost;


            this.purchase_Detail.total_cost =  this.purchase_Detail.quantity *  this.purchase_Detail.cost;
         },

             deletePurchase(index) {

                    let conf = confirm("Esta seguro que quiere cancelar esta compra?");
                    if (conf === true) {
                        axios.delete('/api/purchase/' + index)
                            .then(response => {
                                // this.purchases.splice(index, 1);
                            })
                            .catch(error => {});
                    }
                },

            initAddPurchase() {
                    $("#add_purchase_model").modal("show");
                },

            createPurchase() {
                    axios.post('/api/purchase', {
                        date: this.purchase.date,
                        supplier_id: this.purchase.supplier_id,

                    })

                    .then(response => {
                        this.reset();
                        this.purchases.push(response.data.purchase);

                        $("#add_purchase_model").modal("hide");
                        this.initDetailPurchase(response.data.purchase.id);
                    })

                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors && error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                    });
                },

                initDetailPurchase(index) {
                    // console.log(index);
                    this.purchaseDetail.purchase_id = index;
                    this.readProducts();

                    $("#add_purchase_detail").modal("show");
                },

            createDetailPurchase()
            {
                axios.post('/api/purchaseDetail',
                        {
                            'purchase_id': this.purchaseDetail.purchase_id,
                            'product_id': this.purchaseDetail.product_id,
                            'quantity': this.purchaseDetail.quantity,
                            'cost': this.purchaseDetail.cost,
                            'total_cost': this.purchaseDetail.total_cost,

                        })

                    .then(response => {

                     //this.purchaseDetails.push(response.data.purchaseDetail);

                    let conf = confirm("Se guardo el item satisfactoriamente, quiere agregar otro item?");
                    if (conf === true) {

                    }
                    else{
                        $("#add_purchase_detail").modal("hide");

                    }

                    $("#update_purchase_model").modal("hide");

                    this.detailReset();
                    this.readPurchases();

                    })

                    .catch(error => {
                                    this.errors = [];
                                    if (error.response.data.errors && error.response.data.errors.name) {
                                    this.errors.push(error.response.data.errors.name[0]);
                                    }
                    });


            },

            detailReset()
                {
                     this.purchaseDetail.product_id = '';
                     this.purchaseDetail.quantity = '';
                     this.purchaseDetail.cost = '';
                     this.purchaseDetail.total_cost = '';
                },

                reset()
                {
                    this.purchase.date = '';
                    this.purchase.supplier_id = '';
                    this.purchase.state = '';
                    this.purchase.total_cost = '';
                },

                readSuppliers()
                {
                    axios.get('/api/supplier')
                        .then(response => {
                            this.suppliers = response.data.suppliers;
                        });
                },

                readPurchases()
                {
                    axios.get('/api/purchase')
                        .then(response => {
                            this.purchases = response.data.purchases;
                        });
                },

                readProducts()
                {
                    axios.get('/api/product')
                        .then(response => {
                            this.products = response.data.products;
                        });
                },

                initUpdate(index)
                {
                    this.errors = [];
                    $("#update_purchase_model").modal("show");
                    this.readSuppliers();
                    this.update_purchase = this.purchases[index];
                    this.initDetailUpdate(this.update_purchase.id);
                },


            updatePurchase()
            {
                axios.patch('/api/purchase/' + this.update_purchase.id, {

                    date: this.update_purchase.date,
                    supplier_name: this.update_purchase.supplier_name,
                    supplier_id: this.update_purchase.supplier_id,
                    state: this.update_purchase.state,
                    total_cost: this.update_purchase.total_cost
                })

                .then(response => {
                    $("#update_purchase_model").modal("hide");
                    this.readPurchases();

                })

                .catch(error => {
                    this.errors = [];
                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                });

            },

            purchase_Details(index)
            {
                // console.log(index);

                $("#show_purchaseDetail_model").modal("show");
                this.update_purchase = this.purchases[index];
                console.log(this.update_purchase.id);

                axios.get('/api/purchaseDetail/' + this.update_purchase.id)

                .then(response => {
                    this.purchaseDetails = response.data.purchaseDetails;
                })
            },

            initDetailUpdate(index)
                {
                    this.errors = [];
                    this.readSuppliers();
                   // this.purchaseDetails = this.purchases[index];
                    axios.get('/api/purchaseDetail/' + index)

                .then(response => {
                    this.purchaseDetails = response.data.purchaseDetails;
                })
                },


            updateDetailPurchase(index)
            {
                console.log(index);
                $("#edit_purchase_detail").modal("show");
                 this.purchase_Detail = this.purchaseDetails[index];
                    axios.get('/api/purchaseDetailOne/' + this.purchase_Detail.id)

                .then(response => {
                    this.purchaseDetail = response.data.purchaseDetail;
                })

            },

            saveDetailPurchase()
            {
                axios.patch('/api/purchaseDetail/' + this.purchase_Detail.id, {

                    purchase_id: this.purchase_Detail.purchase_id,
                    product_id: this.purchase_Detail.product_id,
                    quantity: this.purchase_Detail.quantity,
                    cost: this.purchase_Detail.cost,
                    total_cost: this.purchase_Detail.total_cost
                })

                .then(response => {
                    $("#edit_purchase_detail").modal("hide");
                    this.readPurchases();

                })

                .catch(error => {
                    this.errors = [];
                    if (error.response.data.errors.name) {
                        this.errors.push(error.response.data.errors.name[0]);
                    }
                });

            },

            cancelledPurchase(index)
            {

                this.update_purchase = this.purchases[index];
                console.log(this.update_purchase.id );
                axios.get('/api/cancelledPurchase/'+  this.update_purchase.id)

                .then(response => {
                this.readPurchases();
                })


            },

            receivedPurchase(index)
            {

                this.update_purchase = this.purchases[index];
                console.log(this.update_purchase.id );
                axios.get('/api/receivedPurchase/'+  this.update_purchase.id)

                .then(response => {
                  this.readPurchases();
                })


            },

        }

    })
</script>
@stop
