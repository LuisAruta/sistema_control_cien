<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <lugar-modal :datoslugar="lugar" :departamentos="departamentos" @lugarEditadoEvent="recibirDatosModalLugar" />
    <b-modal id="trasladoModal" header-class="border-bottom-0" footer-class="border-top-0" :title="tituloModal" modal size="xl" @ok="handleOk">
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
                    <b-form-input v-model="traslado.numero_de_registro"
                                  :class="{ 'is-invalid': $v.traslado.numero_de_registro.$error }"
                                  placeholder="Ingrese número de registro"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.numero_de_registro.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    <span v-if="!this.$v.traslado.numero_de_registro.required">El número de registro es requerido</span>
                    <span v-else-if="!this.$v.traslado.numero_de_registro.minValue || !this.$v.traslado.numero_de_registro.maxValue">
                      El número debe ser hasta de 8 cifras</span>
                  </b-col>
                </b-row>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Fecha: </label>
                  </b-col>
                  <b-col lg="auto">
                    <b-form-datepicker v-model="fecha_sola"
                                       :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                       :max="max"
                                       :min="min"
                                       locale="es"
                    />
                  </b-col>
                  <b-col lg="auto">
                    <b-form-timepicker v-model="hora_sola" locale="es" />
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Legajo CMF:</label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="traslado.legajo_cuerpo_medico_forense"
                                  :class="{ 'is-invalid': $v.traslado.legajo_cuerpo_medico_forense.$error }"
                                  placeholder="Ingrese el legajo"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.legajo_cuerpo_medico_forense.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.traslado.legajo_cuerpo_medico_forense.required">El Legajo es requerido</span>
                    <span v-else>El legajo debe tener entre {{
                      $v.traslado.legajo_cuerpo_medico_forense.$params.minLength.min
                    }} a {{ $v.traslado.legajo_cuerpo_medico_forense.$params.maxLength.max }} cifras</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Delegación: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="traslado.delegacion_dependencia_id"
                                   :class="{ 'is-invalid': $v.traslado.delegacion_dependencia_id.$error }"
                                   :options="delegaciones"
                                   :reduce="delegacion => delegacion.id" text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione una dependencia"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.delegacion_dependencia_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    La delegación es requerida
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Interviniente: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="traslado.interviniento_dependencia_id"
                                   :class="{ 'is-invalid': $v.traslado.interviniento_dependencia_id.$error }"
                                   :options="intervinientes" :reduce="interviniente => interviniente.id"
                                   text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione un interviniente"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.interviniento_dependencia_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    El interviniente es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Perito: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="traslado.perito_usuario_id"
                                   :class="{ 'is-invalid': $v.traslado.perito_usuario_id.$error }" :options="perito_usuarios"
                                   :reduce="perito_usuario => perito_usuario.id" text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" placeholder="Seleccione un perito"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.perito_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El perito es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Acompañante: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="traslado.acompanante_usuario_id"
                              :class="{ 'is-invalid': $v.traslado.acompanante_usuario_id.$error}" :options="perito_usuarios"
                              :reduce="acompanante_usuario => acompanante_usuario.id" text-field="nombre_completo" value-field="id"
                              label="nombre" placeholder="Seleccione un acompañante"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.traslado && $v.traslado.acompanante_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El acompañante es requerido
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
          <br>
          <expediente ref="componenteExpediente" :requerido="exp_requerido" :expediente="expediente"
                      @changeDatosExpediente="modificarDatosExpediente($event)"
          />
          <tabla-oficios v-if="oficios.length >= 1" ref="componenteOficios" :oficios="oficios" @changeOficios="modificarOficios($event)" />
          <datos-cadaver ref="componenteDatosCadaver" :datos-cadaver="datosCadaver"
                         :is-traslado="true"
                         @changeDatos="modificarDatosCadaver($event)" style="margin-top: 10px;"
          />
        </b-container>
      </div>
      <template slot="modal-footer">
        <b-button type="submit" variant="primary" @click="handleOk">
          Guardar
        </b-button>
        <b-button type="button" @click="$bvModal.hide('trasladoModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import { maxLength, maxValue, minLength, minValue, numeric, required } from 'vuelidate/lib/validators'
import axios from 'axios'

