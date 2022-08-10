<template>
  <b-form @submit.stop.prevent="handleSubmit">
    <loading :active.sync="isLoading"
             :can-cancel="true"
             :is-full-page="fullPage"
    />
    <lugar-modal :datoslugar="lugar" :departamentos="departamentos" @lugarEditadoEvent="recibirDatosModalLugar" />
    <b-modal id="intervencionTecnicaModal" header-class="border-bottom-0" footer-class="border-top-0" :title="tituloModal" modal size="xl" @ok="handleOk">
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
                    <b-form-input v-model="intervenciontecnica.numero_de_registro"
                                  :class="{ 'is-invalid': $v.intervenciontecnica.numero_de_registro.$error }"
                                  placeholder="Ingrese número de registro"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.numero_de_registro.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    <span v-if="!this.$v.intervenciontecnica.numero_de_registro.required">El número de registro es requerido</span>
                    <span v-else-if="!this.$v.intervenciontecnica.numero_de_registro.minValue || !this.$v.intervenciontecnica.numero_de_registro.maxValue">
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
                    <label>Tipo Intervención: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.tipo_intervencion_tecnica_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.tipo_intervencion_tecnica_id.$error }"
                                   :options="tipo_intervenciones_tecnica"
                                   :reduce="delegacion => delegacion.id" text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione un tipo de intervención"
                                   @change="cambiarDelitosporIDTipo"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.tipo_intervencion_tecnica_id.$error" align-h="end"
                       align-v="center"
                >
                  <b-col class="text-danger" sm="9">
                    El tipo de intervención es requerida
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Delegación: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.delegacion_dependencia_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.delegacion_dependencia_id.$error }"
                                   :options="delegaciones"
                                   :reduce="delegacion => delegacion.id" text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione una dependencia"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.delegacion_dependencia_id.$error" align-h="end"
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
                    <b-form-select v-model="intervenciontecnica.interviniento_dependencia_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.interviniento_dependencia_id.$error }"
                                   :options="intervinientes" :reduce="interviniente => interviniente.id"
                                   text-field="nombre" value-field="id"
                                   label="nombre" placeholder="Seleccione un interviniente"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.interviniento_dependencia_id.$error" align-h="end"
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
                    <b-form-select v-model="intervenciontecnica.perito_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.perito_usuario_id.$error }" :options="perito_usuarios"
                                   :reduce="perito_usuario => perito_usuario.id" text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" placeholder="Seleccione un perito"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.perito_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El perito es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Técnico: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.tecnico_revelador_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.tecnico_revelador_usuario_id.$error }" :options="perito_usuarios"
                                   :reduce="tecnico_usuario => tecnico_usuario.id" text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" placeholder="Seleccione un técnico"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.tecnico_revelador_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El técnico revelador es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <label>Secretario: </label>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.secretario_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.secretario_usuario_id.$error }" :options="perito_usuarios"
                                   :reduce="secretario_usuario => secretario_usuario.id" text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" placeholder="Seleccione un secretario"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.secretario_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El secretario es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <b-form-checkbox
                      id="checkbox-plano"
                      v-model="intervenciontecnica.plano"
                      name="checkbox-plano"
                    >
                      ¿Plano?
                    </b-form-checkbox>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.planimetrista_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.planimetrista_usuario_id.$error }"
                                   :options="perito_usuarios" :reduce="planimetrista_usuario => planimetrista_usuario.id"
                                   text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" :disabled="!intervenciontecnica.plano" placeholder="Seleccione un planimetrista"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.planimetrista_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El planimetrista es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <b-form-checkbox
                      id="checkbox-foto"
                      v-model="intervenciontecnica.foto"
                      name="checkbox-foto"
                    >
                      ¿Foto?
                    </b-form-checkbox>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.fotografo_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.fotografo_usuario_id.$error }"
                                   :options="perito_usuarios" :reduce="fotografo_usuario => fotografo_usuario.id"
                                   text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" :disabled="!intervenciontecnica.foto" placeholder="Seleccione un fotógrafo"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.fotografo_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El fotografo es requerido
                  </b-col>
                </b-row>
                <br>
                <b-row align-v="center">
                  <b-col sm="3">
                    <b-form-checkbox
                      id="checkbox-video"
                      v-model="intervenciontecnica.video"
                      name="checkbox-video"
                    >
                      ¿Video?
                    </b-form-checkbox>
                  </b-col>
                  <b-col sm="9">
                    <b-form-select v-model="intervenciontecnica.video_usuario_id"
                                   :class="{ 'is-invalid': $v.intervenciontecnica.video_usuario_id.$error }"
                                   :options="perito_usuarios" :reduce="camarografo_usuario => camarografo_usuario.id"
                                   text-field="nombre_completo" value-field="id"
                                   label="nombre_completo" :disabled="!intervenciontecnica.video" placeholder="Seleccione un camarógrafo"
                    />
                  </b-col>
                </b-row>
                <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.video_usuario_id.$error" align-h="end" align-v="center">
                  <b-col class="text-danger" sm="9">
                    El camarografo es requerido
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
          <expediente ref="componenteExpediente" :requerido="exp_requerido" :expediente="expediente"
                      @changeDatosExpediente="modificarDatosExpediente($event)"
          />
          <tabla-oficios v-if="oficios.length >= 1" ref="componenteOficios" :oficios="oficios" @changeOficios="modificarOficios($event)" />
          <tabla-indicios ref="componenteIndicios" :indicios="indicios" :tipos_indicios="tipos_indicios"
                          @changeIndiciosTomados="modificarIndicios($event)"
          />
          <tabla-informes-laboratorio ref="componenteInformesLaboratorio" :informes_laboratorio="informes_laboratorio" :tipos_informes_laboratorio="tipos_informes_laboratorio"
                                      @changeInformesLaboratorioTomados="modificarInformesLaboratorio($event)"/>
          <tabla-detenidos ref="componenteDetenidos" :detenidos="detenidos"
                          @changeDetenidosTomados="modificarDetenidos($event)"
          />
          <tabla-victimas ref="componenteVictimas" :victimas="victimas"
                          @changeVictimasTomados="modificarVictimas($event)" />
          <b-card class="text-center" header="Datos adicionales" header-bg-variant="primary" header-text-variant="secondary">
            <b-row align-v="center">
              <b-col sm="3">
                <label>Delito: </label>
              </b-col>
              <b-col sm="9">
                <b-form-select v-model="intervenciontecnica.delito_id"
                               :class="{ 'is-invalid': $v.intervenciontecnica.delito_id.$error }"
                               :options="tipos_delitos" :reduce="delito => delito.id"
                               text-field="nombre" value-field="id"
                               label="nombre" placeholder="Seleccione un delito"
                />
              </b-col>
            </b-row>
            <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.delito_id.$error" align-v="center" align-h="end">
              <b-col sm="9" class="text-danger">
                El delito es requerido
              </b-col>
            </b-row>
            <b-row v-if="intervenciontecnica.tipo_intervencion_tecnica_id === 2" align-v="center" style="margin-top: 20px;">
              <b-col sm="3">
                <label>Tipo Colision: </label>
              </b-col>
              <b-col sm="9">
                <b-form-select v-model="intervenciontecnica.tipo_colision_id"
                               :class="{ 'is-invalid': $v.intervenciontecnica.tipo_colision_id.$error }"
                               :options="tipo_colisiones" :reduce="tipo_colision => tipo_colision.id"
                               text-field="nombre" value-field="id"
                               label="nombre" placeholder="Seleccione un tipo de colision"
                />
              </b-col>
            </b-row>
            <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.tipo_colision_id.$error && intervenciontecnica.tipo_intervencion_tecnica_id === 2" align-v="center" align-h="end">
              <b-col sm="9" class="text-danger">
                El tipo de colision es requerido
              </b-col>
            </b-row>
            <b-row align-v="center" style="margin-top: 20px;">
              <b-col sm="3">
                <label>Descripción:</label>
              </b-col>
              <b-col sm="9">
                <b-form-textarea
                  id="textareaDescripcion"
                  v-model="intervenciontecnica.descripcion"
                  :class="{ 'is-invalid': $v.intervenciontecnica.descripcion.$error }"
                  placeholder="Ingrese descripción aquí"
                  rows="3"
                  max-rows="6"
                />
              </b-col>
            </b-row>
            <b-row v-if="$v.intervenciontecnica && $v.intervenciontecnica.descripcion.$error" align-v="center" align-h="end">
              <b-col sm="9" class="text-danger">
                <span v-if="!$v.intervenciontecnica.descripcion.required">La descripcion es requerida</span>
                <span v-else>La descripción puede tener como máximo {{ $v.intervenciontecnica.descripcion.$params.maxLength.max }} caracteres.</span>
              </b-col>
            </b-row>
            <div v-if="allowEdit && permissions.includes('editar_estado_servicios')">
              <br>
              <b-row align-v="center">
                <b-col sm="3">
                  <label>Estado: </label>
                </b-col>
                <b-col sm="9">
                  <b-form-select v-model="intervenciontecnica.estado_id" :options="estados" value-field="id"
                                 text-field="nombre"
                  >
                    <template #first>
                      <b-form-select-option :value="null">
                        Seleccione un Estado
                      </b-form-select-option>
                    </template>
                  </b-form-select>
                </b-col>
              </b-row>
              <b-row v-if="allowEdit && permissions.includes('editar_estado_servicios') && $v.intervenciontecnica && $v.intervenciontecnica.estado_id.$error" class="text-danger"
                     align-h="end"
              >
                <b-col sm="9">
                  El estado es requerido
                </b-col>
              </b-row>
            </div>
          </b-card>
        </b-container>
      </div>
      <template slot="modal-footer">
        <b-button type="submit" variant="primary" @click="handleOk">
          Guardar
        </b-button>
        <b-button type="button" @click="$bvModal.hide('intervencionTecnicaModal')">
          Cancelar
        </b-button>
      </template>
    </b-modal>
  </b-form>
