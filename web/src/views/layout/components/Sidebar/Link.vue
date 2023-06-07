<template>
  <!-- eslint-disable vue/require-component-is-->
  <component v-bind="linkProps(to)" :is="Object">
    <slot/>
  </component>
</template>

<script>
import { validateURL } from '@/utils/validate'
export default {
  props: {
    to: {
      type: String,
      required: true
    },
    query: {
      type: Object,
      default: function() {
        return {}
      },
      required: false
    }
  },
  methods: {
    isExternalLink(routePath) {
      return validateURL(routePath)
    },
    linkProps(url) {
      if (this.isExternalLink(url)) {
        return {
          is: 'a',
          href: url,
          target: '_blank',
          rel: 'noopener'
        }
      }
      return {
        is: 'router-link',
        to: {
          path: url,
          query: this.query
        }
      }
    }
  }
}
</script>
