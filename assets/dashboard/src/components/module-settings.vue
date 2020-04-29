<template>
    <div>
    <form @submit.prevent="submitModuleData">
        <div class="form-group" v-for="(controlSettings, controlName) in moduleData" v-if="controlSettings.type !== 'notice_content_valid'">
            <label v-if="controlSettings.label" :for="controlName">{{controlSettings.label}}</label>
            <input v-if="controlSettings.type !== 'notice_content_valid'" :name="controlName" :id="controlName" :type="controlSettings.type" v-model="fields[controlName]" />
        </div>
        <button v-if="dislayRefresh && notEmptySettings()" type="submit" class="module-refresh button"><i class="dashicons dashicons-update"></i></button>
        <button type="submit" class="button-primary button">Save</button>
    </form>
    <div class="module-info" v-for="(controlSettings, controlName) in moduleData" v-if="controlSettings.type === 'notice_content_valid' && notEmptySettings() " v-html="controlSettings.content"></div>
    </div>
</template>

<script>
    /* jshint esversion: 6 */

    export default {
      name: 'module-settings',
      data () {
        return {
          fields: Object.assign({}, this.$store.state.options.modules_options[this.moduleSlug]),
          displayRefresh: [
            'typekit_fonts'
          ]
        }
      },
      computed: {
        getFieldVal () {
          return this.$store.state.options.modules_options[this.moduleSlug]
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
      methods: {
        submitModuleData () {
          this.$store.dispatch('saveModuleSettings', this)
        },
        dislayRefresh () {
          for (let i = 0; i < this.displayRefresh; i++) {
            if (this.displayRefresh[i] === this.moduleSlug) {
              return true
            }
          }
          return false
        },
        fieldIsEmpty (controlName) {
          return this.$store.state.options.modules_options[this.moduleSlug][controlName] === ''
        },

        notEmptySettings () {
          let target = this.$store.state.options.modules_options[this.moduleSlug]
          for (var k in target) {
            if (target.hasOwnProperty(k) && target[k] !== '') {
              return true
            }
          }
          return false
        }
      }
    }
</script>