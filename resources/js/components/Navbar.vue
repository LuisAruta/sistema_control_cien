<template>
  <b-navbar toggleable="lg" type="dark" variant="primary">
    <b-navbar-brand :to="{name: 'home'}">
      <img :src="logo" class="d-inline-block align-top"
           style="max-height: 55px;"
      >
    </b-navbar-brand>

    <b-navbar-toggle target="navbarToggler">
      <span class="navbar-toggler-icon" />
    </b-navbar-toggle>

    <b-collapse id="navbarToggler" is-nav>
      <b-navbar-nav v-if="user">
        <b-nav-item
          v-if="permissions.includes('crear_intervencion_tecnica') || permissions.includes('editar_intervencion_tecnica') || permissions.includes('consultar_intervencion_tecnica') || permissions.includes('eliminar_intervencion_tecnica')"
          :active="$route.name ==&quot;intervenciontecnica&quot;" :to="{ name: 'intervenciontecnica' }"
        >
          ITCC
        </b-nav-item>
        <b-nav-item
          v-if="permissions.includes('crear_necro') || permissions.includes('editar_necro') || permissions.includes('consultar_necro') || permissions.includes('eliminar_necro')"
          :active="$route.name ==&quot;necro&quot;"
          :to="{ name: 'necro' }"
        >
          Necro
        </b-nav-item>
        <b-nav-item
          v-if="permissions.includes('crear_traslado') || permissions.includes('editar_traslado') || permissions.includes('consultar_traslado') || permissions.includes('eliminar_traslado')"
          :active="$route.name ==&quot;traslado&quot;" :to="{ name: 'traslado' }"
        >
          Traslado
        </b-nav-item>
        <b-nav-item
          v-if="permissions.includes('crear_sin_efecto') || permissions.includes('editar_sin_efecto') || permissions.includes('consultar_sin_efecto') || permissions.includes('eliminar_sin_efecto')"
          :active="$route.name ==&quot;sinefecto&quot;" :to="{ name: 'sinefecto' }"
        >
          Sin Efecto
        </b-nav-item>
        <b-nav-item
          v-if="permissions.includes('crear_usuario') || permissions.includes('editar_usuario') || permissions.includes('consultar_usuario') || permissions.includes('eliminar_usuario')"
          :active="$route.name ==&quot;usuarios&quot;" :to="{ name: 'usuarios' }"
        >
          Usuarios
        </b-nav-item>
        <b-nav-item
          v-if="permissions.includes('crear_dependencia') || permissions.includes('editar_dependencia') || permissions.includes('consultar_dependencia') || permissions.includes('eliminar_dependencia')"
          :active="$route.name ==&quot;dependencias&quot;" :to="{ name: 'dependencias' }"
        >
          Dependencias
        </b-nav-item>
      </b-navbar-nav>
      <b-navbar-nav v-if="user" class="ml-auto" style=" padding: 10px 15px;">
        <!-- Authenticated -->
        <b-nav-item-dropdown v-if="user" :text="user.name" right>
          <b-dropdown-item :to="{ name: 'settings.profile' }">
            <b-icon-gear-fill />
            Perfil
          </b-dropdown-item>
          <b-dropdown-item href="#" @click.prevent="logout">
            <b-icon-power />
            Salir
          </b-dropdown-item>
        </b-nav-item-dropdown>
      </b-navbar-nav>

      <b-navbar-nav v-if="!user" class="ml-auto">
        <b-nav-item :active="$route.name ==&quot;login&quot;" :to="{ name: 'login' }">
          Iniciar sesión
        </b-nav-item>
      </b-navbar-nav>
    </b-collapse>
  </b-navbar>
</template>

<script>
import { mapGetters } from 'vuex'

export default {

  data: () => ({
    appName: window.config.appName,
    active: false,
    logo: '../images/cientifica_logo_horizontal.png'
  }),

  computed: mapGetters({
    user: 'auth/user',
    permissions: 'auth/permissions'
  }),

  methods: {
    async logout () {
      // Log out the user.
      await this.$store.dispatch('auth/logout')

      // Redirect to login.
      this.$router.push({ name: 'login' })
    }
  }
}
</script>

<style scoped>
.navbar{
  padding: 0;
}

.navbar

.nav-item.nav-item.nav-item a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
  border-bottom: 3px solid transparent;
}

.nav-item.nav-item.nav-item a:hover {
  color: #0047b3;
  border-bottom: 3px solid #0047b3;
}

.nav-item.nav-item.nav-item a.active {
  color: #0047b3;
  border-bottom: 3px solid #0047b3;
}
</style>
