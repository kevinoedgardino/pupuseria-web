<template>
    <v-app>
      <div class="content" style="background-color: #454d55;">
        <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
      <v-overlay :value="loader" :z-index="'99999999'">
        <v-progress-circular indeterminate size="80" color="grey darken-4"></v-progress-circular>
      </v-overlay>
        </div>
        <div class="content" style="background-color: #454d55;">
        <v-list style="background-color: #454d55;" v-for="(pedido) in pedidos" :key="pedido.id">
            <v-card class="m-4">
            <v-card-title>
                <b>{{ pedido.cliente }} - {{ pedido.telefono }}</b>
            </v-card-title>
            <v-card-text class="fs-5 detail">
                <div>
                    <p>Producto: {{ pedido.producto }}</p>
                </div>
                <div>
                    <p>Cantidad: {{ pedido.cantidad }}</p>
                </div>
                <div>
                    <p>Total: ${{ pedido.total }}</p>
                </div>
                <div>
                    <p>Fecha: {{ pedido.fecha }}</p>
                </div>
                <div>
                    <p>Hora: {{ pedido.hora }}</p>
                </div>
            </v-card-text>
            <div class="d-flex justify-content-end">
              <v-btn
                class="pedbtn"
                depressed
                color="success"
                @click="despacharCancelarPedido(pedido, 'D')"
              >
                <v-icon left>done_all</v-icon>
                Despachar
              </v-btn>
              <v-btn
                class="pedbtn"
                depressed
                color="primary"
                @click="showModalEditar(pedido)"
              >
                <v-icon left>edit</v-icon>
                Editar
              </v-btn>
              <v-btn
                class="pedbtn"
                depressed
                color="error"
                @click="despacharCancelarPedido(pedido, 'C')"
              >
                <v-icon left>close</v-icon>
                Cancelar
              </v-btn>
            </div>
            
        </v-card>
        </v-list>
    </div>
      </div>
      <v-dialog
          v-model="dialog"
          max-width="700px"
        >
          <v-card>
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                  <v-form ref="formPedido" v-model="validForm" :lazy-validation="true">
                    <v-row>
                      <v-col
                        cols="12"
                        sm="6"
                        md="4"
                    >
                    <v-select
                        v-model="pedido.producto_id"
                        :items="productos"
                        label="Producto"
                        item-value="id"
                        item-text="nombre"
                        ></v-select>
                    </v-col>
                    <v-col
                        cols="12"
                        sm="6"
                        md="4"
                    >
                      <label>Cantidad</label>
                      <number-input 
                      :min="1"
                      v-model="pedido.cantidad"
                      required
                      inline 
                      center 
                      rounded 
                      controls></number-input>
                    </v-col>
                    </v-row>
                  </v-form>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                color="blue darken-1"
                text
                @click="closeForm()"
              >
                Cancelar
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="updatePedido()"
              >
                Guardar
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-footer padless>
          <v-col
            class="text-center"
            cols="12"
          >
            <strong>PupuseriaWeb&copy; {{ new Date().getFullYear() }}. Kevin Chacón, Fredy Alvarez, Andersson Hernandez, Oscar Landaverde.</strong><span class="text-light">144.</span>
          </v-col>
        </v-footer>
  </v-app>    
