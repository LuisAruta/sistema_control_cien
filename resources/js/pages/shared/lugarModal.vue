<template>
  <b-modal id="lugarModal" :lazy="true" :title="tituloModal" header-class="border-bottom-0" footer-class="border-top-0" no-close-on-backdrop @ok="handleOk">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="true"
    />
    <div class="modal-body">
      <b-card class="text-center" header="Datos del lugar" header-bg-variant="primary" header-text-variant="secondary">
        <b-row>
          <b-col>
            <b-input-group-text>Departamento:</b-input-group-text>
            <b-form-select v-model="datos.departamento" :options="departamentos" text-field="DEPTO"
                           :class="{ 'is-invalid': $v.datos.departamento.$error }"
                           value-field="DEPTO" @change="getDatosFromDepartamento"
            />
          </b-col>
        </b-row>
        <b-row v-if="$v.datos && $v.datos.departamento.$error" class="text-danger" align-h="end">
          <b-col>
            El departamento es requerido
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-input-group-text>Localidad:</b-input-group-text>
            <b-form-input v-model="datos.localidad" list="lista-localidades" autocomplete="off"
                          :class="{ 'is-invalid': $v.datos.localidad.$error }"/>
            <b-form-datalist id="lista-localidades" :options="localidades" />
          </b-col>
        </b-row>
        <b-row v-if="$v.datos && $v.datos.localidad.$error" class="text-danger" align-h="end">
          <b-col>La localidad puede tener como máximo {{ $v.datos.barrio.$params.maxLength.max }} letras</b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-input-group-text>Barrio:</b-input-group-text>
            <b-form-input v-model="datos.barrio" :class="{ 'is-invalid': $v.datos.barrio.$error }" list="lista-barrios" />
          </b-col>
        </b-row>
        <b-row v-if="$v.datos && $v.datos.barrio.$error" class="text-danger" align-h="end">
          <b-col>El barrio puede tener como máximo {{ $v.datos.barrio.$params.maxLength.max }} letras</b-col>
        </b-row>
        <b-row>
          <b-col>
            <b-input-group-text>Calle:</b-input-group-text>
            <b-form-input v-model="datos.calle" :class="{ 'is-invalid': $v.datos.calle.$error }" list="lista-calle" />
          </b-col>
        </b-row>
        <b-row v-if="$v.datos && $v.datos.calle.$error" class="text-danger" align-h="end">
          <b-col>La calle puede tener como máximo {{ $v.datos.calle.$params.maxLength.max }} letras</b-col>
        </b-row>
        <b-row>
          <b-col sm="5">
            <b-input-group-text>N°Casa:</b-input-group-text>
            <b-form-input v-model="datos.numero" :class="{ 'is-invalid': $v.datos.numero.$error }"/>
          </b-col>
          <b-col>
            <b-input-group-text>N°Departamento:</b-input-group-text>
            <b-form-input v-model="datos.numero_departamento" :class="{ 'is-invalid': $v.datos.numero_departamento.$error }"/>
          </b-col>
        </b-row>
        <b-row v-if="$v.datos && ($v.datos.numero.$error || $v.datos.numero_departamento.$error)" class="text-danger" align-h="end">
          <b-col>El número es muy largo, máximo: {{ $v.datos.numero.$params.maxLength.max }} números</b-col>
        </b-row>
      </b-card>
    </div>
    <template slot="modal-footer">
      <b-button type="button" variant="primary" @click="handleOk">
        Guardar
      </b-button>
      <b-button type="button" @click="handleCancel">
        Cancelar
      </b-button>
    </template>
  </b-modal>
</template>

<script>
import { maxLength, required } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/vue-loading.css'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'lugarModal',
  components: {
    Loading
  },
  props: {
    datoslugar: {
      type: Object,
      default: () => ({})
    },
    departamentos: {
      type: Array,
      default: () => ([])
    }
  },
  validations: {
    datos: {
      id: {},
      departamento: { required, maxLength: maxLength(100) },
      barrio: { maxLength: maxLength(100) },
      localidad: { maxLength: maxLength(100) },
      calle: { maxLength: maxLength(100) },
      numero: { maxLength: maxLength(10) },
      numero_departamento: { maxLength: maxLength(10) }
    }
  },
  data () {
    return {
      isLoading: false,
      tituloModal: 'Nuevo Lugar',
      localidades: [],
      barrios: [],
      calles: [],
      datos: {
        id: null,
        departamento: null,
        barrio: null,
        localidad: null,
        calle: null,
        numero: null,
        numero_departamento: null,
        created_at: null,
        updated_at: null
      }
    }
  },
  mounted () {
    this.$root.$on('bv::modal::shown', () => {
      this.cleanFormLugar()
      if (this.datoslugar && this.datoslugar.id) {
        this.allowEdit = true
        this.tituloModal = 'Editar Lugar'
        this.datos.id = this.datoslugar.id
        this.datos.latitud = this.datoslugar.latitud
        this.datos.longitud = this.datoslugar.longitud
        this.datos.departamento = this.datoslugar.departamento
        this.datos.barrio = this.datoslugar.barrio
        this.datos.localidad = this.datoslugar.localidad
        this.datos.calle = this.datoslugar.calle
        this.datos.numero = this.datoslugar.numero
        this.datos.numero_departamento = this.datoslugar.numero_departamento
        this.datos.created_at = this.datoslugar.created_at
        this.datos.updated_at = this.datoslugar.updated_at
      } else {
        this.allowEdit = false
        this.tituloModal = 'Nuevo Lugar'
      }
    })
  },
  methods: {
    cleanFormLugar () {
      this.datos.id = null
      this.datos.departamento = null
      this.datos.barrio = null
      this.datos.localidad = null
      this.datos.calle = null
      this.datos.numero = null
      this.datos.numero_departamento = null
      this.datos.created_at = null
      this.datos.updated_at = null
      this.$v.$reset()
    },
    getDatosFromDepartamento () {
      this.datos.calle = ''
      this.calles = []
      this.datos.barrio = ''
      this.barrios = []
      this.getLocalidades()
    },
    getLocalidades () {
      this.mostrarSpinner(true)
      this.datos.localidad = null
      axios.get('/api/external/localidades/' + this.datos.departamento).then((result) => {
        this.localidades = result.data.data.slice().map(function (x) {
          return x.NOM_DIST
        })
        this.mostrarSpinner(false)
      })
    },
    getBarrios (event) {
      this.mostrarSpinner(true)
      axios.get('/api/external/barrios/' + this.datos.departamento + '/' + event).then((result) => {
        this.barrios = result.data.data.slice().map(function (x) {
          return x.label
        })
        this.mostrarSpinner(false)
      })
    },
    getCalles (event) {
      this.mostrarSpinner(true)
      axios.get('/api/external/calles/' + this.datos.departamento + '/' + event).then((result) => {
        this.calles = result.data.data.slice().map(function (x) {
          return x.label
        })
        this.mostrarSpinner(false)
      })
    },
    handleOk (bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault()
      this.$v.$touch()
      if (this.$v.$invalid) {
        this.$bvToast.toast('Existen errores en el formulario', {
          title: 'Alerta',
          autoHideDelay: 5000
        })
      } else {
        this.$nextTick(() => {
          // eslint-disable-next-line vue/custom-event-name-casing
          this.$emit('lugarEditadoEvent', { datos: this.datos })
          this.$bvModal.hide('lugarModal')
        })
      }
    },
    handleCancel (bvModalEvt) {
      this.$bvModal.hide('lugarModal')
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
  margin-bottom: 0 !important;
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
