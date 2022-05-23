<template>
<div class="content" style="background-color: #454d55;">
    <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
      <v-overlay :value="loader" :z-index="'99999999'">
        <v-progress-circular indeterminate size="80" color="grey darken-4"></v-progress-circular>
      </v-overlay>
      
    <v-data-table
    :headers="headers"
    :items="productos"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-container class="d-flex">
        <v-row>
          <v-col
            class="ventas container-fluid m-2 text-light rounded-3"
            cols="12"
            lg="6"
          >
            <v-icon left color="white" size="50">paid</v-icon>
            <h2>Ventas de hoy: {{ ventas.cantidad }}</h2>
            <p class="fs-4">Total: ${{ ventas.total }}</p>
          </v-col>
        </v-row>
      <v-row>
      <v-col
        cols="12"
        lg="6"
      >
        <v-menu
          v-model="menu2"
          :close-on-content-click="false"
          transition="scale-transition"
          offset-y
          max-width="290px"
          min-width="auto"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-text-field
              v-model="computedDateFormatted"
              label="Fecha de hoy"
              hint="Formato Dia/Mes/Año"
              persistent-hint
              prepend-icon="mdi-calendar"
              readonly
              v-bind="attrs"
              v-on="on"
            ></v-text-field>
          </template>
          <v-date-picker
            v-model="date"
            no-title
            @input="menu2 = false"
          ></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
    
    </v-container>
      <v-toolbar
        flat
      >
        <v-toolbar-title>Productos</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              <v-icon left>add_circle</v-icon>              
              Nuevo Producto
            </v-btn>
          </template>  
            <v-card-title>
              <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>
            <v-card-text>
              <v-container>
                  <v-form ref="formProducto" v-model="validForm" :lazy-validation="true">
                    <v-row>
                    <v-col
                        cols="12"
                        sm="6"
                        md="4"
                    >
                        <v-text-field
                        required
                        v-model="producto.nombre"
                        label="Nombre"
                        ></v-text-field>
                    </v-col>
                    <v-col
                        cols="12"
                        sm="6"
                        md="4"
                    >
                    <v-select
                        v-model="producto.tipoprod_id"
                        :items="tipos"
                        label="Tipo de producto"
                        item-value="id"
                        item-text="nombre"
                        ></v-select>
                    </v-col>
                    <v-col
                        cols="12"
                        sm="6"
                        md="4"
                    >
                        <v-text-field
                        v-model="producto.precio"
                        required
                        label="Precio"
                        ></v-text-field>
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
                @click="saveProducto()"
              >
                Guardar
              </v-btn>
            </v-card-actions>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
        <v-btn
        class="ma-2"
        color="indigo"
        dark
        @click="editProducto(item)"
      >
        <v-icon dark>
          edit
        </v-icon>
      </v-btn>
      <v-btn
        class="ma-2"
        color="red"
        dark
        @click="deleteProducto(item)"
      >
        <v-icon dark>
          remove_circle
        </v-icon>
      </v-btn>
    </template>
  </v-data-table>
  <v-divider
    class="mx-4"
    inset
    vertical
  ></v-divider>
  <template>
  <v-card>
    <v-card-title>
      Registro
      <div class="text-center ml-5">
        <v-badge
          :value="hover"
          color="green accent-5"
          content="Estados: E = Entregado, P = Pendiente, C = Cancelado"
          right
          transition="slide-x-transition"
        >
        <v-btn
          color="blue-grey"
          class="ma-2 white--text"
          href="/pedidos/reporte"
        >
          Generar PDF
          <v-icon
            right
            dark
          >
            picture_as_pdf
          </v-icon>
        </v-btn>
          <v-hover v-model="hover">
            <v-icon
              color="success lighten-1"
              large
            >
              help_outline
            </v-icon>
          </v-hover>
        </v-badge>
      </div>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-data-table
      :headers="headersRecord"
      :items="registro"
      :search="search"
    ></v-data-table>
  </v-card>
  </template>
    </div>
    <v-footer padless>
          <v-col
            class="text-center"
            cols="12"
          >
            <strong>PupuseriaWeb&copy; {{ new Date().getFullYear() }}. Kevin Chacón, Fredy Alvarez, Andersson Hernandez, Oscar Landaverde.</strong><span class="text-light">144.</span>
          </v-col>
        </v-footer>
