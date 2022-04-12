<template>
  <v-app>
    <v-form style="background-color: #454d55;" ref="formOrden" v-model="validForm" :lazy-validation="true">
      <div class="content" style="background-color: #454d55;">
    <div class="md-layout-item md-medium-size-100 md-xsmall-size-100 md-size-100">
      <v-overlay :value="loader" :z-index="'99999999'">
        <v-progress-circular indeterminate size="80" color="grey darken-4"></v-progress-circular>
      </v-overlay>
      <v-card class="m-4 datos">
        <v-card-title>
          <b>Ingresa tus datos.</b>
        </v-card-title>
        <div class="m-4">
          <validation-provider
          v-slot="{ errors }"
          name="Nombre"
          rules="required|max:60"
        >
          <v-text-field
            v-model="orden.nombre"
            :counter="60"
            :error-messages="errors"
            label="Nombre"
            filled
            required
          ></v-text-field>
        </validation-provider>
        <validation-provider
          v-slot="{ errors }"
          name="Numero de teléfono"
          :rules="{
            required: true,
            digits: 8,
            regex: /^[0-9]+$/
          }"
        >
          <v-text-field
            v-model="orden.telefono"
            :counter="8"
            :error-messages="errors"
            label="Numero de teléfono"
            filled
            required
          ></v-text-field>
        </validation-provider>
        </div>
      </v-card>
      <v-card class="m-4">
        <v-card-title>
          <b>Elige las pupusas que quieres.</b>
        </v-card-title>
        <v-list class="m-4">
          <v-list-tile v-for="(pupusa) in pupusas" :key="pupusa.id" >
            <v-list-tile-action style="color: #fff">
              <v-checkbox v-model="selected" :label="`${pupusa.nombre} - $${pupusa.precio}`" multiple :value="pupusa" />
            </v-list-tile-action>
          </v-list-tile>
        </v-list>
      </v-card>
      <v-card class="m-4">
        <v-card-title>
          <b>Selecciona la cantidad.</b>
        </v-card-title>
        <v-list class="m-4">
          <v-list-tile v-for="pupusas in selected" :key="pupusas.id" >
            <v-list-tile-action>
              <div class="d-flex">
                <h5 style="margin-right: 10px;"><b>{{ pupusas.nombre }}</b></h5>
                <number-input  v-model="pupusas.cantidad" :min="1" inline center controls rounded></number-input>
              </div>
              <br/>
              <hr />
            </v-list-tile-action>
          </v-list-tile>
        </v-list>
      </v-card>
      <v-card class="m-4">
        <v-card-title>
          <b>Elige la bebida.</b>
          <div class="flex-grow-1"></div>
        </v-card-title>
        <v-list class="m-4">
          <v-list-tile v-for="bebida in bebidas" :key="bebida.id" >
            <v-list-tile-action>
              <div class="d-flex">
                <h5 style="margin-right: 10px;"><b>{{ bebida.nombre }} - ${{ bebida.precio }}</b></h5>
                <number-input :min="0" v-model="bebida.cantidad" id="ni" inline center rounded controls></number-input>
              </div>
              <br/>
              <hr />
            </v-list-tile-action>
          </v-list-tile>
        </v-list>
      </v-card>
        <v-btn
        class="btnCont mb-5 d-flex m-auto"
        x-large
        rounded
        color="success"
        dark
        :disabled="!validForm"
        @click="saveOrden()"
        >
        Hacer pedido
        <v-icon>notification_add</v-icon>
        </v-btn>
    </div>
  </div>
    </v-form>
    <v-footer padless>
          <v-col
            class="text-center"
            cols="12"
          >
            <strong>PupuseriaWeb&copy; {{ new Date().getFullYear()}}. Kevin Chacón, Fredy Alvarez, Andersson Hernandez, Oscar Landaverde.</strong><span class="text-light">144.</span>
          </v-col>
        </v-footer>
  </v-app>
</template>
<script>
import { required, digits, max, regex } from 'vee-validate/dist/rules';
import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate';

setInteractionMode('eager');

extend('digits', {
    ...digits,
    message: '{_field_} tiene que tener {length} digitos. ({_value_})',
  })

  extend('required', {
    ...required,
    message: '{_field_} no puede estar vacío',
  })

  extend('max', {
    ...max,
    message: '{_field_} no debe tener más de {length} caracteres',
  })

  extend('regex', {
    ...regex,
    message: '{_field_} {_value_} no coincide con {regex}',
  })

