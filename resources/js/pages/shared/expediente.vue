<template>
  <b-row align-v="center">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"
    />
    <b-col>
      <b-card class="text-center" header="Datos del Expediente" header-bg-variant="primary"
              header-text-variant="secondary"
      >
        <b-row align-v="center">
          <b-col sm="5">
            <b-form-select v-model="origen" :options="origenes" @change="changeOrigen" />
          </b-col>
          <b-col sm="3">
            <label>Nº Expediente: </label>
          </b-col>
          <b-col sm="4">
            <b-input-group v-if="origen === 'MINISTERIO PUBLICO'">
              <b-form-input v-model="numero_expediente" v-mask="'A9999999999/99'"
                            placeholder="P0000000000/00"
                            type="text" @blur="getDatosExpediente" @keyup="onKeyUp" @paste.prevent
              />
            </b-input-group>
            <b-input-group v-if="origen !== 'MINISTERIO PUBLICO'">
              <b-form-input v-model="numero_expediente"
                            :class="{ 'is-invalid': $v.expediente.numero_expediente.$error }"
                            type="text" @paste.prevent @change="changeDatos"
              />
            </b-input-group>
          </b-col>
        </b-row>
        <b-row v-if="validacion && origen === 'MINISTERIO PUBLICO' && dependencia !== ''">
          <b-col sm="12">
            <b-list-group>
              <b-list-group-item>Caratula: <b>{{ caratula }}</b></b-list-group-item>
              <b-list-group-item>Dependencia: <b>{{ dependencia }}</b></b-list-group-item>
              <b-list-group-item>Delito: <b>{{ delito }}</b></b-list-group-item>
            </b-list-group>
          </b-col>
        </b-row>
        <b-row v-if="$v.expediente && ($v.expediente.numero_expediente.$error || $v.expediente.caratula.$error)">
          <b-col v-if="$v.expediente.numero_expediente.$error" class="text-danger">
            <span v-if="!$v.expediente.numero_expediente.required">El número de expediente es requerido</span>
            <span v-else>El número de expediente puede tener como máximo {{ $v.expediente.numero_expediente.$params.maxLength.max }} caracteres.</span>
          </b-col>
          <b-col v-if="$v.expediente.caratula.$error" class="text-danger">
            La caratula es requerida si es del Ministerio Público
          </b-col>
        </b-row>
      </b-card>
    </b-col>
  </b-row>
</template>
<script>
import { maxLength, required, requiredIf } from 'vuelidate/lib/validators'
import axios from 'axios'
import AwesomeMask from 'awesome-mask'

