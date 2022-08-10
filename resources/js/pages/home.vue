<template>
  <b-container fluid style="margin-top: 20px;">
    <b-row align-h="center">
      <b-col xl="3" md="6">
        <stats-card type="gradient-green"
                    sub-title="Menos de 24Hrs"
                    icon="check-circle"
                    class="mb-4"
        >
          <template slot="footer">
            <b-row align-h="center">
              <span v-for="(item,index) in informacionAMostrar" :key="index">
                <b-col>
                  <span v-if="item.numero_24 !== 0">
                    <span class="font-weight-bold">{{ item.nombre }}: </span>
                    <b-link :to="{name: item.ruta, params: { fecha_a_buscar: 1 }}"> Hay {{ item.numero_24 }} registros.</b-link>
                  </span>
                  <span v-else>
                    <span class="font-weight-bold" style="min-width: 3rem;">{{ item.nombre }}:</span>
                    No hay registros.
                  </span>
                </b-col>
              </span>
            </b-row>
          </template>
        </stats-card>
      </b-col>
      <b-col xl="3" md="6">
        <stats-card type="gradient-yellow"
                    sub-title="Entre 24 a 72Hrs"
                    icon="bell"
                    class="mb-4"
        >
          <template slot="footer">
            <b-row align-h="center">
              <span v-for="(item,index) in informacionAMostrar" :key="index">
                <b-col>
                  <span v-if="item.numero_48 !== 0">
                    <span class="font-weight-bold">{{ item.nombre }}: </span>
                    <b-link :to="{name: item.ruta, params: { fecha_a_buscar: 2 }}"> Hay {{ item.numero_48 }} registros.</b-link>
                  </span>
                  <span v-else>
                    <span class="font-weight-bold">{{ item.nombre }}: </span>
                    No hay registros.
                  </span>
                </b-col>
              </span>
            </b-row>
          </template>
        </stats-card>
      </b-col>
      <b-col xl="3" md="6">
        <stats-card type="gradient-red"
                    sub-title="Más de 72Hrs"
                    icon="exclamation-circle"
                    class="mb-4"
        >
          <template slot="footer">
            <b-row align-h="center">
              <span v-for="(item,index) in informacionAMostrar" :key="index">
                <b-col>
                  <span v-if="item.numero_72 !== 0">
                    <span class="font-weight-bold">{{ item.nombre }}: </span>
                    <b-link :to="{name: item.ruta, params: { fecha_a_buscar: 3 }}"> Hay {{ item.numero_72 }} registros.</b-link>
                  </span>
                  <span v-else>
                    <span class="font-weight-bold">{{ item.nombre }}: </span>
                    No hay registros.
                  </span>
                </b-col>
              </span>
            </b-row>
          </template>
        </stats-card>
      </b-col>
    </b-row>
    <b-container v-if="permissions.includes('ver_estadisticas')" class="w-100">
      <b-row align-h="center">
        <h3><b><u>Estadisticas {{ anioSeleccionado }}</u></b></h3>
      </b-row>
      <b-row style="margin-bottom: 10px;">
        <b-col v-if="permissions.includes('ver_estadisticas_para_todas_las_dependencias')" style="text-align: start;">
          <label>Delegación:</label>
          <b-form-select v-model="delegacionSeleccionada" style="width: 200px;" :options="delegaciones" size="sm" value-field="id" text-field="nombre" @change="calcularEstadoYEstadisticas" >
            <template #first>
              <b-form-select-option :value="null">Todas las delegaciones</b-form-select-option>
            </template>
          </b-form-select>
        </b-col>
        <b-col style="text-align: end;">
          <label>Año:</label>
          <b-form-select v-model="anioSeleccionado" style="width: 100px;" :options="opcionesAnio" size="sm" @change="calcularEstadisticas" />
          <b-button
            class="mb-0"
            size="sm"
            style="margin-left: 20px; margin-bottom: 10px;"
            type="button"
            variant="info"
            @click="cambiarNombreBotonVerGraficos"
          >
            {{ nombreBotonVerGraficos }}
          </b-button>
        </b-col>
      </b-row>
    </b-container>
    <b-container v-if="permissions.includes('ver_estadisticas') && !verGraficos" class="w-100">
      <b-row align-h="center">
        <b-col>
          <b-table-simple small style="text-align: center;">
            <b-thead head-variant="dark">
              <b-tr>
                <b-th colspan="2">
                  Informes Técnicos {{this.anioSeleccionado}}
                </b-th>
              </b-tr>
              <b-tr>
                <b-th>Nombre </b-th>
                <b-th>Cantidad </b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-th>ITCC</b-th>
                <b-th>{{ this.valorAnio[0] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>I-SV</b-th>
                <b-th>{{ this.valorAnio[1] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>I-OH</b-th>
                <b-th>{{ this.valorAnio[2] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>Necros</b-th>
                <b-th>{{ this.valorAnio[3] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>Traslados</b-th>
                <b-th>{{ this.valorAnio[4] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>Sin Efecto</b-th>
                <b-th>{{ this.valorAnio[5] }}</b-th>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-col>
        <b-col>
          <b-table-simple small style="text-align: center;">
            <b-thead head-variant="dark">
              <b-tr>
                <b-th colspan="3">
                  Total Informes Pendientes
                </b-th>
              </b-tr>
              <b-tr>
                <b-th>Nombre </b-th>
                <b-th>Cantidad </b-th>
                <b-th>Fecha más antiguo</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr>
                <b-th>SV</b-th>
                <b-th>{{ this.cantidadInformesPendientes[0] }}</b-th>
                <b-th>{{ this.fechaInformesPendientes[0] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>OH</b-th>
                <b-th>{{ this.cantidadInformesPendientes[1] }}</b-th>
                <b-th>{{ this.fechaInformesPendientes[1] }}</b-th>
              </b-tr>
              <b-tr>
                <b-th>Necro</b-th>
                <b-th>{{ this.cantidadInformesPendientes[2] }}</b-th>
                <b-th>{{ this.fechaInformesPendientes[2] }}</b-th>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
      <b-row align-h="center">
        <b-col>
          <b-table-simple small style="text-align: center;">
            <b-thead head-variant="dark">
              <b-tr>
                <b-th colspan="2">
                  Informes por Tipo de Delito
                </b-th>
              </b-tr>
              <b-tr>
                <b-th>Nombre </b-th>
                <b-th>Cantidad </b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="datos in this.datosTablaInformesDelito" :key="datos.index">
                <b-th>{{ datos.nombre }}</b-th>
                <b-th>{{ datos.count }}</b-th>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-col>
        <b-col>
          <b-table-simple small style="text-align: center;">
            <b-thead head-variant="dark">
              <b-tr>
                <b-th colspan="3">
                  Cantidad de Informes por Peritos
                </b-th>
              </b-tr>
              <b-tr>
                <b-th>Nombre Perito</b-th>
                <b-th>Pendientes</b-th>
                <b-th>Realizados</b-th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr v-for="datos in this.datosTablaInformesPerito" :key="datos.index">
                <b-th>{{ datos.nombre_perito }}</b-th>
                <b-th>{{ datos.pendientes }}</b-th>
                <b-th>{{ datos.realizados }}</b-th>
              </b-tr>
            </b-tbody>
          </b-table-simple>
        </b-col>
      </b-row>
    </b-container>
    <b-container v-if="permissions.includes('ver_estadisticas') && verGraficos" class="w-100">
      <b-row align-h="center">
        <b-col v-if="comprobarCantidadInformesPendientes(valorAnio)">
          <graph-pie
            :width="500"
            :height="500"
            :padding-top="100"
            :padding-bottom="100"
            :values="valorAnio"
            :names="nombresAnio"
            :active-index="[ 0, 2 ]"
            :active-event="'click'"
            :show-text-type="'outside'"
            :data-format="dataFormat"
          >
            <note :text="'Informes Técnicos'" />
            <legends :names="nombresAnio" :filter="true" />
            <tooltip :names="nombresAnio" />
          </graph-pie>
        </b-col>
        <b-col v-if="cantidadInformesPendientes && comprobarCantidadInformesPendientes(cantidadInformesPendientes)">
          <graph-pie
            :width="500"
            :height="500"
            :padding-top="100"
            :padding-bottom="100"
            :values="cantidadInformesPendientes"
            :names="nombresInformesPendientes"
            :active-index="[ 0, 2 ]"
            :active-event="'click'"
            :show-text-type="'outside'"
            :data-format="dataFormat"
          >
            <note text="Total Informes Pendientes" />
            <legends :names="nombresInformesPendientes" />
            <tooltip :names="fechaInformesPendientesGrafico" />
          </graph-pie>
        </b-col>
      </b-row>
      <b-row align-h="center">
        <b-col v-if="comprobarCantidadInformesPendientes(cantidadInformesDelito)">
          <graph-pie
            :width="500"
            :height="500"
            :padding-top="100"
            :padding-bottom="100"
            :values="cantidadInformesDelito"
            :names="nombresInformesDelito"
            :active-index="[ 0, 2 ]"
            :active-event="'click'"
            :show-text-type="'outside'"
            :data-format="dataFormat"
          >
            <note text="Informes por Tipo de Delito" />
            <legends :names="nombresInformesDelito" :filter="true" />
            <tooltip :names="nombresInformesDelito" />
          </graph-pie>
        </b-col>
      </b-row>
      <b-row align-h="center">
        <b-col v-if="false && comprobarCantidadInformesPendientes(nombresInformesPerito)">
          <graph-pie
            :width="500"
            :height="500"
            :padding-top="100"
            :padding-bottom="100"
            :values="cantidadInformesPeritoPendientes"
            :names="nombresInformesPerito"
            :active-index="[ 0, 2 ]"
            :active-event="'click'"
            :show-text-type="'outside'"
            :data-format="dataFormat"
          >
            <note text="Informes Pendientes por Perito" />
            <legends :names="nombresInformesPerito" :filter="true" />
            <tooltip :names="nombresInformesPerito" />
          </graph-pie>
        </b-col>
        <b-col v-if="false && comprobarCantidadInformesPendientes(nombresInformesPerito)">
          <graph-pie
            :width="500"
            :height="500"
            :padding-top="100"
            :padding-bottom="100"
            :values="cantidadInformesPeritoRealizados"
            :names="nombresInformesPerito"
            :active-index="[ 0, 2 ]"
            :active-event="'click'"
            :show-text-type="'outside'"
            :data-format="dataFormat"
          >
            <note text="Informes Realizados por Perito" />
            <legends :names="nombresInformesPerito" :filter="true" />
            <tooltip :names="nombresInformesPerito" />
          </graph-pie>
        </b-col>
      </b-row>
    </b-container>
  </b-container>
</template>

<script>
import { mapGetters } from 'vuex'
import axios from 'axios'

export default {
  middleware: 'auth',
  metaInfo () {
    return { title: this.$t('home') }
  },
  data () {
    return {
      informacionAMostrar: [],
      verGraficos: false,
      nombreBotonVerGraficos: 'Ver Graficos',
      anioSeleccionado: null,
      delegacionSeleccionada: 0,
      opcionesAnio: [],
      nombresAnio: ['ITCC', 'Siniestros Viales', 'Otros Hechos', 'Necro', 'Traslado', 'Sin Efecto'],
      valorAnio: [],
      nombresInformesPendientes: ['Siniestros Viales', 'Otros Hechos', 'Necro'],
      cantidadInformesPendientes: [],
      fechaInformesPendientes: [],
      fechaInformesPendientesGrafico: [],
      nombresInformesDelito: [],
      cantidadInformesDelito: [],
      datosTablaInformesDelito: [],
      nombresInformesPerito: [],
      cantidadInformesPeritoPendientes: [],
      cantidadInformesPeritoRealizados: [],
      nombresEstadoInformes: ['Pendientes', 'Realizados'],
      datosTablaInformesPerito: [],
      delegaciones: []
    }
  },
  computed: mapGetters({
    user: 'auth/user',
    permissions: 'auth/permissions'
  }),
  mounted () {
    this.opcionesAnio = []
    this.delegaciones = []
    if (this.permissions.includes('ver_estadisticas_para_todas_las_dependencias')){
      this.delegacionSeleccionada = null
    } else {
      this.delegacionSeleccionada = this.user.dependencia_id
    }
    this.getDelegaciones()
    this.calcularOpcionesAnio()
    this.calcularEstadoActual()
    if (this.permissions.includes('ver_estadisticas')) {
      const today = new Date()
      this.anioSeleccionado = today.getFullYear()
      this.calcularEstadisticas()
    }
  },
  methods: {
    dataFormat: function (a, b) {
      if (b) return b
      return a
    },
    calcularOpcionesAnio () {
      let anio = new Date().getFullYear()
      do {
        this.opcionesAnio.push(anio)
        anio--
      } while (anio > 2020)
      this.opcionesAnio = this.opcionesAnio.sort()
    },
    limpiarEstadisticas () {
      this.cantidadInformesPendientes = []
      this.fechaInformesPendientes = []
      this.fechaInformesGrafico = []
      this.nombresInformesDelito = []
      this.cantidadInformesDelito = []
      this.datosTablaInformesDelito = []
      this.nombresInformesPerito = []
      this.cantidadInformesPeritoPendientes = []
      this.cantidadInformesPeritoRealizados = []
      this.datosTablaInformesPerito = []
      this.valorAnio = []
    },
    formatFecha (value, grafico) {
      if (value) {
        const fecha = value.split(' ')[0].split('-')
        if (grafico) {
          return 'Más Antiguo: ' + fecha[2] + '-' + fecha[1] + '-' + fecha[0]
        } else {
          return fecha[2] + '-' + fecha[1] + '-' + fecha[0]
        }
      } else {
        return '-'
      }
    },
    formatCantidad: value => {
      if (value) {
        return value
      } else {
        return 0
      }
    },
    cambiarNombreBotonVerGraficos () {
      this.verGraficos ? this.nombreBotonVerGraficos = 'Ver Graficos' : this.nombreBotonVerGraficos = 'Ver Tablas'
      this.verGraficos = !this.verGraficos
    },
    onlyUnique (value, index, self) {
      return self.indexOf(value) === index
    },
    calcularEstadoYEstadisticas () {
      this.calcularEstadoActual()
      this.calcularEstadisticas()
    },
    calcularEstadoActual () {
      axios.get('api/estadoActual/' + this.delegacionSeleccionada).then((result) => {
        this.informacionAMostrar = result.data.datos
      })
    },
    calcularEstadisticas () {
      this.limpiarEstadisticas()
      axios.get('api/estadisticas/' + this.anioSeleccionado + '/' + this.delegacionSeleccionada).then((result) => {
        const estadisticasInformesPorAnio = result.data.datos.estadisticas_informes_anio[0]
        const estadisticasInformesPendientes = result.data.datos.estadisticas_informes_pendientes[0]
        const estadisticasInformesPorDelito = result.data.datos.estadisticas_informes_por_delito
        const estadisticasInformesPorPerito = result.data.datos.estadisticas_informes_por_perito
        this.valorAnio.push(estadisticasInformesPorAnio.cantidad_itcc, estadisticasInformesPorAnio.cantidad_sv, estadisticasInformesPorAnio.cantidad_oh, estadisticasInformesPorAnio.cantidad_necro, estadisticasInformesPorAnio.cantidad_traslado, estadisticasInformesPorAnio.cantidad_sin_efecto)
        this.cantidadInformesPendientes.push(this.formatCantidad(estadisticasInformesPendientes.cantidad_sv), this.formatCantidad(estadisticasInformesPendientes.cantidad_oh), this.formatCantidad(estadisticasInformesPendientes.cantidad_necro))
        this.fechaInformesPendientes.push(this.formatFecha(estadisticasInformesPendientes.fecha_sv_viejo, false), this.formatFecha(estadisticasInformesPendientes.fecha_oh_viejo, false), this.formatFecha(estadisticasInformesPendientes.fecha_necro_viejo, false))
        this.fechaInformesPendientesGrafico.push(this.formatFecha(estadisticasInformesPendientes.fecha_sv_viejo, true), this.formatFecha(estadisticasInformesPendientes.fecha_oh_viejo, true), this.formatFecha(estadisticasInformesPendientes.fecha_necro_viejo, true))
        this.datosTablaInformesDelito = estadisticasInformesPorDelito
        this.nombresInformesDelito = estadisticasInformesPorDelito.map(i => { return i.nombre })
        this.cantidadInformesDelito = estadisticasInformesPorDelito.map(i => { return i.count })
        this.nombresInformesPerito = estadisticasInformesPorPerito.map(i => { return i.nombre_perito })
        this.cantidadInformesPeritoPendientes = estadisticasInformesPorPerito.map(i => { return i.pendientes })
        this.cantidadInformesPeritoRealizados = estadisticasInformesPorPerito.map(i => { return i.realizados })
        this.datosTablaInformesPerito = estadisticasInformesPorPerito
      })
    },
    comprobarCantidadInformesPendientes (array) {
      let resultado = false
      array.forEach(element => {
        if (element) {
          resultado = true
        }
      })
      return resultado
    },
    getDelegaciones () {
      axios.get('/api/delegaciones').then((result) => {
        this.delegaciones = result.data.data.slice()
      })
    }
  }
}
</script>
