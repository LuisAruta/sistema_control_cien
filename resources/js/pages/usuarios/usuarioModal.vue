<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <b-modal id="usuarioModal" header-class="border-bottom-0" footer-class="border-top-0" :title="tituloModal" modal size="lg" @ok="handleOk">
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
                    <b-form-input v-model="usuario.name"
                                  :class="{ 'is-invalid': $v.usuario.name.$error }"
                                  placeholder="Ingrese el nombre"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.usuario && $v.usuario.name.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.usuario.name.required">El nombre es requerido</span>
                    <span v-else>El nombre debe tener entre {{
                      $v.usuario.name.$params.minLength.min
                    }} a {{ $v.usuario.name.$params.maxLength.max }} letras</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Email:</label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="usuario.email"
                                  :class="{ 'is-invalid': $v.usuario.email.$error }"
                                  placeholder="Ingrese el email" @change="()=>{this.error_email = false}"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.usuario && $v.usuario.email.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.usuario.email.required">El email es requerido</span>
                    <span v-else-if="!$v.usuario.email.email">El email no tiene un formato correcto</span>
                    <span v-else>El email debe tener entre {{
                      $v.usuario.email.$params.minLength.min
                    }} a {{ $v.usuario.email.$params.maxLength.max }} letras</span>
                  </b-col>
                </b-row>
                <b-row v-if="this.error_email" align-h="end" class="text-danger">
                  <b-col sm="9">
                    <span>El email ya esta registrado en la base</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Dependencia: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="usuario.dependencia_id"
                                   :class="{ 'is-invalid': $v.usuario.dependencia_id.$error }"
                                   :options="dependencias"
                                   :reduce="dependencia => dependencia.id" text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione una dependencia"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.usuario && $v.usuario.dependencia_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    La dependencia es requerida
                  </b-col>
                </b-row>
                <br>
                <b-row>
                  <b-col>
                    <b-form-checkbox
                      id="checkbox-perito"
                      v-model="usuario.perito"
                      name="checkbox-perito"
                    >
                      ¿Es perito?
                    </b-form-checkbox>
                  </b-col>
                </b-row>
                <br>
                <b-row>
                  <b-col sm="3">
                    <label>Roles: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="usuario.rolesUsuario"
                                   :class="{ 'is-invalid': $v.usuario.rolesUsuario.$error }"
                                   :options="roles" text-field="name" value-field="id" multiple :select-size="4" />
                  </b-col>
                </b-row>
                <b-row v-if="$v.usuario && $v.usuario.rolesUsuario.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.usuario.rolesUsuario.required">Es requerido que posea al menos un rol</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Documento:</label>
                  </b-col>
                  <b-col sm="3">
                    <b-form-select v-model="documento.tipo_documento" :options="tipos_documentos" @change="comprobarTipoDoc" />
                  </b-col>
                  <b-col sm="6">
                    <b-form-input v-model="documento.numero" min="0" placeholder="Numero de Documento" type="number" @change="comprobarNumeroDoc" />
                  </b-col>
                </b-row>
                <b-row v-if="this.$v && this.$v.documento.numero.$error"
                       align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!this.$v.documento.numero.required">El documento es requerido</span>
                    <span v-else>El número debe tener entre {{
                      this.$v.documento.numero.$params.minLength.min
                    }} a {{
                      this.$v.documento.numero.$params.maxLength.max
                    }} cifras</span>
                  </b-col>
                </b-row>
                <b-row align-h="end" class="text-danger">
                  <b-col v-if="this.error_documento" sm="9">
                    <span>El documento ya esta registrado en la base</span>
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Nombre Completo:</label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-input v-model="usuario.nombre_completo"
                                  :class="{ 'is-invalid': $v.usuario.nombre_completo.$error }"
                                  placeholder=""
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.usuario && $v.usuario.nombre_completo.$error" align-h="end"
                       class="text-danger"
                >
                  <b-col sm="9">
                    <span v-if="!$v.usuario.nombre_completo.required">El nombre completo es requerido</span>
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
        <b-button type="button" @click="$bvModal.hide('usuarioModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import { maxLength, minLength, required, email } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'usuarioModal',
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
    usuario: {
      name: { required, minLength: minLength(4), maxLength: maxLength(100) },
      email: { required, minLength: minLength(6), maxLength: maxLength(100), email },
      dependencia_id: { required },
      nombre_completo: { required },
      rolesUsuario: { required },
      perito: { }
    },
    documento: {
      tipo_documento: { required },
      numero: { required, minLength: minLength(7), maxLength: maxLength(13) }
    }
  },
  data () {
    return {
      tituloModal: 'Nuevo Usuario',
      isLoading: false,
      allowEdit: false,
      fullPage: true,
      url: '',
      textoError: 'No posee legajo digital',
      tieneLegajo: false,
      error_documento: false,
      error_email: false,
      usuario: {
        id: '',
        name: '',
        email: '',
        dependencia_id: null,
        nombre_completo: '',
        rolesUsuario: [],
        perito: false
      },
      documento: {
        id: null,
        tipo_documento: 'DNI',
        numero: null
      },
      dependencias: [],
      roles: [],
      tipos_documentos: [
        {
          value: 'DNI',
          text: 'DNI'
        },
        {
          value: 'LC',
          text: 'LC'
        },
        {
          value: 'LE',
          text: 'LE'
        },
        {
          value: 'EXT',
          text: 'EXT'
        },
        {
          value: 'Pasaporte',
          text: 'Pasaporte'
        }
      ],
      tipos_documentos_personal: ['DNI', 'LC', 'LE']
    }
  },
  mounted () {
    this.$root.$on('bv::modal::shown', (bvEvent, modalId) => {
      if (modalId === 'usuarioModal') {
        this.cleanForm()
        if (this.row && this.row.id) {
          this.tituloModal = 'Editar Usuario'
          this.allowEdit = true
          this.usuario.id = this.row.id
          this.usuario.name = this.row.name
          this.usuario.email = this.row.email
          this.usuario.dependencia_id = this.row.dependencia.id
          this.usuario.nombre_completo = this.row.nombre_completo
          this.usuario.perito = this.row.perito
          this.usuario.rolesUsuario = this.row.roles.map(item => {
            return item.id
          })
          this.documento.id = this.row.documento.id
          this.documento.tipo_documento = this.row.documento.tipo_documento
          this.documento.numero = this.row.documento.numero
        } else {
          this.allowEdit = false
          this.tituloModal = 'Nuevo Usuario'
        }
      }
    })
  },
  created () {
    this.getDependencias()
    this.getRoles()
  },
  methods: {
    comprobarTipoDoc () {
      if (this.tipos_documentos_personal.indexOf(this.documento.tipo_documento) < 0) {
        this.documento.numero = ''
        this.usuario.nombre_completo = ''
        this.tieneLegajo = false
      }
    },
    comprobarNumeroDoc () {
      this.error_documento = false
      if (this.tipos_documentos_personal.indexOf(this.documento.tipo_documento) >= 0 && this.documento.numero.length > 6 && this.documento.numero.length < 9) {
        this.usuario.nombre_completo = ''
        axios.get('/api/external/personal/' + this.documento.numero).then((result) => {
          this.mostrarSpinner(true)
          if (!result.data.error) {
            const persona = JSON.parse(result.data)
            if (persona[0]) {
              this.usuario.nombre_completo = persona[0].LegNombre + ' ' + persona[0].LegApellido
              this.tieneLegajo = true
            } else {
              this.usuario.nombre_completo.placeholder = this.textoError
              this.tieneLegajo = false
            }
          } else {
            console.error(result.data.error)
            this.usuario.nombre_completo.placeholder = this.textoError
            this.tieneLegajo = false
          }
          this.mostrarSpinner(false)
        })
      } else {
        this.usuario.nombre_completo.placeholder = this.textoError
      }
    },
    cleanForm () {
      this.error_documento = false
      this.error_email = false
      this.usuario.id = ''
      this.usuario.name = ''
      this.usuario.email = ''
      this.usuario.dependencia_id = null
      this.usuario.nombre_completo = ''
      this.usuario.rol_id = ''
      this.usuario.perito = false
      this.usuario.rolesUsuario = []
      this.documento.id = null
      this.documento.tipo_documento = 'DNI'
      this.documento.numero = ''
      this.tieneLegajo = false
      this.$v.$reset()
    },
    getDependencias () {
      this.mostrarSpinner(true)
      const url = '/api/delegaciones'
      axios.get(url).then((result) => {
        this.dependencias = result.data.data.slice()
        this.mostrarSpinner(false)
      })
    },
    getRoles () {
      this.mostrarSpinner(true)
      const url = '/api/roles'
      axios.get(url).then((result) => {
        this.roles = result.data.data
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
        if (this.usuario.id) {
          this.submitEditar()
        } else {
          this.submitCrear()
        }
      }
    },
    submitEditar () {
      axios.put('api/user/' + this.usuario.id, {
        usuario: this.usuario,
        documento: this.documento
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han editado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-usuarios')
          this.$bvModal.hide('usuarioModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        if (error.response.data.errors['documento.numero']) {
          this.error_documento = true
        }
        if (error.response.data.errors['usuario.email']) {
          this.error_email = true
        }
        this.mostrarErrores(error.response.data.errors)
      })
    },
    submitCrear () {
      axios.post('api/user/crear', {
        usuario: this.usuario,
        documento: this.documento
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-usuarios')
          this.$bvModal.hide('usuarioModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        if (error.response.data.errors['documento.numero']) {
          this.error_documento = true
        }
        if (error.response.data.errors['usuario.email']) {
          this.error_email = true
        }
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
