<template>
  <b-row align-v="center">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"
    />
    <b-col>
      <b-card class="text-center" header="Datos del cadaver" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-row sm="6">
          <b-col>
            <b-form-checkbox
              id="checkbox-foto"
              v-model="foto"
              name="checkbox-foto"
              @change="changeDatos"
            >
              ¿Foto?
            </b-form-checkbox>
          </b-col>
          <b-col>
            <b-form-checkbox
              id="checkbox-identificado"
              v-model="identificado"
              name="checkbox-indentificado"
              @change="checkIdentificado"
            >
              ¿Identificado?
            </b-form-checkbox>
          </b-col>
        </b-row>
        <br>
        <b-row align-v="center">
          <b-col sm="3">
            <b-form-select v-model="cadaver_documento.tipo_documento" :disabled="!identificado"
                           :options="tipos_documentos"
                           @change="comprobarTipoDoc"
            />
          </b-col>
          <b-col sm="9">
            <b-form-input v-model="cadaver_documento.numero" :disabled="!identificado"
                          :class="{ 'is-invalid': $v.cadaver_documento.$error }"
                          min="0" placeholder="Numero de Documento" type="number" @change="comprobarNumeroDoc"
            />
          </b-col>
        </b-row>
        <b-row v-if="this.$v && this.$v.cadaver_documento.numero.$error"
               align-h="end"
               class="text-danger"
        >
          <b-col sm="9">
            <span v-if="!this.$v.cadaver_documento.numero.required">El documento es requerido</span>
            <span v-else>El numero debe tener entre {{
              this.$v.cadaver_documento.numero.$params.minLength.min
            }} a {{
              this.$v.cadaver_documento.numero.$params.maxLength.max
            }} cifras</span>
          </b-col>
        </b-row>
        <br>
        <b-row align-v="center">
          <b-col sm="3">
            <label>Nombre: </label>
          </b-col>
          <b-col sm="9">
            <b-form-input v-model="cadaver_nombre" :disabled="!identificado && !isTraslado"
                          :class="{ 'is-invalid': $v.cadaver_nombre.$error }"
                          placeholder="Nombre" @change="changeDatos"
            />
          </b-col>
        </b-row>
        <b-row v-if="this.$v && this.$v.cadaver_nombre.$error" align-h="end" class="text-danger">
          <b-col sm="9">
            <span v-if="!this.$v.cadaver_nombre.required">El nombre es requerido</span>
            <span v-else>El nombre debe tener entre {{
              this.$v.cadaver_nombre.$params.minLength.min
            }} a {{ this.$v.cadaver_nombre.$params.maxLength.max }} caracteres</span>
          </b-col>
        </b-row>
        <br>
        <b-row align-v="center">
          <b-col sm="3">
            <label>Observaciones: </label>
          </b-col>
          <b-col>
            <b-form-input v-model="observaciones"
                          :class="{ 'is-invalid': $v.observaciones.$error }"
                          placeholder="Observaciones" @change="changeDatos"
            />
          </b-col>
        </b-row>
        <b-row v-if="this.$v && this.$v.observaciones.$error" class="text-danger" align-h="end">
          <b-col>La observación puede tener como máximo {{ $v.observaciones.$params.maxLength.max }} letras</b-col>
        </b-row>
      </b-card>
    </b-col>
  </b-row>
</template>

<script>
import { maxLength, minLength, required, requiredIf } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'datosCadaver',
  components: {
    Loading
  },
  props: {
    datosCadaver: {
      type: Object,
      default: () => ({
        foto: false,
        identificado: true,
        cadaver_nombre: '',
        cadaver_documento: {
          id: null,
          tipo_documento: 'DNI',
          numero: null
        },
        observaciones: ''
      })
    }
  },
  validations: {
    cadaver_nombre: {
      minLength: minLength(2),
      maxLength: maxLength(100),
      required
    },
    cadaver_documento: {
      tipo_documento: {},
      numero: {
        minLength: minLength(7),
        maxLength: maxLength(11),
        required: requiredIf(function () {
          if (this.identificado) {
            return true
          } else {
            return false
          }
        })
      }
    },
    foto: { required },
    identificado: { required },
    observaciones: { maxLength: maxLength(250) }
  },
  data () {
    return {
      isLoading: false,
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
      tipos_documentos_renaper: ['DNI', 'LC', 'LE'],
      foto: false,
      identificado: true,
      cadaver_nombre: '',
      cadaver_documento: {
        id: null,
        tipo_documento: 'DNI',
        numero: null
      },
      observaciones: ''
    }
  },
  watch: {
    datosCadaver: function (newVal) {
      this.foto = newVal.foto
      this.identificado = newVal.identificado
      this.cadaver_nombre = newVal.cadaver_nombre
      this.cadaver_documento = {}
      if (newVal.cadaver_documento) {
        this.cadaver_documento.id = newVal.cadaver_documento.id
        this.cadaver_documento.tipo_documento = newVal.cadaver_documento.tipo_documento
        this.cadaver_documento.numero = newVal.cadaver_documento.numero
      } else {
        this.cadaver_documento.tipo_documento = this.tipos_documentos[0].value
      }
      this.observaciones = newVal.observaciones
    }
  },
  methods: {
    comprobarTipoDoc () {
      if (this.tipos_documentos_renaper.indexOf(this.cadaver_documento.tipo_documento) < 0) {
        this.cadaver_nombre = ''
      }
      this.changeDatos()
    },
    comprobarNumeroDoc () {
      if (this.tipos_documentos_renaper.indexOf(this.cadaver_documento.tipo_documento) >= 0 && this.cadaver_documento.numero.length > 6 && this.cadaver_documento.numero.length < 9) {
        this.mostrarSpinner(true)
        this.cadaver_nombre = ''
        axios.get('/api/external/renaper/' + this.cadaver_documento.numero).then((result) => {
          if (result.data.apellido) {
            this.cadaver_nombre = result.data.nombres + ' ' + result.data.apellido
          } else {
            result.data.error ? console.error(result.data.error) : console.error('No se pudo traer datos de la persona')
            this.cadaver_nombre = 'Sin datos'
          }
          this.mostrarSpinner(false)
          this.changeDatos()
        })
      } else {
        this.cadaver_nombre = ''
        this.changeDatos()
      }
    },
    checkIdentificado () {
      this.cadaver_nombre = this.identificado ? '' : 'NN'
      this.cadaver_documento.tipo_documento = this.tipos_documentos[0].value
      this.cadaver_documento.numero = null
      this.changeDatos()
    },
    changeDatos () {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeDatos', {
        foto: this.foto,
        identificado: this.identificado,
        cadaver_nombre: this.cadaver_nombre,
        cadaver_documento: this.cadaver_documento,
        observaciones: this.observaciones
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
