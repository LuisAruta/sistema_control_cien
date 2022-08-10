<template>
  <b-container fluid style="padding-left: 0px !important; padding-right: 0px !important;">
    <intervencion-tecnica-modal :row="selectedRow" :tipo_intervenciones_tecnica="tipo_intervenciones_tecnica"
                                :delitos="delitos" :estados="estados"
                                :departamentos="departamentos"
                                :delegaciones="delegaciones"
                                :intervinientes="intervinientes"
                                :perito_usuarios="perito_usuarios"
                                :tipo_colisiones="tipo_colisiones"
                                :tipos_indicios="tipos_indicios"
                                :tipos_informes_laboratorio="tipos_informes_laboratorio"
                                :estados_oficios="estados_oficios"
                                @cambios-array-intervenciont="getData()"
    />
    <detalle-modal :info-modal="infoModal" @close="resetInfoModal" />
    <oficio-modal :row="selectedRow"
                  :solicitantes="intervinientes"
                  :estados="estados"
                  :estados_oficios="estados_oficios"
                  @cambios-oficios="getData()" />
    <b-card style="background-color: transparent;">
      <b-row>
        <b-col class="my-1" lg="5">
          <b-form-group
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                id="filter-input"
                v-model="campoBusqueda"
                placeholder="Escriba para buscar"
                type="search"
                @keyup.enter="getData"
              />
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col class="my-1" lg="5">
          <b-button
            class="mb-0"
            size="sm"
            style="margin-bottom: 10px;"
            type="button"
            variant="info"
            @click="getData"
          >
            Buscar
          </b-button>
          <b-button v-b-toggle.collapse-filtros size="sm" variant="primary">
            Busqueda Avanzada
          </b-button>
        </b-col>
        <b-col lg="2" style="text-align: end;">
          <b-button
            v-if="permissions.includes('crear_intervencion_tecnica')"
            size="sm"
            type="button"
            variant="primary"
            @click="abrirModal(null)"
          >
            Nuevo ITCC
          </b-button>
        </b-col>
      </b-row>
      <b-collapse id="collapse-filtros" class="mt-2" size="sm">
        <b-card
          border-variant="primary"
          header="Parametros de busqueda"
          header-bg-variant="primary"
          header-text-variant="white"
          align="center"
        >
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Fecha: </label>
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_desde"
                                   placeholder="Desde"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_hasta"
                                   placeholder="Hasta"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
              <b-col sm="2">
                <label size="sm">Expediente: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.expediente" placeholder="Ingrese Expediente" size="sm" />
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Delito: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.delito" size="sm" :options="delitos" value-field="id"
                               text-field="nombre"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione un Delito
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
              <b-col sm="2">
                <label size="sm">Departamento: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.departamento" size="sm" :options="departamentos" text-field="DEPTO"
                               value-field="DEPTO"
                >
                    <template #first>
                      <b-form-select-option :value="null">
                        Seleccione un Departamento
                      </b-form-select-option>
                    </template>
                </b-form-select>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Delegacion: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.delegacion" size="sm" :options="delegaciones" value-field="id"
                               text-field="nombre" :disabled="!permissions.includes('consultar_para_todas_las_dependencias')"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione una Delegacion
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
              <b-col sm="2">
                <label size="sm">Interviniente: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.interviniente" size="sm" :options="intervinientes" value-field="id"
                               text-field="nombre"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione un Interviniente
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Estado: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.estado" size="sm" :options="estados" value-field="id"
                               text-field="nombre"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione un Estado
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
              <b-col sm="2">
                <label size="sm">Tipo ITCC: </label>
              </b-col>
              <b-col sm="4">
                <b-form-select v-model="filtros.tipo_intervencion" size="sm" :options="tipo_intervenciones_tecnica" value-field="id"
                               text-field="nombre"
                >
                  <template #first>
                    <b-form-select-option :value="null">
                      Seleccione un Tipo
                    </b-form-select-option>
                  </template>
                </b-form-select>
              </b-col>
            </b-row>
          </b-card-text>
          <template #footer>
            <b-button size="sm" variant="danger" @click="limpiarFiltros">
              Limpiar
            </b-button>
            <b-button
              v-b-toggle.collapse-filtros
              class="mb-0"
              size="sm"
              style="margin-bottom: 10px;"
              type="button"
              variant="primary"
              @click="getData()"
            >
              Buscar
            </b-button>
          </template>
        </b-card>
      </b-collapse>
    </b-card>
    <b-table id="itcctabla"
             responsive="sm"
             :fields="fields"
             :per-page="perPage"
             :current-page="currentPage"
             :items="items" :total-rows="rows" head-variant="dark" primary-key="id"
             :tbody-tr-class="rowClass"
    >
      <template #cell(acciones)="row">
        <b-button v-if="permissions.includes('consultar_intervencion_tecnica')" v-b-tooltip.hover="'Ver Detalles'" size="sm"
                  variant="info" @click="abrirModalDetalle(row.item)"
        >
          <b-icon aria-hidden="true" icon="search" />
        </b-button>
        <b-button
          v-if="permissions.includes('editar_intervencion_tecnica_viejo') || (row.item.editable && permissions.includes('editar_intervencion_tecnica'))"
          v-b-tooltip.hover="'Editar'"
          size="sm"
          type="button"
          variant="primary"
          data-toggle="modal"
          @click="abrirModal(row.item)"
        >
          <b-icon aria-hidden="true" icon="pencil" />
        </b-button>
        <b-button v-b-tooltip.hover="'Oficios'" size="sm"
                  variant="success" @click="abrirModalOficio(row.item)"
        >
          <b-icon aria-hidden="true" icon="plus-circle" />
        </b-button>
        <b-button
          v-if="permissions.includes('eliminar_intervencion_tecnica')"
          v-b-tooltip.hover="'Borrar'"
          size="sm"
          variant="danger"
          @click="eliminarRow($event, row.item)"
        >
          <b-icon aria-hidden="true" icon="trash" />
        </b-button>
      </template>
    </b-table>
    <b-pagination
      v-model="currentPage"
      :total-rows="rows"
      :per-page="10"
      aria-controls="itcctabla"
      @change="getData($event)"
    />
  </b-container>
