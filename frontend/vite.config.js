import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// Configuración de Vite para React. El puerto 5173 debe coincidir con
// FRONTEND_URL del backend (CORS).
export default defineConfig({
  plugins: [react()],
  server: {
    port: 5173,
    host: '127.0.0.1',
  },
  test: {
    globals: true,
    environment: 'jsdom',
    setupFiles: './src/test/setup.js',
  },
});