export default {
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  data() {
     return {
      selected: [],
      pupusas: [],
      bebidas: [],
      loader: false,
      search: "",
      orden: {
        id: null,
        nombre: '',
        telefono: null,
        detallePedido: [],
        hora: '',
        fecha: ''
      },  
      validForm: true,
      snackbar: false,
      msjSnackBar: "",
      errorsNombre: [],
      editedCategoria: -1
    };
  },
  computed: {
    formTitle() {
      return this.orden.id === null
        ? "Agregar Categoria"
        : "Actualizar Categoria";
    },
    btnTitle() {
      return this.orden.id === null ? "Guardar" : "Actualizar";
    }
  },
  methods: {
    fetchPupusas() {
      let me = this;
      me.loader = true;
      axios.get(`/productos/filter-type?state=D&&tipo=Pupusa`)
        .then(function(response) {
          me.pupusas = response.data;
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
          console.log(error);
        });
     me.loader = true;
    },
    
    fetchBebidas() {
      let me = this;
      let temp = [];
      let cantidad = 0;
      me.loader = true;
      axios.get(`/productos/filter-type?state=D&&tipo=Bebida`)
        .then(function(response) {
          me.bebidas = response.data,
          console.log(me.bebidas)
          me.loader = false;
        })
        .catch(function(error) {
          me.loader = false;
          console.log(error);
        });
     me.loader = true;
    },
    setMessageToSnackBar(msj, estado) {
      let me = this;
      me.snackbar = estado;
      me.msjSnackBar = msj;
    },
    clearForm() {
      let me = this;
      me.selected = [];
      me.orden = {
        id: null,
        nombre: '',
        telefono: null,
        detallePedido: [],
        hora: '',
        fecha: ''
      }
    },
    resetValidation() {
      let me = this;
      me.errorsNombre = [];
      me.$refs.formCategoria.resetValidation();
    },
    showModalEditar(categoria) {
      let me = this;
      me.editedCategoria = me.arrayCategorias.indexOf(categoria);
      me.categoria = Object.assign({}, categoria);
      me.dialog = true;
    },
    saveOrden() {
      /**
       * pd = id del producto
       * ct = cantidad cantidad del producto
       * tl = total de la orden
       */
      let me = this;
      if (me.$refs.formOrden.validate()) {
        let accion = me.orden.id == null ? "add" : "upd";
        me.loader = true;
        if(accion=="add"){
          me.pupusas.forEach(element => {
            if (element.cantidad > 0) {
              me.orden.detallePedido.push({'pd': element.id, 'ct': element.cantidad, 'tl': parseFloat(element.precio) * element.cantidad});
            }
          });
          me.bebidas.forEach(element => {
            if (element.cantidad > 0) {
              me.orden.detallePedido.push({'pd': element.id, 'ct': element.cantidad, 'tl': parseFloat(element.precio) * element.cantidad});
            }
          });
          me.orden.hora = me.getDateTime('hora');
          me.orden.fecha = me.getDateTime('fecha');
           axios.post('/pedidos/save', me.orden)
            .then(function(response) {
              if (response.status === 201) {
                  me.verificarAccionDato(response.data, response.status, accion);
                  me.clearForm();
                  me.loader = false;
              }
              else {
                me.loader = false;
                Vue.swal("Error","Ocurrió un error intente de nuevo","error");
                me.clearForm();
              }
                })
                .catch(function(error){
                    Vue.swal("Error","Ocurrió un error intente de nuevo","error");
                })          
        }
      }
    },
    getDateTime(opcion) {
      if (opcion === 'hora') {
        return new Date(new Date().getTime()).toLocaleTimeString();
      }
      else if (opcion === 'fecha') {
        return new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000).toISOString().substr(0, 10);
      }
      else {
        return null;
      }
    },
    deleteCategoria(categoria) {
      let me = this;
      me.editedCategoria = me.arrayCategorias.indexOf(categoria);
      
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
        //personalizando nueva confirmacion
        Vue.swal.fire({
        title: 'Eliminar Categoría',
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
            axios.post('/categorias/delete', categoria)
            .then(function(response) {
              me.verificarAccionDato(response.data, response.status, "del");
              me.loader = false;
            })
          }
        });
    },
     verificarAccionDato(orden, statusCode, accion) {
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
        case "add":
          //Agrego al array de categorias el objecto que devuelve el Backend
          //me.arrayCategorias.unshift(categoria); 
          /* Toast.fire({
            icon: 'success',
            title: "<h5 style='color:black'>" + 'La orden se ha registrado' + "</h5>"
           }); */
           Vue.swal('Listo!!!','La orden se ha registrado','success');
          me.loader = false;
          break;
      }
    }
  },
  mounted() {
    let me = this;
    me.fetchPupusas();
    me.fetchBebidas();
  }
}; 
</script>

<style scoped>
  .btnCont {
    display: flex;
    margin: auto;
  }
  .datos {
    width: 500px;
  }
</style>