</template>

<script>
import { minValue, maxValue, maxLength, numeric, required, requiredIf } from 'vuelidate/lib/validators'
import axios from 'axios'
import Loading from 'vue-loading-overlay'
import lugarModal from '../shared/lugarModal'
import expediente from '../shared/expediente.vue'
import tablaIndicios from '../shared/tablaIndicios.vue'
import tablaInformesLaboratorio from '../shared/tablaInformesLaboratorio.vue'
import tablaDetenidos from '../shared/tablaDetenidos.vue'
import tablaVictimas from '../shared/tablaVictimas.vue'
import tablaOficios from '../oficio/tablaOficios'
import 'vue-loading-overlay/dist/vue-loading.css'
import 'vue-select/dist/vue-select.css'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'

export default {
  // eslint-disable-next-line vue/component-definition-name-casing
  name: 'intervencionTecnicaModal',
  components: {
    expediente,
    lugarModal,
    tablaIndicios,
    tablaInformesLaboratorio,
    tablaOficios,
    tablaDetenidos,
    tablaVictimas,
    Loading
  },
  middleware: 'auth',
  props: {
    row: {
      type: Object,
      default: () => ({})
    },
    // eslint-disable-next-line vue/prop-name-casing
    tipo_intervenciones_tecnica: {
      type: Array,
      default: () => ([])
    },
    delitos: {
      type: Array,
      default: () => ([])
    },
    estados: {
      type: Array,
      default: () => ([])
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
    tipo_colisiones: {
      type: Array,
      default: () => ([])
    },
    // eslint-disable-next-line vue/prop-name-casing
    tipos_indicios: {
      type: Array,
      default: () => ([])
    },
    tipos_informes_laboratorio: {
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
    intervenciontecnica: {
      numero_de_registro: { required, numeric, minValue: minValue(1), maxValue: maxValue(99999999) },
      tipo_intervencion_tecnica_id: { required },
      delegacion_dependencia_id: { required },
      interviniento_dependencia_id: { required },
      tipo_colision_id: {
        required: requiredIf(function () {
          return this.intervenciontecnica.tipo_intervencion_tecnica_id === 2
        })
      },
      perito_usuario_id: { required },
      tecnico_revelador_usuario_id: {},
      secretario_usuario_id: {},
      planimetrista_usuario_id: {
        required: requiredIf(function () {
          return this.intervenciontecnica.plano
        })
      },
      fotografo_usuario_id: {
        required: requiredIf(function () {
          return this.intervenciontecnica.foto
        })
      },
      video_usuario_id: {
        required: requiredIf(function () {
          return this.intervenciontecnica.video
        })
      },
      delito_id: { required },
      lugar_id: {},
      plano: { required },
      foto: { required },
      video: { required },
      descripcion: {
        required,
        maxLength: maxLength(2000)
      },
      estado_id: { required }
    }
  },
  data () {
    return {
      tituloModal: 'Nueva Intervencion Técnica Criminalística de Campo',
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
      intervenciontecnica: {
        id: '',
        numero_de_registro: 1,
        fecha_hora: '',
        delegacion_dependencia_id: null,
        interviniento_dependencia_id: null,
        perito_usuario_id: null,
        tecnico_revelador_usuario_id: null,
        secretario_usuario_id: null,
        planimetrista_usuario_id: null,
        fotografo_usuario_id: null,
        video_usuario_id: null,
        tipo_colision_id: null,
        tipo_intervencion_tecnica_id: 1,
        delito_id: null,
        lugar_id: null,
        plano: false,
        foto: false,
        video: false,
        descripcion: '',
        estado_id: 1
      },
      oficios: [],
      indicios: [],
      detenidos: [],
      victimas: [],
      informes_laboratorio: [],
      expediente: {
        origen: 'MINISTERIO PUBLICO',
        numero_expediente: '',
        caratula: '',
        delito: '',
        dependencia: ''
      },
      tipos_delitos: []
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
      if (modalId === 'intervencionTecnicaModal') {
        this.cleanForm()
        if (this.row && this.row.id) {
          this.allowEdit = true
          this.tituloModal = 'Editar Intervencion Técnica Criminalística de Campo'
          this.intervenciontecnica.id = this.row.id
          this.intervenciontecnica.numero_de_registro = this.row.numero_de_registro
          this.setearHorayFecha(this.row.fecha_hora)
          this.lugar = this.row.lugar
          this.intervenciontecnica.lugar_id = this.row.lugar.id
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
          this.intervenciontecnica.delegacion_dependencia_id = this.row.delegacion_dependencia.id
          this.intervenciontecnica.interviniento_dependencia_id = this.row.interviniento_dependencia.id
          this.intervenciontecnica.perito_usuario_id = this.row.perito_usuario.id
          if (this.row.tecnico_revelador_usuario) {
            this.intervenciontecnica.tecnico_revelador_usuario_id = this.row.tecnico_revelador_usuario.id
          }
          if (this.row.secretario_usuario) {
            this.intervenciontecnica.secretario_usuario_id = this.row.secretario_usuario.id
          }
          if (this.row.foto) {
            this.intervenciontecnica.foto = true
            this.intervenciontecnica.fotografo_usuario_id = this.row.fotografo_usuario.id
          }
          if (this.row.plano) {
            this.intervenciontecnica.plano = true
            this.intervenciontecnica.planimetrista_usuario_id = this.row.planimetrista_usuario.id
          }
          if (this.row.video) {
            this.intervenciontecnica.video = true
            this.intervenciontecnica.video_usuario_id = this.row.video_usuario.id
          }
          if (this.row.indicios && this.row.indicios.length >= 1) {
            this.indicios = this.row.indicios
          } else {
            this.indicios = []
          }
          if (this.row.detenidos && this.row.detenidos.length >= 1) {
            this.detenidos = this.row.detenidos
          } else {
            this.detenidos = []
          }
          if (this.row.victimas && this.row.victimas.length >= 1) {
            this.victimas = this.row.victimas
          } else {
            this.victimas = []
          }
          if (this.row.informes_laboratorio && this.row.informes_laboratorio.length >= 1) {
            this.informes_laboratorio = this.row.informes_laboratorio
          } else {
            this.informes_laboratorio = []
          }
          if (this.row.oficios.length >= 1) {
            this.oficios = this.row.oficios
          } else {
            this.oficios = []
          }
          if (this.row.tipo_colision) {
            this.intervenciontecnica.tipo_colision_id = this.row.tipo_colision.id
          }
          this.intervenciontecnica.tipo_intervencion_tecnica_id = this.row.tipo_intervencion_tecnica.id
          this.intervenciontecnica.delito_id = this.row.delito.id
          this.cambiarDelitosporIDTipo()
          this.intervenciontecnica.descripcion = this.row.descripcion
          this.intervenciontecnica.estado_id = this.row.estado.id
        } else {
          this.allowEdit = false
          this.tituloModal = 'Nueva Intervencion Técnica Criminalística de Campo'
          this.cleanDatosExpediente()
          this.cleanDatosLugar()
          this.indicios = []
          this.detenidos = []
          this.victimas = []
          this.informes_laboratorio = []
          this.oficios = []
          if (this.intervenciontecnica.delegacion_dependencia_id === null) {
            this.intervenciontecnica.delegacion_dependencia_id = this.user.dependencia_id
          }
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
      this.intervenciontecnica.id = ''
      this.intervenciontecnica.numero_de_registro = 1
      this.intervenciontecnica.fecha_hora = ''
      this.intervenciontecnica.expediente_id = null
      this.intervenciontecnica.delegacion_dependencia_id = null
      this.intervenciontecnica.interviniento_dependencia_id = null
      this.intervenciontecnica.perito_usuario_id = null
      this.intervenciontecnica.tecnico_revelador_usuario_id = null
      this.intervenciontecnica.secretario_usuario_id = null
      this.intervenciontecnica.planimetrista_usuario_id = null
      this.intervenciontecnica.fotografo_usuario_id = null
      this.intervenciontecnica.video_usuario_id = null
      this.intervenciontecnica.tipo_colision_id = null
      this.intervenciontecnica.tipo_intervencion_tecnica_id = 1
      this.intervenciontecnica.delito_id = null
      this.intervenciontecnica.lugar_id = null
      this.intervenciontecnica.plano = false
      this.intervenciontecnica.foto = false
      this.intervenciontecnica.video = false
      this.intervenciontecnica.descripcion = ''
      this.intervenciontecnica.estado_id = 1
      this.tipos_delitos = this.tipo_intervenciones_tecnica[0].delitos
      this.$v.$reset()
    },
    cleanDatosExpediente () {
      this.intervenciontecnica.lugar_id = null
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
    cambiarDelitosporIDTipo () {
      const index = this.intervenciontecnica.tipo_intervencion_tecnica_id
      this.tipos_delitos = this.tipo_intervenciones_tecnica[index - 1].delitos
      const array = []
      this.tipos_delitos.forEach((delito) => {
        array.push(delito.id)
      })
      if (!array.includes(this.intervenciontecnica.delito_id)) {
        this.intervenciontecnica.delito_id = null
      }
    },
    setearHorayFecha (fechahora) {
      const result = fechahora.split(' ') // Se separa la fecha de la hora
      this.fecha_sola = result[0] // Se setea la fecha
      this.hora_sola = result[1] // Se setea la hora
      if (!this.permissions.includes('crear_intervencion_tecnica_viejo') && !this.permissions.includes('editar_intervencion_tecnica_viejo')) { // Si no tiene ninguno de estos permisos se setea el minimo
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
    modificarDatosExpediente (event) {
      this.expediente = {}
      this.expediente.origen = event.origen
      this.expediente.numero_expediente = event.numero_expediente
      this.expediente.caratula = event.caratula
      this.expediente.delito = event.delito
      this.expediente.dependencia = event.dependencia
    },
    modificarIndicios (event) {
      this.indicios = event.indicios
    },
    modificarDetenidos (event) {
      this.detenidos = event.detenidos
    },
    modificarVictimas (event) {
      this.victimas = event.victimas
    },
    modificarInformesLaboratorio (event) {
      this.informes_laboratorio = event.informes_laboratorio
    },
    modificarOficios (event) {
      this.mostrarSpinner(true)
      axios.get('api/oficio/searchBy/intervencion/' + this.intervenciontecnica.id)
        .then((result) => {
          this.oficios = result.data.data.slice()
          this.mostrarSpinner(false)
        })
    },
    handleSubmit () {
      this.$refs.componenteExpediente.$v.$touch()
      this.$refs.componenteIndicios.$v.$touch()
      this.$refs.componenteDetenidos.$v.$touch()
      this.$refs.componenteVictimas.$v.$touch()
      this.$refs.componenteInformesLaboratorio.$v.$touch()
      this.$v.$touch()
      if (this.$v.$invalid || this.$refs.componenteExpediente.$v.$invalid || this.$refs.componenteIndicios.$v.$invalid || this.$refs.componenteDetenidos.$v.$invalid || this.$refs.componenteVictimas.$v.$invalid || this.$refs.componenteInformesLaboratorio.$v.$invalid) {
        this.$bvToast.toast('Existen errores en el formulario', {
          title: 'Alerta',
          autoHideDelay: 5000
        })
      } else {
        this.mostrarSpinner(true)
        if (this.intervenciontecnica.id) {
          this.submitEditar()
        } else {
          this.submitCrear()
        }
      }
    },
    submitEditar () {
      this.intervenciontecnica.fecha_hora = this.fecha_hora
      axios.put('api/intervencion-tecnica/' + this.intervenciontecnica.id, {
        intervenciontecnica: this.intervenciontecnica,
        lugar: this.lugar,
        expediente: this.expediente,
        indicios: this.indicios,
        detenidos: this.detenidos,
        victimas: this.victimas,
        informes_laboratorio: this.informes_laboratorio
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han editado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-intervenciont')
          this.$bvModal.hide('intervencionTecnicaModal')
        })
      }).catch(error => {
        this.mostrarSpinner(false)
        this.mostrarErrores(error.response.data.errors)
      })
    },
    submitCrear () {
      this.intervenciontecnica.fecha_hora = this.fecha_hora
      axios.post('api/intervencion-tecnica', {
        intervenciontecnica: this.intervenciontecnica,
        lugar: this.lugar,
        expediente: this.expediente,
        indicios: this.indicios,
        detenidos: this.detenidos,
        victimas: this.victimas,
        informes_laboratorio: this.informes_laboratorio
      }).then(response => {
        this.loaded = true
        this.success = true
        this.$bvToast.toast('Los datos se han guardado correctamente')
        this.cleanForm()
        this.$nextTick(() => {
          this.mostrarSpinner(false)
          this.$emit('cambios-array-intervenciont')
          this.$bvModal.hide('intervencionTecnicaModal')
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