import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'expediente',
  components: {
    Loading
  },
  directives: {
    mask: AwesomeMask
  },
  props: {
    expediente: {
      type: Object,
      default: () => ({
        numero_expediente: '',
        caratula: '',
        delito: '',
        dependencia: '',
        origen: 'MINISTERIO PUBLICO'
      })
    },
    requerido: {
      type: Boolean,
      default: false
    }
  },
  validations: {
    expediente: {
      numero_expediente: {
        maxLength: maxLength(20),
        required: requiredIf(function () {
          return this.requerido
        })
      },
      caratula: {
        required: requiredIf(function () {
          if (this.requerido && this.origen === 'MINISTERIO PUBLICO') {
            return true
          } else {
            return false
          }
        })
      },
      delito: {},
      dependencia: {},
      origen: { required }
    }
  },
  data () {
    return {
      isLoading: false,
      numero_expediente: 'P0000000000/00',
      last_numero_expediente: 'P0000000000/00',
      caratula: '',
      delito: '',
      dependencia: '',
      validacion: false,
      origen: 'MINISTERIO PUBLICO',
      origenes: ['MINISTERIO PUBLICO', 'OTRO']
    }
  },
  watch: {
    expediente: function (newVal) {
      this.origen = newVal.origen
      this.numero_expediente = newVal.numero_expediente
      this.caratula = newVal.caratula
      this.delito = newVal.delito
      this.dependencia = newVal.dependencia
    }
  },
  methods: {
    mostrarSpinner (boolean) {
      if (boolean) {
        this.isLoading = true
        setTimeout(() => {
          this.isLoading = false
        }, 2000)
      } else {
        this.isLoading = false
      }
    },
    changeOrigen () {
      this.caratula = ''
      this.delito = ''
      this.dependencia = ''
      this.numero_expediente = ''
      this.changeDatos()
    },
    changeDatos () {
      // eslint-disable-next-line vue/custom-event-name-casing
      this.$emit('changeDatosExpediente', {
        origen: this.origen,
        caratula: this.caratula,
        delito: this.delito,
        dependencia: this.dependencia,
        numero_expediente: this.numero_expediente
      })
    },
    getDatosExpediente () {
      if (this.numero_expediente) {
        this.mostrarSpinner(true)
        this.validacion = false
        const numero = this.numero_expediente.replace('/', '')
        axios.get('/api/external/expediente/' + numero).then((result) => {
          this.validacion = true
          if (result.data.error) {
            console.error(result.data.error)
            this.caratula = 'SIN DATOS'
            this.delito = 'SIN DATOS'
            this.dependencia = 'SIN DATOS'
          } else {
            this.caratula = result.data.caratula
            this.delito = result.data.delito.descripcion
            this.dependencia = result.data.dependecia.descripcion
          }
          this.changeDatos()
          this.mostrarSpinner(false)
        })
      }
    },
    onKeyUp (e) {
      let response
      if (
        ((e.keyCode >= 48 && e.keyCode <= 57) ||
          (e.keyCode >= 96 && e.keyCode <= 105) ||
          (e.keyCode >= 65 && e.keyCode <= 90) ||
          e.keyCode === 8 ||
          e.keyCode === 46) &&
        !(e.keyCode >= 37 && e.keyCode <= 40 && e.keyCode === 13 && e.keyCode === 17)
      ) {
        this.numero_expediente.replace('/', '')
        response = this.formatMaskText(e.keyCode, this.numero_expediente, this.last_numero_expediente)
        this.numero_expediente = this.numero_expediente.substr(0, this.numero_expediente.length - 3) + '/' + this.numero_expediente.substr(this.numero_expediente.length - 3, this.numero_expediente.length - 1)
        // e.preventDefault();
        this.numero_expediente = response
      }
    },
    formatMaskText (keyCode, maskedNumeroExpediente, lastMaskedNumeroExpediente) {
      let tempMaskedLetraExpediente = maskedNumeroExpediente.substr(0, 1)

      let tempNroExpediente = maskedNumeroExpediente.replace(
        /\D/g,
        ''
      )

      if (tempMaskedLetraExpediente === '_') {
        tempMaskedLetraExpediente = lastMaskedNumeroExpediente.substr(0, 1)
      }

      if (!tempNroExpediente) {
        tempNroExpediente = lastMaskedNumeroExpediente.replace(
          /\D/g,
          ''
        )
      }

      let calculatedKeyCode = keyCode
      if (keyCode >= 96 && keyCode <= 105) {
        calculatedKeyCode = keyCode - 48
      }

      // ingresa una letra
      if (keyCode >= 65 && keyCode <= 90) {
        tempMaskedLetraExpediente = String.fromCharCode(keyCode)
        // borra un caracter
      } else if ((tempNroExpediente && tempNroExpediente.length < 12) || calculatedKeyCode === 8) {
        tempNroExpediente = (
          '000000000000' + tempNroExpediente
        ).substr(tempNroExpediente.length, 12)
        // ingresa un digito
      } else {
        tempNroExpediente = (
          '000000000000' +
          tempNroExpediente +
          String.fromCharCode(calculatedKeyCode)
        ).substr(tempNroExpediente.length + 1, 12)
      }

      if (tempMaskedLetraExpediente !== '_' && tempNroExpediente) {
        return tempMaskedLetraExpediente + tempNroExpediente
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
</style>