</template>

<script>

import axios from 'axios'
import intervencionTecnicaModal from './intervencionTecnicaModal'
import detalleModal from '../shared/detalleModal'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import oficioModal from '../oficio/oficioModal'

export default {
  name: 'IntervencionTecnicaVue',
  components: { oficioModal, intervencionTecnicaModal, detalleModal },
  middleware: 'auth',
  data () {
    return {
      isLoading: false,
      url: 'api/intervencion-tecnica/search',
      total: 0,
      modal: intervencionTecnicaModal,
      fields: [
        {
          key: 'numero_de_registro',
          label: 'N°Reg',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'fecha_hora',
          label: 'Fecha',
          sortable: true,
          class: 'text-center',
          formatter: value => {
            const fecha = value.split(' ')[0].split('-')
            return fecha[2] + '-' + fecha[1] + '-' + fecha[0]
          }
        }, {
          key: 'tipo_intervencion_tecnica.sigla',
          label: 'Tipo',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'expediente.numero_expediente',
          label: 'Expediente',
          sortable: true,
          class: 'text-center',
          formatter: value => {
            if (value) {
              return value
            } else {
              return 'Sin Número'
            }
          }
        }, {
          key: 'delito.nombre',
          label: 'Delito',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'lugar',
          label: 'Departamento / Localidad',
          sortable: true,
          class: 'text-center',
          formatter: (value) => {
            return value.departamento + ' / ' + this.comprobarCampoTexto(value.localidad)
          }
        }, {
          key: 'estado.nombre',
          label: 'Estado',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'acciones',
          label: 'Acciones',
          sortable: false,
          class: 'text-center'
        }
      ],
      items: [],
      selectedRow: {
        id: '',
        fecha_hora: '',
        descripcion: '',
        plano: false,
        foto: false,
        video: false,
        lugar: {
          barrio: '',
          calle: '',
          departamento: '',
          id: '',
          latitud: '',
          longitud: '',
          numero: '',
          numero_departamento: '',
          created_at: '',
          updated_at: ''
        },
        expediente: {
          id: '',
          caratula: '',
          numero_expediente: '',
          created_at: '',
          updated_at: ''
        },
        delegacion_dependencia: {
          id: ''
        },
        interviniento_dependencia: {
          id: ''
        },
        perito_usuario: {
          id: ''
        },
        tecnico_revelador_usuario: {
          id: ''
        },
        planimetrista_usuario: {
          id: ''
        },
        fotografo_usuario: {
          id: ''
        },
        video_usuario: {
          id: ''
        },
        indicios: [],
        informes_laboratorio: [],
        estado: ''
      },
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      campoBusqueda: null,
      filtros: {
        fecha_desde: null,
        fecha_hasta: null,
        expediente: null,
        delito: null,
        departamento: null,
        delegacion: null,
        interviniente: null,
        tipo_intervencion: null,
        estado: null
      },
      infoModal: {
        title: '',
        content: ''
      },
      perito_usuarios: [],
      tipo_intervenciones_tecnica: [],
      delitos: [],
      departamentos: [],
      delegaciones: [],
      intervinientes: [],
      tipo_colisiones: [],
      tipos_indicios: [],
      tipos_informes_laboratorio: [],
      estados: [],
      estados_oficios: []
    }
  },
  computed: {
    rows () {
      return this.total
    },
    sortOptions () {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key }
        })
    },
    ...mapGetters({
      permissions: 'auth/permissions',
      user: 'auth/user'
    })
  },
  created () {
    if (JSON.stringify(this.$route.params) !== '{}') {
      this.getData(1, this.$route.params.fecha_a_buscar)
    } else {
      this.getData()
    }
    this.getFiltros()
    this.getFiltrosAdicionales()
  },
  methods: {
    rowClass (item, type) {
      if (!item || type !== 'row') return
      if (item.cantidad_oficios_pendientes > 0) return 'table-danger'
    },
    abrirModalOficio (data) {
      this.selectedRow = data
      this.selectedRow.tipo = 'IntervencionTecnica'
      this.$bvModal.show('oficioModal')
    },
    abrirModalDetalle (item) {
      this.infoModal.title = 'Detalles ITCC'
      this.infoModal.content = item
      const result = item.fecha_hora.split(' ')
      this.infoModal.content.fecha_sola = result[0]
      this.infoModal.content.hora_sola = result[1]
      this.$bvModal.show('detalleModal')
    },
    resetInfoModal () {
      this.infoModal.title = ''
      this.infoModal.content = ''
    },
    comprobarCampoTexto (data) {
      if (data) {
        return data
      } else {
        return '-'
      }
    },
    abrirModal (data) {
      if (data) {
        this.selectedRow = {}
        this.selectedRow = data
      } else {
        this.selectedRow = {}
      }
      this.$bvModal.show(this.modal.name)
    },
    limpiarFiltros () {
      this.filtros.fecha_desde = null
      this.filtros.fecha_hasta = null
      this.filtros.expediente = null
      this.filtros.delito = null
      this.filtros.departamento = null
      this.filtros.delegacion = null
      this.filtros.interviniente = null
      this.filtros.tipo_intervencion = null
      this.filtros.estado = null
    },
    getData (event = 1, entrefechas = null) {
      const page = typeof event === 'number' ? event : 1
      this.mostrarSpinner(true)
      const url = this.url + '?page=' + page
      let data = null
      if (!this.permissions.includes('consultar_para_todas_las_dependencias')) {
        this.filtros.delegacion = this.user.dependencia_id
      }
      data = { filter: this.campoBusqueda, filtros: this.filtros, entre_fechas: entrefechas }
      axios.post(url, data)
        .then(response => {
          this.items = response.data.data
          this.perPage = response.data.per_page
          this.currentPage = response.data.meta.current_page
          this.total = response.data.meta.total
          this.mostrarSpinner(false)
        }).catch(error => {
          this.mostrarSpinner(false)
          this.mostrarErrores(error.response.data.errors)
          this.items = []
        })
    },
    eliminarRow (event, row) {
      Swal.fire({
        title: '¿Desea eliminar la Intervención Técnica?',
        text: 'Eliminar un ITCC es permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.mostrarSpinner(true)
          axios.delete('api/intervencion-tecnica/' + row.id)
            .then(response => {
              this.$bvToast.toast('El ITCC fue borrado correctamente', {
                title: 'Exito',
                autoHideDelay: 3000
              })
              this.getData()
              this.mostrarSpinner(false)
            }).catch(error => {
              this.mostrarSpinner(false)
              this.mostrarErrores(error.response.data.errors)
            })
        }
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
    },
    onFiltered (filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    getFiltrosAdicionales () {
      this.mostrarSpinner(true)
      axios.get('/api/filtrosITCC').then((result) => {
        this.tipo_colisiones = result.data.filtros.tipo_colisiones.slice()
        this.tipo_intervenciones_tecnica = result.data.filtros.tiposITCC.slice()
        this.delitos = result.data.filtros.delitos.slice()
        this.tipos_indicios = result.data.filtros.tipos_indicios.slice()
        this.tipos_informes_laboratorio = result.data.filtros.tipos_informes_laboratorio.slice()
        this.mostrarSpinner(false)
      })
    },
    getFiltros () {
      this.mostrarSpinner(true)
      axios.get('/api/filtros/' + this.user.dependencia_id).then((result) => {
        this.perito_usuarios = result.data.filtros.perito_usuarios.slice()
        this.departamentos = result.data.filtros.departamentos.data.slice()
        this.delegaciones = result.data.filtros.delegaciones.slice()
        if (!this.permissions.includes('crear_para_todas_las_dependencias')) {
          this.delegaciones = this.delegaciones.filter(delegacion => delegacion.id === this.user.dependencia_id)
        }
        this.intervinientes = result.data.filtros.intervinientes.slice()
        this.estados_oficios = result.data.filtros.estados_oficios.slice()
        this.estados = result.data.filtros.estados.slice()
        this.mostrarSpinner(false)
      })
    }
  }
}
</script>

<style scoped>

</style>
