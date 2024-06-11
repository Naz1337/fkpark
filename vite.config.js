// vite.config.js
import { defineConfig } from 'vite';
import { liveReload } from "vite-plugin-live-reload";

const baseUrl = '/CB22159/fkpark/'

export default defineConfig(({command, mode }) => {

  let baseSetting = '';
  if (command === 'build') {
    baseSetting = baseUrl + 'build/'
  }

  return {
    plugins:[
      liveReload(['**/*.php'])
    ],
    base: baseSetting,
    server: {
      origin: "http://localhost:5173"
    },
    build: {
      manifest: true,
      outDir: 'build',
      rollupOptions: {
        input: {
          main: './js/main.js',
          user_dashboard: './js/user_dashboard.js',
          admin_parking_dashboard: './js/admin_parking_dashboard.js',
          admin_parking_status_dashboard: './js/admin_parking_status_dashboard.js',
        },
      },
    },
  }
});
