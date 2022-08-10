<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <lugar-modal :datoslugar="lugar" :departamentos="departamentos" @lugarEditadoEvent="recibirDatosModalLugar" />
    <b-modal id="sinEfectoModal" header-class="border-bottom-0" footer-class="border-top-0" :title="tituloModal" modal size="xl" @ok="handleOk">
      <div class="modal-body">
        <b-container>
          <b-row align-v="center">
            <b-col>
              <b-card class="text-center" header="Datos Generales" header-bg-variant="primary"
                      header-text-variant="secondary"
              >
                <b-row v-if="allowEdit" align-v="center" style="margin-bottom: 10px;">
                  <b-col sm="3">
                    <label>Número de Registro: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="sinefecto.numero_de_registro"
                                  :class="{ 'is-invalid': $v.sinefecto.numero_de_registro.$error }"
                                  placeholder="Ingrese número de registro"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.sinefecto && $v.sinefecto.numero_de_registro.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    <span v-if="!this.$v.sinefecto.numero_de_registro.required">El número de registro es requerido</span>
                    <span v-else-if="!this.$v.sinefecto.numero_de_registro.minValue || !this.$v.sinefecto.numero_de_registro.maxValue">
                      El número debe ser hasta de 8 cifras</span>
                  </b-col>
                </b-row>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Fecha Solicitud: </label>
                  </b-col>
                  <b-col lg="auto">
                    <b-form-datepicker v-model="fecha_sola_solicitud"
                                       :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                       :max="max"
                                       :min="min"
                                       locale="es"
                    />
                  </b-col>
                  <b-col lg="auto">
                    <b-form-timepicker v-model="hora_sola_solicitud" locale="es" />
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Fecha Registro: </label>
                  </b-col>
                  <b-col lg="auto">
                    <b-form-datepicker v-model="fecha_sola_registro"
                                       :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                       :max="max"
                                       :min="min"
                                       locale="es"
                    />
                  </b-col>
                  <b-col lg="auto">
                    <b-form-timepicker v-model="hora_sola_registro" locale="es" />
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Delegación: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="sinefecto.delegacion_dependencia_id"
                                   :class="{ 'is-invalid': $v.sinefecto.delegacion_dependencia_id.$error }"
                                   :options="delegaciones"
                                   :reduce="delegacion => delegacion.id" text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione una dependencia"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.sinefecto && $v.sinefecto.delegacion_dependencia_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    La delegación es requerida
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Lugar: </label>
                  </b-col>
                  <b-col sm="7">
                    <label @click="abrirModalLugar()">{{ stringLugar }}</label>
                  </b-col>
                  <b-col sm="2">
                    <b-button variant="info" @click="abrirModalLugar()">
                      <b-icon aria-hidden="true" icon="search" />
                    </b-button>
                  </b-col>
                </b-row>
                <b-row v-if="$v.lugar && $v.lugar.departamento.$error" align-h="end" class="text-danger">
                  <b-col sm="9">
                    El lugar es requerido
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
          </b-row>
          <tabla-oficios v-if="oficios.length >= 1" ref="componenteOficios" :oficios="oficios" @changeOficios="modificarOficios($event)" />
          <b-card class="text-center" header="Datos adicionales" header-bg-variant="primary" header-text-variant="secondary">
            <b-row align-v="center" style="margin-top: 20px;">
              <b-col sm="3">
                <label>Funcionario:</label>
              </b-col>
              <b-col sm="9">
                <b-form-input v-model="sinefecto.funcionario"
                              :class="{ 'is-invalid': $v.sinefecto.funcionario.$error }"
                              placeholder="Ingrese datos del funcionario"/>
              </b-col>
            </b-row>
            <b-row v-if="$v.sinefecto && $v.sinefecto.funcionario.$error" align-v="center" align-h="end">
              <b-col sm="9" class="text-danger">
                <span v-if="!$v.sinefecto.funcionario.required">El funcionario es requerido</span>
                <span v-else>El funcionario puede tener como máximo {{ $v.sinefecto.funcionario.$params.maxLength.max }} caracteres.</span>
              </b-col>
            </b-row>
            <b-row align-v="center" style="margin-top: 20px;">
              <b-col sm="3">
                <label>Descripción:</label>
              </b-col>
              <b-col sm="9">
                <b-form-textarea
                  id="textareaDescripcion"
                  v-model="sinefecto.descripcion"
                  :class="{ 'is-invalid': $v.sinefecto.descripcion.$error }"
                  placeholder="Ingrese descripción aquí"
                  rows="3"
                  max-rows="6"
                />
              </b-col>
            </b-row>
            <b-row v-if="$v.sinefecto && $v.sinefecto.descripcion.$error" align-v="center" align-h="end">
              <b-col sm="9" class="text-danger">
                <span v-if="!$v.sinefecto.descripcion.required">La descripcion es requerida</span>
                <span v-else>La descripción puede tener como máximo {{ $v.sinefecto.descripcion.$params.maxLength.max }} caracteres.</span>
              </b-col>
            </b-row>
          </b-card>
        </b-container>
      </div>
      <template slot="modal-footer">
        <b-button type="submit" variant="primary" @click="handleOk">
          Guardar
        </b-button>
        <b-button type="button" @click="$bvModal.hide('sinEfectoModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import { maxLength, maxValue, minValue, numeric, required } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import lugarModal from '../shared/lugarModal'
