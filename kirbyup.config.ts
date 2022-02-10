import { resolve } from 'path'
import { defineConfig } from 'kirbyup'

export default defineConfig({
  alias: {
    '@KirbyPanel/': `${resolve(__dirname, '../../../kirby/panel/src')}/`,
  },
  extendViteConfig: {
    logLevel: 'info',
  }
})
