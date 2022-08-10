<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-modal id="detalleOficioModal" title="Editar Oficio" header-class="border-bottom-0" footer-class="border-top-0" modal @ok="handleOk">
      <div class="modal-body">
        <b-container>
          <b-row align-v="center">
            <b-col>
              <b-card class="text-center" header="Datos del Oficio" header-bg-variant="primary"
                      header-text-variant="secondary"
              >
                <b-row align-v="center">
                  <b-col sm="4">
                    <label>Fecha </label>
                  </b-col>
                  <b-col sm="8">
                    <b-form-datepicker v-model="oficio.fecha_ingreso"
                                       :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                       :max="max"
                                       locale="es"
                    />
                  </b-col>
                </b-row>
                <b-row align-v="center" style="margin-top: 20px;">
                  <b-col sm="4">
                    <label>Solicitantes: </label>
                  </b-col>
                  <b-col sm="8">
                    <b-form-select v-model="oficio.instancia_solicitante_id"
                                   :class="{ 'is-invalid': $v.oficio.instancia_solicitante_id.$error }"
                                   :options="solicitantes" :reduce="solicitante => solicitante.id"
                                   text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione el solicitante"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.oficio && $v.oficio.instancia_solicitante_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="8">
                    La instancia solicitante es requerida
                  </b-col>
                </b-row>
                <b-row align-v="center" style="margin-top: 20px;">
                  <b-col sm="4">
                    <label>Observaciones </label>
                  </b-col>
                  <b-col sm="8">
                    <b-form-textarea
                      id="textareaObservacion"
                      v-model="oficio.observacion"
                      placeholder="Ingrese observaciones aquí"
                      rows="3"
                      max-rows="6"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.oficio && $v.oficio.observacion.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    La observación es requerida
                  </b-col>
                </b-row>
                <b-row align-v="center" style="margin-top: 20px;">
                  <b-col sm="4">
                    <label>Estado Oficio:</label>
                  </b-col>
                  <b-col sm="8">
                    <b-form-select v-model="oficio.estado_oficio_id" :options="estados_oficios" value-field="id"
                                   text-field="nombre"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.oficio && $v.oficio.estado_oficio_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="8">
                    El estado del oficio es requerido
                  </b-col>
                </b-row>
              </b-card>
            </b-col>
          </b-row>
        </b-container>
      </div>
      <template slot="modal-footer">
        <b-button type="submit" variant="primary" @click="handleOk">
          Guardar
        </b-button>
        <b-button type="button" @click="$bvModal.hide('detalleOficioModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import { mapGetters } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import Swal from 'sweetalert2'

export default {
  name: 'detalleOficioModal',
  components: {
    Loading
  },
  middleware: 'auth',
  props: {
    row: {
      type: Object,
      default: () => ({})
    }
  },
  validations: {
    oficio: {
      fecha_ingreso: { required },
      instancia_solicitante_id: { required },
      observacion: { required },
      estado_oficio_id: { required }
    }
  },
  data () {
    return {
      fullPage: true,
      isLoading: false,
      max: null,
      oficio: {
        id: '',
        fecha_ingreso: null,
        instancia_solicitante_id: null,
        observacion: null,
        estado_oficio_id: null
      },
      solicitantes: [],
      estados_oficios: []
    }
  },
  computed: {
    ...mapGetters({
      permissions: 'auth/permissions',
      user: 'auth/user'
    })
  },
  created () {
    this.getSolicitantes()
    this.getEstadosOficios()
    this.max = new Date()
  },
  mounted () {
    this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
      if (modalId === 'detalleOficioModal') {
        this.oficio.id = this.row.id
        this.oficio.fecha_ingreso = this.row.fecha_ingreso
        this.oficio.instancia_solicitante_id = this.row.instancia_solicitante.id
        this.oficio.observacion = this.row.observacion
        this.oficio.estado_oficio_id = this.row.estado_oficio_id
      }
    })
  },
  methods: {
    cleanForm () {
      this.oficio.fecha_ingreso = null
      this.oficio.instancia_solicitante_id = null
      this.oficio.observacion = null
      this.oficio.estado_oficio_id = null
      this.$v.$reset()
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
    },
    getSolicitantes () {
      this.mostrarSpinner(true)
      const url = '/api/intervinientes'
      axios.get(url).then((result) => {
        this.solicitantes = result.data.data.slice()
        this.mostrarSpinner(false)
      })
    },
    getEstadosOficios () {
      this.mostrarSpinner(true)
      axios.get('/api/estados-oficio').then((result) => {
        this.estados_oficios = result.data.data.slice()
        this.mostrarSpinner(false)
      })
    },
    handleOk (bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      // Trigger submit handler
      this.handleSubmit()
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
        this.submitEditar()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambio-detalle-oficio')
          this.$bvModal.hide('detalleOficioModal')
        })
      }
    },
    submitEditar () {
      axios.put('api/oficio/' + this.oficio.id, {
        oficio: this.oficio
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
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
    }
  }
}
</script>

<style scoped>

</style>
