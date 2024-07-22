new Vue({
  el: '#p4w-updater',
  data() {
    return {
      companyName: 'Plugins for WP',
      companyEmail: 'support@pluginsforwp.com',
      myProductsRoute: '/wp-json/pluginsforwp/v1/my-products',
      detailsImage: 'https://pluginsforwp.com/wp-content/uploads/2020/07/new-tab-icon.png',
      purchaseUrl: 'https://pluginsforwp.com/checkout?edd_action=add_to_cart&download_id=DOWNLOAD_ID',
      unlimitedDownloadsId: 3591,
      pluginizer: false,

      activeTab: 'plugins',
      isBusy: false,
      isBusyMessage: null,
      settings: {
        key: null,
        username: null,
        coupon: null,
        affiliate: null,
      },
      products: [],
      pluginCnt: 0,
      themeCnt: 0,
      modals: {
        isInstallModalActive: false,
        isInstallOk: false,
        isInstallError: false,
        details: false,
        youtube: false,
      },
      product: {
        name: null,
        type: null,
      },
      user: {
        plugins: null,
        themes: null,
        serverUrl: null,
        hasAllAccess: false,
        hasUltimateItems: false,
        allAccessUrl: null,
        allAccessImage: null,
        validCredentials: false,
      },
      search: {
        plugins: {
          text: '',
        },
        themes: {
          text: '',
        },
      },
      filter: {
        plugins: 'all',
        themes: 'all',
      },
    };
  },

  mounted() {
    if (this.companyName === 'Pluginizer') {
      this.pluginizer = true;
    }

    this.getInstalledProducts();
  },

  computed: {
    plugins() {
      var plugins = [];
      for (var product of this.products) {
        if (product.type === 'plugin' &&
            product.version &&
            this.canShowProduct(product, this.filter.plugins, this.search.plugins.text)) {
          plugins.push(product);
        }
      }

      return plugins;
    },

    themes() {
      var themes = [];
      for (var product of this.products) {
        if (product.type === 'theme' &&
            product.version &&
            this.canShowProduct(product, this.filter.plugins, this.search.plugins.text)) {
          themes.push(product);
        }
      }

      return themes;
    },
  },

  methods: {
    setUsername(text) {
      this.settings.username = text.target.value;
    },

    setKey(text) {
      this.settings.key = text.target.value;
    },

    setAffiliate(text) {
      this.settings.affiliate = text.target.value;
    },

    getFilteredPluginCount() {
      let cnt = 0;
      for (let product of this.products) {
        if (product.type === 'plugin' &&
            product.name.toLowerCase().includes(this.search.plugins.text.toLowerCase())) {
          cnt++;
        }
      }

      return cnt;
    },

    getFilteredThemeCount() {
      let cnt = 0;
      for (let product of this.products) {
        if (product.type === 'theme' &&
            product.name.toLowerCase().includes(this.search.themes.text.toLowerCase())
        ) {
          cnt++;
        }
      }

      return cnt;
    },

    getBaseUrl() {
      let baseurl = window.location.origin + window.location.pathname;

      return baseurl.replace('/wp-admin/admin.php', '') + '/?rest_route=/p4w/v1';
    },

    getInstalledProducts() {
      this.isBusy = true;

      axios.get(this.getBaseUrl() + '/products/list').then(response => {
        this.settings.username = response.data.username || null;
        this.settings.key = response.data.key || null;
        this.settings.affiliate = response.data.affiliate || null;

        this.user = {
          plugins: response.data.plugins,
          themes: response.data.themes,
          serverUrl: response.data.serverUrl,
        };

        this.isBusy = false;

        this.checkUpdates();
      });
    },

    /**
     * Install or update a product
     *
     * @param product
     */
    install(product) {
      this.product = product;
      this.modals.isInstallModalActive = true;
    },

    doInstall(product) {
      this.modals.isInstallModalActive = false;
      this.isBusy = true;
      this.isBusyMessage = 'Installing Product ...';

      axios.post(this.getBaseUrl() + '/products/install', product).then(() => {
        this.modals.isInstallOk = true;
        this.isBusyMessage = null;

        this.getInstalledProducts();
      }).catch(() => {
        this.modals.isInstallError = true;
      });
    },

    /**
     * Update settings (Secret key)
     */
    submitSettings() {
      axios.post(this.getBaseUrl() + '/settings/save', this.settings).then(() => {
        this.checkUpdates();
        this.activeTab = 'plugins';
      });
    },

    /**
     * Clear products
     */
    reset() {
      this.products = [];
      this.pluginCnt = 0;
      this.themeCnt = 0;
    },

    /**
     * Check for updated products by getting a list and comparing it with what's installed locally
     */
    checkUpdates() {
      this.reset();

      let request = {
        method: 'GET',
        url: this.user.serverUrl + this.myProductsRoute,
        headers: {
          'Content-type': 'application/json',
        },
      };

      this.user.validCredentials = false;
      if (this.settings.username && this.settings.key) {
        request.headers.Authorization = 'Basic ' + window.btoa(this.settings.username + ':' + this.settings.key);
      }

      this.isBusy = true;
      axios(request).then(response => {
        this.user.allAccessUrl = response.data.allAccessUrl;
        this.user.allAccessImage = response.data.allAccessImage;
        this.user.hasAllAccess = response.data.hasAllAccess;
        this.user.hasUltimateItems = response.data.hasUltimateItems;

        if (this.settings.username && this.settings.key) {
          this.user.validCredentials = true;
        }

        let userPlugins = Object.values(this.user.plugins);
        let userThemes = Object.values(this.user.themes);

        this.products = response.data.products;
        for (let product of this.products) {
          product.installedVersion = null;

          for (let plugin of userPlugins) {
            if (product.name === plugin.Name && product.type === 'plugin') {
              product.installedVersion = plugin.Version;
            }
          }

          for (let theme of userThemes) {
            if (product.name === theme.Name && product.type === 'theme') {
              product.installedVersion = theme.Version;
            }
          }

          if (product.type === 'plugin') {
            this.pluginCnt++;
          } else if (product.type === 'theme') {
            this.themeCnt++;
          }
        }

        this.isBusy = false;
      });
    },

    postError() {
      this.modals.isInstallError = false;
      this.checkUpdates();
    },

    /**
     * Filter the product by the radio buttons and search text
     * @param product
     * @param filter
     * @param text
     * @returns {boolean}
     */
    canShowProduct(product, filter, text) {
      if (filter === 'installed' && !product.installedVersion) {
        // filter out installed products
        return false;
      }

      if (filter === 'purchased' && !product.purchased) {
        // filter out not purchased products
        return false;
      }

      if (filter === 'installed_and_purchased' && (!product.installedVersion && !product.purchased)) {
        return false;
      }

      if (!text || (text && product.name.toLowerCase().includes(text.toLowerCase()))) {
        // filter by searchbox
        return true;
      }

      return false;
    },

    /**
     * Open an iframe to the main store site for product details and purchase
     * @param product
     */
    openDetailsModal(product) {
      this.modals.details = true;
      this.product = product;
    },

    /**
     * Replace the placeholders in the purchase URL with the download and pricing IDs given
     * @param url
     * @param downloadId
     * @returns {*}
     */
    replaceIds(url, downloadId) {
      return url.replace('DOWNLOAD_ID', downloadId);
    },

    /**
     * Add the coupon code to the purchase URL, if any
     * @returns {*}
     * @param downloadId
     * @param priceId
     */
    getPurchaseUrl(downloadId, priceId) {
      if (this.pluginizer) {
        let url = 'https://pluginizer.com/checkout?edd_action=add_to_cart&download_id=DOWNLOAD_ID';
        switch (priceId) {
          case -1:
            return this.replaceIds(url + '&discount=10off', 17483);
          case -2:
            return this.replaceIds(url + '&discount=50off', 18046);
          case -3:
            return this.replaceIds(url, 18049);
        }

        return 'https://pluginizer.com/pricing/';
      }

      let url = this.purchaseUrl;
      let newURL = url.split('?');

      switch (priceId) {
        case -1:
          downloadId = 484672;
          break;
        case -2:
          downloadId = 484675;
          break;
        case -3:
          downloadId = 484678;
          break;
      }

      // Regular products with coupon
      if (priceId > 0) {
        if (this.settings.coupon) {
          if (this.settings.affiliate) {
            return this.replaceIds(newURL[0] + '/?ref=' + this.settings.affiliate + '&' + newURL[1] + '&discount=' + this.settings.coupon, downloadId);
          } else {
            return this.replaceIds(url + '&discount=' + this.settings.coupon, downloadId);
          }
        }

        return this.replaceIds(url, downloadId);
      }

      // Unlimited downloads (hardcoded coupon)
      if (this.settings.affiliate) {
        return this.replaceIds(newURL[0] + '/?ref=' + this.settings.affiliate + '&' + newURL[1] + '&discount=INTRO50', downloadId);
      }

      return this.replaceIds(url + '&discount=INTRO50', downloadId);
    },

    /**
     * Activates a WordPress plugin
     * @param product
     */
    activate(product) {
      this.modals.isInstallOk = false;
      this.isBusyMessage = 'Activating ...';

      axios.post(this.getBaseUrl() + '/products/activate', product).then(() => {
        window.location.reload();
      }).catch(() => {
        this.modals.isInstallError = true;
      });
    },

    /**
     * Compare two versions without throwing
     * @param a
     * @param b
     * @returns {number|*}
     */
    compareProductVersions(a, b) {
      try {
        return compareVersions(a, b);
      } catch (e) {
        return 0;
      }
    },
  },
});

Vue.filter('striphtml', function(value) {
  let div = document.createElement('div');
  div.innerHTML = value;

  return div.textContent || div.innerText || '';
});

// Add handler for close button on admin banner
jQuery(document).ready(function($) {
  $('#p4w-admin-banner .notice-dismiss').click(function() {
    axios.post('/?rest_route=/p4w/v1/settings/update-admin-banner-time').then(function() {});
  });
});
