<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-modal id="dependenciaModal" header-class="border-bottom-0" footer-class="border-top-0" :title="tituloModal" modal size="lg" @ok="handleOk">
      <div class="modal-body">
        <b-container>
          <b-row align-v="center">
            <b-col>
              <b-card class="text-center" header="Datos Generales" header-bg-variant="primary"
                      header-text-variant="secondary"
              >
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Nombre:</label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="dependencia.nombre"
                                  placeholder="Ingrese el nombre"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.dependencia && $v.dependencia.nombre.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.dependencia.nombre.required">El nombre es requerido</span>
                    <span v-else>El nombre debe tener entre {{
                      $v.dependencia.nombre.$params.minLength.min
                    }} a {{ $v.dependencia.nombre.$params.maxLength.max }} letras</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Grupo:</label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="dependencia.grupo"
                                  placeholder="Ingrese el grupo"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.dependencia && $v.dependencia.grupo.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.dependencia.grupo.required">El grupo es requerido</span>
                    <span v-else>El grupo debe tener entre {{
                        $v.dependencia.grupo.$params.minLength.min
                      }} a {{ $v.dependencia.grupo.$params.maxLength.max }} letras</span>
                  </b-col>
                </b-row>
                <br>
                <b-row>
                  <b-col>
                    <b-form-checkbox
                      id="checkbox-cientifica"
                      v-model="dependencia.cientifica"
                      name="checkbox-cientifica"
                    >
                      ¿Pertenece a Científica?
                    </b-form-checkbox>
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
        <b-button type="button" @click="$bvModal.hide('dependenciaModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import { maxLength, minLength, required } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'dependenciaModal',
  components: {
    Loading
  },
  props: {
    row: {
      type: Object,
      default: () => ({})
    }
  },
  validations: {
    dependencia: {
      nombre: { required, minLength: minLength(4), maxLength: maxLength(100) },
      grupo: { required, minLength: minLength(4), maxLength: maxLength(100) },
      cientifica: { required }
    }
  },
  data () {
    return {
      tituloModal: 'Nueva Dependencia',
      isLoading: false,
      fullPage: true,
      url: '',
      dependencia: {
        id: '',
        nombre: '',
        grupo: '',
        cientifica: false
      }
    }
  },
  mounted () {
    this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
      this.cleanForm()
      if (this.row && this.row.id) {
        this.tituloModal = 'Editar Dependencia'
        this.dependencia.id = this.row.id
        this.dependencia.nombre = this.row.nombre
        this.dependencia.grupo = this.row.grupo
        this.dependencia.cientifica = this.row.cientifica
      } else {
        this.allowEdit = false
        this.tituloModal = 'Nueva Dependencia'
      }
    })
  },
  methods: {
    cleanForm () {
      this.dependencia.id = ''
      this.dependencia.nombre = ''
      this.dependencia.grupo = ''
      this.dependencia.cientifica = false
      this.$v.$reset()
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
        if (this.dependencia.id) {
          this.submitEditar()
        } else {
          this.submitCrear()
        }
      }
    },
    submitEditar () {
      axios.put('api/dependencia/' + this.dependencia.id, {
        dependencia: this.dependencia
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han editado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-dependencias')
          this.$bvModal.hide('dependenciaModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
    },
    submitCrear () {
      axios.post('api/dependencia/crear', {
        dependencia: this.dependencia
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-dependencias')
          this.$bvModal.hide('dependenciaModal')
        })
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

</style>