import tablaOficios from '../oficio/tablaOficios'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'sinEfectoModal',
  components: {
    lugarModal,
    tablaOficios,
    Loading
  },
  middleware: 'auth',
  props: {
    row: {
      type: Object,
      default: () => ({})
    },
    departamentos: {
      type: Array,
      default: () => ([])
    },
    delegaciones: {
      type: Array,
      default: () => ([])
    },
    intervinientes: {
      type: Array,
      default: () => ([])
    },
    // eslint-disable-next-line vue/prop-name-casing
    estados_oficios: {
      type: Array,
      default: () => ([])
    }
  },
  validations: {
    lugar: {
      departamento: { required }
    },
    sinefecto: {
      numero_de_registro: { required, numeric, minValue: minValue(1), maxValue: maxValue(99999999) },
      delegacion_dependencia_id: { required },
      lugar_id: {},
      funcionario: {
        required,
        maxLength: maxLength(300)
      },
      descripcion: {
        required,
        maxLength: maxLength(2000)
      }
    }
  },
  data () {
    return {
      tituloModal: 'Nuevo Sin Efecto',
      allowEdit: false,
      isLoading: false,
      fullPage: true,
      url: '',
      fecha_sola_solicitud: null,
      hora_sola_solicitud: null,
      fecha_sola_registro: null,
      hora_sola_registro: null,
      min: null,
      max: null,
      lugar: {
        id: null,
        latitud: null,
        longitud: null,
        departamento: null,
        barrio: null,
        localidad: null,
        calle: null,
        numero: null,
        numero_departamento: null
      },
      modal: lugarModal,
      sinefecto: {
        id: '',
        numero_de_registro: 1,
        fecha_hora_solicitud: '',
        fecha_hora_registro: '',
        delegacion_dependencia_id: null,
        lugar_id: null,
        funcionario: '',
        descripcion: ''
      },
      oficios: []
    }
  },
  computed: {
    fecha_hora_registro: function () {
      return this.fecha_sola_registro + ' ' + this.hora_sola_registro
    },
    fecha_hora_solicitud: function () {
      return this.fecha_sola_solicitud + ' ' + this.hora_sola_solicitud
    },
    stringLugar: function () {
      if (this.lugar.departamento || this.lugar.localidad || this.lugar.barrio || this.lugar.calle || this.lugar.numero || this.lugar.numero_departamento) {
        return this.comprobarTexto(this.lugar.departamento) + this.comprobarTexto(this.lugar.localidad) + this.comprobarTexto(this.lugar.barrio) + this.comprobarTexto(this.lugar.calle) + this.comprobarTexto(this.lugar.numero) + this.comprobarTexto(this.lugar.numero_departamento)
      } else {
        return ''
      }
    },
    ...mapGetters({
      permissions: 'auth/permissions',
      user: 'auth/user'
    })
  },
  mounted () {
    this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
      if (modalId === 'sinEfectoModal') {
        this.cleanForm()
        if (this.row && this.row.id) {
          this.allowEdit = true
          this.tituloModal = 'Editar Sin Efecto'
          this.sinefecto.id = this.row.id
          this.sinefecto.numero_de_registro = this.row.numero_de_registro
          this.setearHorayFecha(this.row.fecha_hora_registro, this.row.fecha_hora_solicitud)
          this.lugar = this.row.lugar
          this.sinefecto.lugar_id = this.row.lugar.id
          this.sinefecto.delegacion_dependencia_id = this.row.delegacion_dependencia.id
          if (this.row.oficios.length >= 1) {
            this.oficios = this.row.oficios
          } else {
            this.oficios = []
          }
          this.sinefecto.funcionario = this.row.funcionario
          this.sinefecto.descripcion = this.row.descripcion
        } else {
          this.allowEdit = false
          this.tituloModal = 'Nuevo Sin Efecto'
          this.cleanDatosLugar()
          this.oficios = []
          if (this.sinefecto.delegacion_dependencia_id === null) {
            this.sinefecto.delegacion_dependencia_id = this.user.dependencia_id
          }
          this.setearHorayFecha(this.getFechaActual(), this.getFechaActual())
        }
      }
    })
  },
  methods: {
    cleanForm () {
      this.fecha_sola_registro = null
      this.hora_sola_registro = null
      this.fecha_sola_solicitud = null
      this.hora_sola_solicitud = null
      this.min = null
      this.max = null
      this.sinefecto.id = ''
      this.sinefecto.numero_de_registro = 1
      this.sinefecto.fecha_hora_registro = ''
      this.sinefecto.fecha_hora_solicitud = ''
      this.sinefecto.delegacion_dependencia_id = null
      this.sinefecto.lugar_id = null
      this.sinefecto.funcionario = ''
      this.sinefecto.descripcion = ''
      this.$v.$reset()
    },
    cleanDatosLugar () {
      this.lugar = {}
      this.lugar.id = null
      this.lugar.latitud = null
      this.lugar.longitud = null
      this.lugar.departamento = null
      this.lugar.barrio = null
      this.lugar.localidad = null
      this.lugar.calle = null
      this.lugar.numero = null
      this.lugar.numero_departamento = null
    },
    getFechaActual () {
      const hoy = new Date()
      const fecha = hoy.getFullYear() + '-' + (hoy.getMonth() + 1) + '-' + hoy.getDate()
      const hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds()
      return fecha + ' ' + hora
    },
    setearHorayFecha (fechahoraregistro, fechahorasolicitud) {
      const result = fechahoraregistro.split(' ') // Se separa la fecha de la hora
      this.fecha_sola_registro = result[0] // Se setea la fecha
      this.hora_sola_registro = result[1] // Se setea la hora
      const result2 = fechahorasolicitud.split(' ') // Se separa la fecha de la hora
      this.fecha_sola_solicitud = result2[0] // Se setea la fecha
      this.hora_sola_solicitud = result2[1] // Se setea la hora
      if (!this.permissions.includes('crear_sin_efecto_viejo') && !this.permissions.includes('editar_sin_efecto_viejo')) { // Si no tiene ninguno de estos permisos se setea el minimo
        const minDate = new Date(fechahoraregistro) // Se clona la fecha en minDate para calcular el minimo permitido
        minDate.setDate(minDate.getDate() - 1) // Se deja que solo pueda modificar 1 dia hacia atras
        this.min = minDate // Se setea el min del date-picker
        const maxDate = new Date(fechahoraregistro) // Se clona la fecha en maxDate para calcular el maximo permitido
        const fechaActual = new Date() // Se necesita usar la fecha actual para ver que no pueda setear una fecha del futuro
        if (maxDate.getFullYear() !== fechaActual.getFullYear() || maxDate.getDate() !== fechaActual.getDate() || maxDate.getMonth() !== fechaActual.getMonth()) { // Se compara que el maxDate y el dia actual no sea el mismo
          maxDate.setDate(maxDate.getDate() + 1) // Si no son iguales se le permite editar 1 dia hacia adelante
        }
        this.max = maxDate // Se setea el max del date-picker
      } else {
        this.max = new Date() // Si tiene esos permisos puede editar hacia atras cuanto quiera y a futuro solo hasta la fecha actual
      }
    },
    abrirModalLugar () {
      this.$bvModal.show(this.modal.name)
    },
    handleOk (bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
    },
    modificarOficios (event) {
      this.mostrarSpinner(true)
      axios.get('api/oficio/searchBy/sinefecto/' + this.sinefecto.id)
        .then((result) => {
          this.oficios = result.data.data.slice()
          this.mostrarSpinner(false)
        })
    },
    handleSubmit () {
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.$bvToast.toast('Existen errores en el formulario', {
          title: 'Alerta',
          autoHideDelay: 5000
        })
      } else {
        this.mostrarSpinner(true)
        if (this.sinefecto.id) {
          this.submitEditar()
        } else {
          this.submitCrear()
        }
      }
    },
    submitEditar () {
      this.sinefecto.fecha_hora_registro = this.fecha_hora_registro
      this.sinefecto.fecha_hora_solicitud = this.fecha_hora_solicitud
      axios.put('api/sin-efecto/' + this.sinefecto.id, {
        sinefecto: this.sinefecto,
        lugar: this.lugar
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han editado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-sinefecto')
          this.$bvModal.hide('sinEfectoModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
    },
    submitCrear () {
      this.sinefecto.fecha_hora_registro = this.fecha_hora_registro
      this.sinefecto.fecha_hora_solicitud = this.fecha_hora_solicitud
      axios.post('api/sin-efecto', {
        sinefecto: this.sinefecto,
        lugar: this.lugar
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-sinefecto')
          this.$bvModal.hide('sinEfectoModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
    },
    recibirDatosModalLugar (event) {
      this.lugar = {}
      this.lugar.id = event.datos.id !== null ? event.datos.id : -1
      this.lugar.latitud = event.datos.latitud
      this.lugar.longitud = event.datos.longitud
      this.lugar.departamento = event.datos.departamento
      this.lugar.barrio = event.datos.barrio
      this.lugar.localidad = event.datos.localidad
      this.lugar.calle = event.datos.calle
      this.lugar.numero = event.datos.numero
      this.lugar.numero_departamento = event.datos.numero_departamento
    },
    comprobarTexto (texto) {
      if (texto) {
        return ' ' + texto
      } else {
        return ''
      }
    },
    mostrarErrores (errores) {
      let mensajeError = ''
      for (var prop in errores) {
        if (mensajeError === '') {
          mensajeError = errores[prop]
        } else {
          mensajeError = mensajeError + '\n' + errores[prop]
        }
      }

      Swal.fire({
        title: 'Error',
        text: mensajeError,
        icon: 'error',
        showCancelButton: false,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar'
      })
    },
    mostrarSpinner (boolean) {
      if (boolean) {
        this.isLoading = true
        setTimeout(() => {
          this.isLoading = false
        }, 5000)
      } else {
        this.isLoading = false
      }
    }
  }
}

</script>

<style scoped>

label {
  margin-bottom: 0rem !important;
}

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}

</style>
