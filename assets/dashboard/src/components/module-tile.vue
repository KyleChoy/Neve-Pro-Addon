<template>
	<div class="tile" :class="[{'unavailable' : !isModuleAvailable}, moduleSlug ]">
		<div class="tile__header">
			<h4>{{moduleData.nicename}}</h4>
			<div class="toggle" v-if="!moduleData.required_actions && isModuleAvailable">
				<toggle-button
						@change="handleToggle( $event )"
						:value="!disabled"
						:sync="true"
						color="#008ec2"
						:disabled="this.$store.state.loading"
				></toggle-button>
			</div>
			<span v-if="moduleData.required_actions || !isModuleAvailable" class="label" :class="inactive">{{ statusString }}</span>
		</div>
		<div class="tile__content">
			<div class="upsell" v-if="!isModuleAvailable">
				<a :href="upgradeLinks[this.moduleData.availabilityLevel]" class="button-primary button button-hero">{{license
					> 0 ? strings.upgrade : strings.purchase }}</a>
			</div>
			<div class="info">
				<p>{{moduleData.description}}</p>
			</div>
			<div v-if="moduleHaveSettings && !disabled && !moduleData.required_actions" class="settings">
				<module-settings :module-data="moduleData.settingsForm" :module-slug="moduleSlug"></module-settings>
			</div>
		</div>
		<footer class="tile__footer" v-if=" moduleData.links || moduleData.documentation  || moduleData.required_actions">
			<p v-if="moduleData.required_actions && isModuleAvailable " class="required-actions" v-html="moduleData.required_actions" ></p>
			<a v-if="( moduleData.documentation && !moduleData.required_actions ) || !isModuleAvailable" target="_blank" class="doc-link button button-link" :href="moduleData.documentation.url" >{{moduleData.documentation.label}}</a>
			<ul v-if="( moduleData.links && !moduleData.required_actions && isModuleAvailable && !disabled )" >
				<li v-for="(link, index) in moduleData.links">
					<a class="button button-link" :href="link.url">{{link.label}}</a>
				</li>
			</ul>
		</footer>
	</div>
</template>

<script>
  /* global neveProData */
  /* jshint esversion: 6 */

  import ModuleSettings from './module-settings.vue'

  export default {
    name: 'module-tile',
    data () {
      return {
        strings: neveProData.strings,
        upgradeLinks: neveProData.upgradeLinks,
        license: neveProData.license
      }
    },
    computed: {
      disabled () {
        return this.$store.state.options.modules_status[this.moduleSlug] === 'disabled'
      },
      isLoading () {
        return this.$store.state.loading
      },
      statusString () {
        if (this.moduleData.required_actions && this.isModuleAvailable) {
          return this.strings.actions
        }
        return this.strings.unavailable
      },
      isModuleAvailable () {
        return neveProData.license >= this.moduleData.availabilityLevel
      },
      moduleHaveSettings () {
        return this.moduleData.settingsForm.length !== 0
      }
    },
    methods: {
      handleToggle ($event) {
        this.$store.commit('toggleModule', this.moduleSlug)
        this.$store.dispatch('saveOptions', this)
      },
      resetModule () {
        this.$store.commit('toggleModule', this.moduleSlug)
      }
    },
    props: {
      moduleData: {
        type: Object,
        required: true,
        default: {}
      },
      moduleSlug: {
        type: String,
        required: true,
        default: null
      }
    },
    components: {
      ModuleSettings
    }
  }
</script>
