<template>
	<header class="dash__header">
		<div class="titles">
			<h3 class="title">{{strings.title}}</h3>
			<p>{{strings.subtitle}}</p>
		</div>
		<toast v-if="this.$store.state.toast.message"></toast>
	</header>
</template>

<script>
  /* global neveProData */
  /* jshint esversion: 6 */
  import Toast from './toast.vue'

  export default {
    name: 'app-header',
    data () {
      return {
        strings: neveProData.strings
      }
    },
    components: {
      Toast
    }
  }
</script>