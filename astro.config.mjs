// @ts-check
import { defineConfig } from 'astro/config';

import tailwindcss from '@tailwindcss/vite';

import vercel from '@astrojs/vercel';

import sitemap from '@astrojs/sitemap';

// https://astro.build/config
export default defineConfig({
  vite: {
    plugins: [tailwindcss()]
  },

  // server: {
  //   host:true,
  //   port:3000
  // },

  adapter: vercel(),

  site: 'https://idiucv.vercel.app/',
  integrations: [sitemap()]
});