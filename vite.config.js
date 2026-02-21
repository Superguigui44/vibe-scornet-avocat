import { defineConfig } from 'vite'
import { resolve } from 'path'

export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'index.html'),
        cabinet: resolve(__dirname, 'cabinet.html'),
        competences: resolve(__dirname, 'competences.html'),
        contact: resolve(__dirname, 'contact.html'),
      },
    },
  },
})
