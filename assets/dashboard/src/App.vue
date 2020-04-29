<template>
	<div class="neve-pro-dash-wrap">
		<app-header></app-header>
		<div class="tiles">
			<module-tile v-for="( module, slug ) in modules" :module-data="module" :key="slug"
					:module-slug="slug"></module-tile>
		</div>
	</div>
</template>

<script>
  /* global neveProData */
  /* jshint esversion: 6 */

  import ModuleTile from './components/module-tile.vue'
  import AppHeader from './components/app-header.vue'

  export default {
    name: 'app',
    components: {
      AppHeader,
      ModuleTile
    },
    data () {
      return {
        modules: neveProData.modules ? neveProData.modules : {}
      }
    }
  }
</script>

<style lang="scss">
	@import "./scss/style.scss";
</style>
