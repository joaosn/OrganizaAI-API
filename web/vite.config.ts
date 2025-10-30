import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import path from 'path'

export default defineConfig({
  plugins: [react()],
  root: '.',
  base: '/', // Base absoluta a partir da raiz do domínio
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  optimizeDeps: {
    exclude: ['lucide-react'],
  },
  
  server: {
    port: 3002,
    host: true,
    cors: {
      origin: '*',
      credentials: true
    },
    watch: {
      ignored: ['../api/**'],
    }
  },
  preview: {
    port: 3002,
    host: true
  }
})