</div>

</template>
<script>
  export default {
  data(vm) {
     return { 
          date: (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10),
          dateFormatted: vm.formatDate((new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10)),
          menu1: false,
          menu2: false,
          hover: false,
          pedidos: [], 
         productos: [],
         registro: [],
         ventas: {
           cantidad: null,
           total: null,
         },
         tipos: [],
         dialog: false,
         search: '',
         dialogDelete: false,
         headersRecord: [
          {
            text: 'Cliente',
            align: 'start',
            sortable: false,
            value: 'cliente',
          },
          { text: 'Telefono', value: 'telefono' },
          { text: 'Producto', value: 'producto' },
          { text: 'Cantidad', value: 'cantidad' },
          { text: 'Estado', value: 'estado' },
          { text: 'Total', value: 'total' },
          { text: 'fecha', value: 'fecha' },
          { text: 'Hora', value: 'hora' },
        ],
        headers: [
            { text: 'Producto', value: 'nombre' },
            { text: 'Tipo', value: 'tipo' },
            { text: 'Precio', value: 'precio' },
            { text: 'Actions', value: 'actions', sortable: false, align: "center" },
        ],
        producto: {
            id: null,
            estado: "",
            nombre: "",
            precio: null,
            tipoprod_id: null,
        },
        loader: false,
        snackbar: false,
        validForm: true,
        msjSnackBar: "",
        editedProducto: -1,  
    };
  },
  computed: {
    formTitle () {
        return this.editedIndex === -1 ? 'Agregar producto' : 'Editar'
      },
    computedDateFormatted () {
        return this.formatDate(this.date)
      },
  },
  watch: {
      dialog (val) {
        val || this.closeForm()
      },
      dialogDelete (val) {
        val || this.closeDelete()
      },
      date (val) {
        this.dateFormatted = this.formatDate(this.date)
      },
    },
  methods: {
    getRegistro() {
      let me = this;
      me.loader = true;
      axios.get(`/pedidos/all`)
        .then(function(response) {
          me.registro = response.data;
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
        });
     me.loader = true;
    },
    getVentas() {
      let me = this;
      let suma = 0;
      let cant = 0;
      let fechona = me.date;
      me.loader = true;

      axios.get(`/pedidos/filter?state=E`)
        .then(function(response) {
          me.pedidos = response.data;
          me.pedidos.forEach(pedido => {
            if (pedido.fecha == fechona) {
              cant++;
              suma = suma + parseFloat(pedido.total);
            }
          });
          me.ventas.cantidad = cant
          me.ventas.total = parseFloat(suma).toFixed(2);
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
        });
     me.loader = true;
    },
    formatDate (date) {
        if (!date) return null

        const [year, month, day] = date.split('-')
        return `${month}/${day}/${year}`
      },
      parseDate (date) {
        if (!date) return null

        const [month, day, year] = date.split('/')
        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
      },
    fetchProductos() {
            let me = this;
            me.loader = true;
            axios.get(`/productos/filter?state=D`)
                .then(function(response) {
                me.productos = response.data;
                me.loader = false;
                })
                .catch(function(error) {
                me.loader = false;
                });
        },
        fetchTipoProductos() {
            let me = this;
            me.loader = true;
            axios.get(`/tipoproductos/all`)
                .then(function(response) {
                me.tipos = response.data;
                me.loader = false;
                })
                .catch(function(error) {
            
                me.loader = false;
                });
        },
        dialogDeleteItem() {
            let me = this;
            me.dialogDelete = true;
        },
      editProducto (item) {
            let me = this;
            me.editedProducto = me.productos.indexOf(item);
            me.producto = Object.assign({}, item);
            me.dialog = true;
      },

      deleteItemConfirm () {
        this.desserts.splice(this.editedIndex, 1)
        this.closeDelete()
      },

      closeForm() {
        let me = this;
        me.dialog = false;
        setTimeout(() => {
            me.producto = {
            id: null,
            estado: "",
            nombre: "",
            precio: null,
            tipoprod_id: null,
            };
            me.resetValidation();
        }, 300);
      },

      closeDelete () {
        this.dialogDelete = false
        this.$nextTick(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        })
      },
    setMessageToSnackBar(msj, estado) {
      let me = this;
      me.snackbar = estado;
      me.msjSnackBar = msj;
    },
    resetValidation() {
      let me = this;
      me.errorsNombre = [];
      me.$refs.formProducto.resetValidation();
    },
    saveProducto() {
      let me = this;
      if (me.$refs.formProducto.validate()) {
        let accion = me.producto.id == null ? "add" : "upd";
        me.loader = true;
        if(accion=="add"){
            me.producto.estado = "D";
            axios.post(`/productos/save`,me.producto)
            .then(function(response) {
              if(response.status==201){
                 me.verificarAccionDato(response.data, response.status, accion);
                 me.closeForm();
              }else{
                Vue.swal("Error", "Ocurrio un error, intente de nuevo", "error");
                me.closeForm();
              }
            
            })
            .catch(function(error){
               Vue.swal("Error", "Ocurrio un error, intente de nuevo", "error");
            });
            me.loader = false;
        }else{
            //para actualizar
                axios.put(`/productos/update`,me.producto)
               .then(function(response) {
                 if(response.status==202){
                    me.verificarAccionDato(response.data, response.status, accion);
                        me.closeForm();  
                 }else{
                    Vue.swal("Error", "Ocurrio un error, intente de nuevo", "error");
                     me.loader = false;
                 }   me.closeForm();
                  
              })
                .catch(function(error) {
                    me.loader = false;
                });
        }
      
      }
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
    },
    deleteProducto(item) {
        
        let me = this;
        let prod = {
            id: item.id,
            estado: "N"
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

        Vue.swal.fire({
        title: 'Eliminar Producto',
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
            axios.put(`/productos/change`,prod)
               .then(function(response) {
                 if(response.status==202){
                     Vue.swal("Hecho", "El producto se ha eliminado correctamente", "success");
                      me.fetchProductos(); 
                 }else{
                    Vue.swal("Error", "Ocurrio un error, intente de nuevo", "error");
                     me.loader = false;
                 }   
              })
                .catch(function(error) {
                    me.loader = false;
                });
          }
        });
    },
     verificarAccionDato(producto, statusCode, accion) {
      let me = this;
      
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
      switch (accion) {
        case "add":
          //Agrego al array de categorias el objecto que devuelve el Backend
          //me.arrayCategorias.unshift(categoria);
          this.fetchProductos(); 
          Toast.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + 'Producto exitosamente registrado!!!' + "</h5>"
           });
          me.loader = false;
          break;
        case "upd":
          //Actualizo al array de categorias el objecto que devuelve el Backend ya con los datos actualizados
          //Object.assign(me.arrayCategorias[me.editedCategoria], categoria);
          this.fetchProductos(); 
          Toast.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + 'Producto exitosamente actualizado!!!' + "</h5>"
           }); 
            
          me.loader = false;
          break;
        case "del":
          this.fetchProductos(); 
          Toast.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + 'Producto exitosamente eliminado!!!' + "</h5>"
           }); 
          break;
      }
    }
  },
  mounted() {
    let me = this;
    me.loader = true;
    me.fetchProductos();
    me.fetchTipoProductos();
    me.getVentas();
    me.getRegistro();
  }
}; 
</script>
<style scoped>
  .ventas {
    background: rgb(0, 189, 0);
  }
</style>