import Loading from 'vue-loading-overlay'
import lugarModal from '../shared/lugarModal'
import expediente from '../shared/expediente.vue'
import tablaOficios from '../oficio/tablaOficios'
import datosCadaver from '../shared/datosCadaver'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'trasladoModal',
  components: {
    expediente,
    lugarModal,
    tablaOficios,
    datosCadaver,
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
    perito_usuarios: {
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
    traslado: {
      numero_de_registro: { required, numeric, minValue: minValue(1), maxValue: maxValue(99999999) },
      legajo_cuerpo_medico_forense: {
        minLength: minLength(4),
        maxLength: maxLength(25),
        required
      },
      perito_usuario_id: { required },
      acompanante_usuario_id: { required },
      lugar_id: {},
      delegacion_dependencia_id: { required },
      interviniento_dependencia_id: { required }
    }
  },
  data () {
    return {
      tituloModal: 'Nuevo Traslado',
      allowEdit: false,
      isLoading: false,
      fullPage: true,
      url: '',
      fecha_sola: null,
      hora_sola: null,
      min: null,
      max: null,
      exp_requerido: false, // Aca es para colocar si el expediente es requerido o no
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
      traslado: {
        id: '',
        numero_de_registro: 1,
        perito_usuario_id: null,
        acompanante_usuario_id: null,
        delegacion_dependencia_id: null,
        interviniento_dependencia_id: null,
        legajo_cuerpo_medico_forense: '',
        lugar_id: null
      },
      expediente: {
        origen: 'MINISTERIO PUBLICO',
        numero_expediente: '',
        caratula: '',
        delito: '',
        dependencia: ''
      },
      oficios: [],
      datosCadaver: {
        foto: false,
        identificado: true,
        cadaver_nombre: '',
        cadaver_documento: null,
        observaciones: ''
      }
    }
  },
  computed: {
    fecha_hora: function () {
      return this.fecha_sola + ' ' + this.hora_sola
    },
    stringLugar: function () {
      if (this.lugar.departamento || this.lugar.localidad || this.lugar.barrio || this.lugar.calle || this.lugar.numero || this.lugar.numero_departamento) {
        return this.comprobarTexto(this.lugar.departamento) + this.comprobarTexto(this.lugar.localidad) + this.comprobarTexto(this.lugar.barrio) + this.comprobarTexto(this.lugar.calle) + this.comprobarTexto(this.lugar.numero) + this.comprobarTexto(this.lugar.numero_departamento)
      } else {
        return 'INDIQUE EL LUGAR PULSADO LA LUPA →'
      }
    },
    ...mapGetters({
      permissions: 'auth/permissions',
      user: 'auth/user'
    })
  },
  mounted () {
    this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
      if (modalId === 'trasladoModal') {
        this.cleanForm()
        if (this.row && this.row.id) {
          this.allowEdit = true
          this.tituloModal = 'Editar Traslado'
          this.traslado.id = this.row.id
          this.traslado.numero_de_registro = this.row.numero_de_registro
          this.setearHorayFecha(this.row.fecha_hora)
          this.lugar = this.row.lugar
          this.traslado.lugar_id = this.row.lugar.id
          this.traslado.legajo_cuerpo_medico_forense = this.row.legajo_cuerpo_medico_forense
          if (this.row.expediente) {
            this.expediente = this.row.expediente
          } else {
            this.expediente = {}
            this.expediente.origen = 'MINISTERIO PUBLICO'
            this.expediente.numero_expediente = ''
            this.expediente.caratula = ''
            this.expediente.delito = ''
            this.expediente.dependencia = ''
          }
          if (this.row.oficios.length >= 1) {
            this.oficios = this.row.oficios
          } else {
            this.oficios = []
          }
          this.datosCadaver = {
            foto: this.row.foto,
            cadaver_nombre: this.row.cadaver_nombre,
            cadaver_documento: this.row.cadaver_documento ? this.row.cadaver_documento : null,
            identificado: !!this.row.cadaver_documento,
            observaciones: this.row.observaciones
          }
          this.traslado.delegacion_dependencia_id = this.row.delegacion_dependencia.id
          this.traslado.interviniento_dependencia_id = this.row.interviniento_dependencia.id
          this.traslado.perito_usuario_id = this.row.perito_usuario.id
          this.traslado.acompanante_usuario_id = this.row.acompanante_usuario.id
        } else {
          this.allowEdit = false
          this.tituloModal = 'Nuevo Traslado'
          if (this.traslado.delegacion_dependencia_id === null) {
            this.traslado.delegacion_dependencia_id = this.user.dependencia_id
          }
          this.oficios = []
          this.cleanDatosCadaverExpediente()
          this.cleanDatosLugar()
          this.setearHorayFecha(this.getFechaActual())
        }
      }
    })
  },
  methods: {
    cleanForm () {
      this.fecha_sola = null
      this.hora_sola = null
      this.min = null
      this.max = null
      this.traslado.id = ''
      this.traslado.numero_de_registro = 1
      this.traslado.expediente_id = null
      this.traslado.perito_usuario_id = null
      this.traslado.acompanante_usuario_id = null
      this.traslado.delegacion_dependencia_id = null
      this.traslado.interviniento_dependencia_id = null
      this.traslado.legajo_cuerpo_medico_forense = ''
      this.traslado.lugar_id = null
      this.$v.$reset()
    },
    cleanDatosCadaverExpediente () {
      this.datosCadaver = {}
      this.datosCadaver.foto = false
      this.datosCadaver.identificado = false
      this.datosCadaver.cadaver_nombre = 'NN'
      this.datosCadaver.cadaver_documento = null
      this.datosCadaver.observaciones = null
      this.expediente = {}
      this.expediente.origen = 'MINISTERIO PUBLICO'
      this.expediente.numero_expediente = ''
      this.expediente.caratula = ''
      this.expediente.delito = ''
      this.expediente.dependencia = ''
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
    setearHorayFecha (fechahora) {
      const result = fechahora.split(' ') // Se separa la fecha de la hora
      this.fecha_sola = result[0] // Se setea la fecha
      this.hora_sola = result[1] // Se setea la hora
      if (!this.permissions.includes('crear_traslado_viejo') && !this.permissions.includes('editar_traslado_viejo')) { // Si no tiene ninguno de estos permisos se setea el minimo
        const minDate = new Date(fechahora) // Se clona la fecha en minDate para calcular el minimo permitido
        minDate.setDate(minDate.getDate() - 1) // Se deja que solo pueda modificar 1 dia hacia atras
        this.min = minDate // Se setea el min del date-picker
        const maxDate = new Date(fechahora) // Se clona la fecha en maxDate para calcular el maximo permitido
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
    modificarDatosCadaver (event) {
      this.datosCadaver.foto = event.foto
      this.datosCadaver.cadaver_nombre = event.cadaver_nombre
      this.datosCadaver.cadaver_documento = event.cadaver_documento
      this.datosCadaver.identificado = event.identificado
      this.datosCadaver.observaciones = event.observaciones
    },
    modificarDatosExpediente (event) {
      this.expediente = {}
      this.expediente.origen = event.origen
      this.expediente.numero_expediente = event.numero_expediente
      this.expediente.caratula = event.caratula
      this.expediente.delito = event.delito
      this.expediente.dependencia = event.dependencia
    },
    modificarOficios (event) {
      this.mostrarSpinner(true)
      axios.get('api/oficio/searchBy/traslado/' + this.traslado.id)
        .then((result) => {
          this.oficios = result.data.data.slice()
          this.mostrarSpinner(false)
        })
    },
    handleSubmit () {
      this.$refs.componenteDatosCadaver.$v.$touch()
      this.$refs.componenteExpediente.$v.$touch()
      this.$v.$touch()
      if (this.$v.$invalid || this.$refs.componenteExpediente.$v.$invalid || this.$refs.componenteDatosCadaver.$v.$invalid) {
        this.$bvToast.toast('Existen errores en el formulario', {
          title: 'Alerta',
          autoHideDelay: 5000
        })
      } else {
        this.mostrarSpinner(true)
        if (this.traslado.id) {
          this.submitEditar()
        } else {
          this.submitCrear()
        }
      }
    },
    submitEditar () {
      this.traslado.fecha_hora = this.fecha_hora
      axios.put('api/traslado/' + this.traslado.id, {
        traslado: this.traslado,
        lugar: this.lugar,
        expediente: this.expediente,
        cadaver: this.datosCadaver
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han editado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-traslado')
          this.$bvModal.hide('trasladoModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
    },
    submitCrear () {
      this.traslado.fecha_hora = this.fecha_hora
      axios.post('api/traslado', {
        traslado: this.traslado,
        lugar: this.lugar,
        expediente: this.expediente,
        cadaver: this.datosCadaver
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-traslado')
          this.$bvModal.hide('trasladoModal')
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
        }, 2000)
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

label {
  color: black;
}
</style>
