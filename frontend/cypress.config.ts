import { defineConfig } from "cypress";
import PluginEvents = Cypress.PluginEvents;
import PluginConfigOptions = Cypress.PluginConfigOptions;

export default defineConfig({
  e2e: {
      env: {
          apiUrl: "http://localhost:8000/api",
      },
      baseUrl: "http://localhost:3000",
      setupNodeEvents(_on: PluginEvents, _config: PluginConfigOptions) {},
  },
    component: {
        devServer: {
            framework: "next",
            bundler: "webpack",
        },
    },

    pageLoadTimeout: 100000,
});
