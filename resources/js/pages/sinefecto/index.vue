<template>
  <b-container fluid style="padding-left: 0px !important; padding-right: 0px !important;">
    <sin-efecto-modal :row="selectedRow"
                      :departamentos="departamentos"
                      :delegaciones="delegaciones"
                      :estados_oficios="estados_oficios"
                      @cambios-array-sinefecto="getData()" />
    <detalle-modal :info-modal="infoModal" @close="resetInfoModal" />
    <oficio-modal :row="selectedRow"
                  :solicitantes="intervinientes"
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
            v-if="permissions.includes('crear_sin_efecto')"
            size="sm"
            type="button"
            variant="primary"
            @click="abrirModal(null)"
          >
            Nuevo Sin Efecto
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
                <label size="sm">Fecha Solicitud: </label>
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_desde_sol"
                                   placeholder="Desde"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_hasta_sol"
                                   placeholder="Hasta"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
              <b-col sm="2">
                <label size="sm">Fecha Registro: </label>
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_desde_reg"
                                   placeholder="Desde"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
              <b-col sm="2">
                <b-form-datepicker v-model="filtros.fecha_hasta_reg"
                                   placeholder="Hasta"
                                   :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }"
                                   size="sm"
                                   locale="es"
                />
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
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
              <b-col sm="2">
                <label size="sm">Usuario: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.nombre_usuario" placeholder="Ingrese Nombre" size="sm"/>
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
                <label size="sm">Funcionario: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.datos_funcionario" placeholder="Ingrese Datos Funcionario" size="sm"/>
              </b-col>
            </b-row>
          </b-card-text>
          <b-card-text>
            <b-row align-v="center">
              <b-col sm="2">
                <label size="sm">Descripción: </label>
              </b-col>
              <b-col sm="4">
                <b-form-input v-model="filtros.descripcion" placeholder="Ingrese Descripcion" size="sm"/>
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
    <b-table id="sinefectotabla"
             responsive="sm"
             :fields="fields"
             :per-page="perPage"
             :current-page="currentPage"
             :items="items" :total-rows="rows" head-variant="dark" primary-key="id"
             :tbody-tr-class="rowClass"
    >
      <template #cell(acciones)="row">
        <b-button v-if="permissions.includes('consultar_sin_efecto')" v-b-tooltip.hover="'Ver Detalles'" size="sm"
                  variant="info" @click="abrirModalDetalle(row.item)"
        >
          <b-icon aria-hidden="true" icon="search" />
        </b-button>
        <b-button
          v-if="permissions.includes('editar_sin_efecto_viejo') || (row.item.editable && permissions.includes('editar_sin_efecto'))"
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
          v-if="permissions.includes('eliminar_sin_efecto')"
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
      aria-controls="sinefectotabla"
      @change="getData($event)"
    />
  </b-container>
</template>

<script>
import axios from 'axios'
import sinEfectoModal from './sinEfectoModal'
import detalleModal from '../shared/detalleModal'
import { mapGetters } from 'vuex'
import Swal from 'sweetalert2'
import oficioModal from '../oficio/oficioModal'

export default {
  name: 'SinEfectoVue',
  components: { oficioModal, sinEfectoModal, detalleModal },
  middleware: 'auth',
  data () {
    return {
      isLoading: false,
      url: 'api/sin-efecto/search',
      total: 0,
      modal: sinEfectoModal,
      fields: [
        {
          key: 'numero_de_registro',
          label: 'N°Reg',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'fecha_hora_solicitud',
          label: 'Fecha Solicitud',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'fecha_hora_registro',
          label: 'Fecha Registro',
          sortable: true,
          class: 'text-center'
        }, {
          key: 'descripcion',
          label: 'Descripcion',
          sortable: true,
          class: 'text-center',
          formatter: value => {
            if (value.length >= 19) {
              const trimmedString = value.substring(0, 20)
              return trimmedString + '...'
            } else {
              return value
            }
          }
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
        fecha_hora_registro: '',
        fecha_hora_solicitud: '',
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
        delegacion_dependencia: {
          id: ''
        },
        funcionario: '',
        descripcion: ''
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
        fecha_desde_sol: null,
        fecha_hasta_sol: null,
        fecha_desde_reg: null,
        fecha_hasta_reg: null,
        departamento: null,
        nombre_usuario: null,
        delegacion: null,
        datos_funcionario: null,
        descripcion: null
      },
      infoModal: {
        title: '',
        content: ''
      },
      departamentos: [],
      delegaciones: [],
      intervinientes: [],
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
    this.getData()
    this.getFiltros()
  },
  methods: {
    rowClass (item, type) {
      if (!item || type !== 'row') return
      if (item.cantidad_oficios_pendientes > 0) return 'table-danger'
    },
    abrirModalOficio (data) {
      this.selectedRow = data
      this.selectedRow.tipo = 'SinEfecto'
      this.$bvModal.show('oficioModal')
    },
    abrirModalDetalle (item) {
      this.infoModal.title = 'Detalles Sin Efecto'
      this.infoModal.content = item
      this.infoModal.content.fecha_hora_registro = item.fecha_hora_registro
      this.infoModal.content.fecha_hora_solicitud = item.fecha_hora_solicitud
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
      this.filtros.fecha_desde_sol = null
      this.filtros.fecha_hasta_sol = null
      this.filtros.fecha_desde_reg = null
      this.filtros.fecha_hasta_reg = null
      this.filtros.departamento = null
      this.filtros.nombre_usuario = null
      this.filtros.delegacion = null
      this.filtros.datos_funcionario = null
      this.filtros.descripcion = null
    },
    getData () {
      const page = typeof event === 'number' ? event : 1
      this.mostrarSpinner(true)
      const url = this.url + '?page=' + page
      let data = null
      if (!this.permissions.includes('consultar_para_todas_las_dependencias')) {
        this.filtros.delegacion = this.user.dependencia_id
      }
      data = { filter: this.campoBusqueda, filtros: this.filtros }
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
        title: '¿Desea eliminar el Sin Efecto?',
        text: 'Eliminar un Sin Efecto es permanente.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#F5365C',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.value) {
          this.mostrarSpinner(true)
          axios.delete('api/sin-efecto/' + row.id)
            .then(response => {
              this.$bvToast.toast('El Sin Efecto fue borrado correctamente', {
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
    getFiltros () {
      this.mostrarSpinner(true)
      axios.get('/api/filtros' + this.user.dependencia_id).then((result) => {
        this.departamentos = result.data.filtros.departamentos.data.slice()
        this.delegaciones = result.data.filtros.delegaciones.slice()
        if (!this.permissions.includes('crear_para_todas_las_dependencias')) {
          this.delegaciones = this.delegaciones.filter(delegacion => delegacion.id === this.user.dependencia_id)
        }
        this.intervinientes = result.data.filtros.intervinientes.slice()
        this.estados_oficios = result.data.filtros.estados_oficios.slice()
        this.mostrarSpinner(false)
      })
    }
  }
}
</script>

<style scoped>

</style>