</template>
<script>
import Vue from 'vue';
    export default {
  data() {
     return {
        pedidos: [],
        productos: [],
      loader: false,
      search: "",
      pedido: {
        id: null,
        cliente: "",
        telefono: null,
        cantidad: null,
        hora: "",
        total: "",
        fecha: "",
        pedido_id: null,
        producto_id: null,
      },  
      validForm: true,
      dialog: false,
      snackbar: false,
      msjSnackBar: "",
      errorsNombre: [],
      editedpedido: -1
    };
  },
  computed: {
    formTitle() {
      return this.pedido.id === null
        ? "Agregar pedido"
        : "Actualizar pedido";
    },
    btnTitle() {
      return this.orden.id === null ? "Guardar" : "Actualizar";
    }
  },
  methods: {
    fetchPedidos() {
      let me = this;
      me.loader = true;
      axios.get(`/pedidos/filter?state=P`)
        .then(function(response) {
          me.pedidos = response.data;
          me.pedido = [...me.pedidos];
          //console.log(me.pedidos);
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
          console.log(error);
        });
        let cantidad = me.pedido.cantidad;
            let precio;
            let total = 0;
            let pedido = me.pedido.producto_id;
            let productos = me.productos;
            productos.forEach(element => {
                if (element.id == pedido) {
                  precio = parseFloat(element.precio);
                }
            });
            for (let i = 1; i <= cantidad; i++) {
              total = total + precio;
            }
            //me.pedido.total = total;
            me.pedido.total = parseFloat(total).toFixed(2);
     me.loader = true;
    },
    fetchProductos() {
            let me = this;
            me.loader = true;
            axios.get(`/productos/filter?state=D`)
                .then(function(response) {
                me.productos = response.data;
                //console.log(me.productos);
                me.loader = false;
                })
                .catch(function(error) {
                console.log(error);
                me.loader = false;
                });
        },
    setMessageToSnackBar(msj, estado) {
      let me = this;
      me.snackbar = estado;
      me.msjSnackBar = msj;
    },
    closeForm() {
        let me = this;
        me.dialog = false;
        setTimeout(() => {
            me.pedido = {
              id: null,
              cliente: "",
              telefono: null,
              hora: "",
              total: "",
              fecha: ""
        }, 
        me.resetValidation();
        }, 300);
      },
    resetValidation() {
      let me = this;
      me.errorsNombre = [];
      me.$refs.formPedido.resetValidation();
    },
    showModalEditar(item) {
      //console.log(item);
      let me = this;
            me.editedpedido = me.pedidos.indexOf(item);
            me.pedido = Object.assign({}, item);
            //console.log(me.pedido)
            me.dialog = true;
    },
    updatePedido() {
      let me = this;
            //para actualizar
            let accion = "upd";
            let cantidad = me.pedido.cantidad;
            let precio;
            let total = 0;
            let pedido = me.pedido.producto_id;
            let productos = me.productos;
            productos.forEach(element => {
                if (element.id == pedido) {
                  precio = parseFloat(element.precio);
                }
            });
            for (let i = 1; i <= cantidad; i++) {
              total = total + precio;
            }
            //me.pedido.total = total;
            me.pedido.total = parseFloat(total).toFixed(2);
            axios.put('/pedidos/update-pedido',me.pedido)
               .then(function(response) {
                    
                    me.verificarAccionDato(response.data, response.status, accion);
                    me.closeForm();
            })
          .catch(function(error) {
            console.log(error);
              Vue.swal("Error","No se pudo actualizar el pedido", "error");
            /* if (error.response.status == 409) {
                me.setMessageToSnackBar("pedido ya existe",true);
                me.errorsNombre = ["Nombre de categoría existente"];
            }
            else {
                Vue.swal("Error","Ocurrió un error intente de nuevo","error");
            } */
          });
      },
    despacharCancelarPedido(item, query) {
      if (query === "C") {
        let me = this;
        let ped = {
            id: item.pedidoId,
            estado: "C"
        };
      
      const Toast = Vue.swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        });
        //personalizando nueva confirmacion
        Vue.swal.fire({
        title: 'Cancelar Pedido',
        text: "Una vez realizada la acción no se podra revertir !",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        customClass : {
              title: 'alert-color'
            },
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: "No"
        }).then((result) => {
        if (result.value) {
            me.loader = true;
            axios.put('/pedidos/change', ped)
            .then(function(response) {
              me.verificarAccionDato(response.data, response.status, "can");
              me.loader = false;
            })
            .catch(function(error){
              console.log(error);
              me.loader = false;
              Vue.swal("Error","No se pudo cancelar el pedido","error");
            })
          }
        });
      }
      else if (query === "D") {
        let me = this;
        let ped = {
            id: item.pedidoId,
            estado: "E"
        };
      
      const Toast = Vue.swal.mixin({
        toast: true,
        position: 'bottom-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        });
        //personalizando nueva confirmacion
        Vue.swal.fire({
        title: 'Despachar Pedido',
        text: "Una vez realizada la acción no se podra revertir !",
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si',
        cancelButtonText: "No"
        }).then((result) => {
        if (result.value) {
            me.loader = true;
            axios.put('/pedidos/change', ped)
            .then(function(response) {
              me.verificarAccionDato(response.data, response.status, "des");
              me.loader = false;
            })
            .catch(function(error){
              console.log(error);
              me.loader = false;
              Vue.swal("Error","No se pudo cancelar el pedido","error");
            })
          }
        });
      }
      
    },
     verificarAccionDato(pedido, statusCode, accion) {
      let me = this;
      
      const Toast = Vue.swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        onOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
        }); 
      switch (accion) {
        case "des":
          if (statusCode == 202) {
            me.fetchPedidos();
              Toast.fire({
                icon: 'success',
                title: "<h5 style='color:black'>" + 'El pedido ha sido despachado!!!' + "</h5>"
              });
          } else {
             Toast.fire({
                icon: 'error',
                title: 'Ocurrió un error, intente de nuevo'
              });
          }
          break;
        case "upd":
          this.fetchPedidos(); 
          Toast.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + 'El pedido se ha actualizado!!!' + "</h5>"
           }); 
            
          me.loader = false;
          break;
        case "can":
          if (statusCode == 202) {
            me.fetchPedidos();
              Toast.fire({
                icon: 'success',
                title: "<h5 style='color:black'>" + 'El pedido ha sido cancelado' + "</h5>"
              });
          } else {
             Toast.fire({
                icon: 'error',
                title: "<h5 style='color:black'>" + 'Ocurrío un error, intentalo de nuevo' + "</h5>"
              });
          }
          break;
      }
  },
  },
  mounted() {
    let me = this;
    me.fetchProductos();
    me.fetchPedidos();
  }
}; 
</script>

<style scoped>
  .pedbtn {
    margin-right: 10px;
    margin-bottom: 8px;
  }
  .detail div {
    color: #000;
  }
  .swal2-title {
    color: #000;
  }
</style>
