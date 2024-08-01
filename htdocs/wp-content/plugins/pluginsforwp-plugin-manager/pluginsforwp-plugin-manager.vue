<div id="p4w-updater" v-cloak>
<div class="tabs is-boxed p4w-updater-tabs">
  <ul>
    <li :class="{'is-active':activeTab === 'plugins'}" @click="activeTab = 'plugins'">
      <a>Plugins</a></li>
    <li :class="{'is-active':activeTab === 'themes'}" @click="activeTab = 'themes'">
      <a>Themes</a></li>
    <li :class="{'is-active':activeTab === 'unlimited'}" @click="activeTab = 'unlimited'" v-if="!user.hasAllAccess">
      <a>Unlimited Downloads</a></li>
    <li :class="{'is-active':activeTab === 'settings'}" @click="activeTab = 'settings'">
      <a>Settings</a>
    </li>
  </ul>
</div>

<div class="p4w-updater-container" v-cloak>


  <!-- Plugins Tab -->


  <div v-show="activeTab === 'plugins'" class="p4w-updater-tab-contents">
    <div v-if="!user.hasAllAccess && !isBusy">
      <a class="p4w-link-large" @click="activeTab = 'unlimited'">
        <img :src="user.allAccessImage" alt="Get Unlimited Downloads">
      </a>
    </div>

    <div v-show="isBusy" class="p4w-updater-loader">
      <span v-if="isBusyMessage">{{ isBusyMessage }}</span>
      <span v-if="!isBusyMessage">Loading your products ...</span>
      <progress class="progress is-small has-text-primary" :class="pluginizer ? 'is-warning' : 'is-danger'"
                max="100"></progress>
    </div>


    <div class="panel" v-show="user.validCredentials === false && !isBusy" v-cloak>
      <div class="notification is-danger">
        The username or key entered were not correct.
      </div>
    </div>

    <div v-show="!isBusy">
      <div class="panel-block" v-if="!user.hasAllAccess && !pluginizer">
        <div class="p4w-updater-form-settings flex-section" style="margin:auto;">
          <p style="margin: 0"><strong>Coupon Code:</strong></p>
          <input type="text" style="width:auto;margin: 0 10px;" class="input is-small" v-model="settings.coupon"
                 :placeholder="companyName + ' Coupon'">
          <p style="margin: 0">(Enter coupon code and click Purchase on your desired plugin/theme to see the new price)</p>
        </div>
      </div>

      <br>

      <div class="pntDashboard">
        <div class="panel-heading">
          <div class="columns is-mobile">
            <div class="column is-two-thirds">
              <div class="block">
                Show:
                <q-radio v-model="filter.plugins"
                         label="All"
                         val="all">
                </q-radio>
                <q-radio v-model="filter.plugins"
                         label="Purchased"
                         val="purchased">
                </q-radio>
                <q-radio v-model="filter.plugins"
                         label="Installed"
                         val="installed">
                </q-radio>
              </div>
            </div>
            <div class="column is-one-third">
              <input type="text" placeholder="Search plugins by name ..." v-model="search.plugins.text"
                     class="is-pulled-right">
            </div>
          </div>
        </div>

        <div class="panel p4w-product-list-container">
          <div class="panel" v-show="!settings.key && !isBusy" v-cloak
               v-if="filter.plugins === 'purchased'">
            <div class="notification is-danger">
              Can't find your purchased products? <a class="p4w-link-large" @click="activeTab = 'settings'">Click here
              to activate the plugin.</a>
            </div>
          </div>

          <div class="q-pa-lg flex flex-center">
            <q-pagination
                v-model="pagination.pluginPage"
                :max-pages="10"
                :max="getMaxPluginPages"
            />
          </div>

          <ul class="p4w-product-list" v-show="pluginCnt">
            <li v-for="plugin in plugins" :key="plugin.id" class="p4w-product-row">
              <div class="pfwpItemInner">
                <div class="pfwpItemDetails">
                  <span v-html="plugin.image"></span>

                  <span v-if="plugin.id">
                    <a class="p4w-link-large" @click="openDetailsModal(plugin)">{{ plugin.name }}</a>
                    (<a :href="plugin.serverUrl" target="_blank" rel="noopener noreferrer">
                    <img alt="Product Details" :src="detailsImage"></a>)
                  </span>
                  <span v-if="!plugin.id" class="p4w-link-large" style="text-decoration: none;">
                    {{ plugin.name }}
                  </span>

                  <div class="pfwpItemDesc" v-readmore:150="stripHTML(plugin.description)"></div>
                  <div class="itemVers">
                    <div class="pfwpItemVer">{{ companyName }} Version:
                      <span class="p4w-product-version">{{ plugin.version }}</span></div>
                    <div class="pfwpClientVer">
                      <span>Your Version: <strong>{{ plugin.installedVersion || 'Not installed' }}</strong></span>
                    </div>
                  </div>
                </div>

                <div class="pfwpCTABtns">
                  <span v-if="plugin.purchased && !plugin.installedVersion"><button
                      class="button is-success"
                      :class="pluginizer ? 'pz-button' : 'p4w-button'"
                      @click="install(plugin)">Install</button></span>
                  <span
                      v-if="plugin.purchased && plugin.installedVersion && plugin.version && compareProductVersions(plugin.installedVersion, plugin.version) === -1"><button
                      class="button is-success"
                      :class="pluginizer ? 'pz-button' : 'p4w-button'"
                      @click="install(plugin)">Update</button></span>
                  <span v-if="!plugin.purchased && plugin.id">
                    <a :href="getPurchaseUrl(plugin.id, 1)"
                       class="button is-success" target="_blank"
                       :class="pluginizer ? 'pz-button' : 'p4w-button'"
                       rel="noopener noreferrer">Purchase</a></span>

                  <span v-if="plugin.installed">
                    <a @click="deactivatePlugin(plugin)"
                       v-if="plugin.active"
                       class="button is-success" target="_blank"
                       :class="pluginizer ? 'pz-button' : 'p4w-button'"
                       rel="noopener noreferrer">Deactivate</a>
                    <a @click="activatePlugin(plugin)"
                       v-if="!plugin.active"
                       class="button is-success" target="_blank"
                       :class="pluginizer ? 'pz-button' : 'p4w-button'"
                       rel="noopener noreferrer">Activate</a>
                  </span>
                </div>
              </div>
            </li>
          </ul>

          <div class="notification" v-show="getFilteredPluginCount() === 0">
            There are no plugins matching your search.
          </div>

          <div class="notification" v-show="!pluginCnt">
            You have not purchased any plugins.
          </div>
        </div>

        <div class="q-pa-lg flex flex-center">
          <q-pagination
              v-model="pagination.pluginPage"
              :max-pages="10"
              :max="getMaxPluginPages"
          />
        </div>

        <div class="numberItemSum">
          <p>There are <strong>{{ pluginCnt }}</strong> plugins.</p>
        </div>
      </div>
    </div>
  </div>


  <!-- Themes Tab -->


  <div v-show="activeTab === 'themes'" class="p4w-updater-tab-contents">
    <div v-if="!user.hasAllAccess && !isBusy">
      <a class="p4w-link-large" @click="activeTab = 'unlimited'">
        <img :src="user.allAccessImage" alt="Get Unlimited Downloads">
      </a>
    </div>

    <div v-show="isBusy" class="p4w-updater-loader">
      <span v-if="isBusyMessage">{{ isBusyMessage }}</span>
      <span v-if="!isBusyMessage">Loading your products ...</span>
      <progress class="progress is-small has-text-primary" :class="pluginizer ? 'is-warning' : 'is-danger'"
                max="100"></progress>
    </div>

    <div class="panel" v-show="user.validCredentials === false && !isBusy" v-cloak>
      <div class="notification is-danger">
        The username or key entered were not correct.
      </div>
    </div>

    <div v-show="!isBusy">

      <div class="panel-block" v-if="!user.hasAllAccess">
        <div class="p4w-updater-form-settings flex-section" style="margin:auto;">
          <p style="margin: 0"><strong>Coupon Code:</strong></p>
          <input type="text" style="width:auto;margin: 0 10px;" class="input is-small" v-model="settings.coupon"
                 :placeholder="companyName + ' Coupon'">
          <p style="margin: 0">(Enter coupon code and click Purchase on your desired plugin/theme to see the new price)</p>
        </div>
      </div>

      <div class="pntDashboard">
        <div class="panel-heading">
          <div class="columns is-mobile">
            <div class="column is-two-thirds">
              <div class="block">
                Show:
                <q-radio v-model="filter.themes"
                         label="All"
                         val="all">
                </q-radio>
                <q-radio v-model="filter.themes"
                         label="Purchased"
                         val="purchased">
                </q-radio>
                <q-radio v-model="filter.themes"
                         label="Installed"
                         val="installed">
                </q-radio>
              </div>
            </div>
            <div class="column is-one-third">
              <input type="text" placeholder="Search themes by name ..." v-model="search.themes.text"
                     class="is-pulled-right">
            </div>
          </div>
        </div>

        <div class="panel p4w-product-list-container">
          <div class="panel" v-show="!settings.key && !isBusy" v-cloak
               v-if="filter.themes === 'purchased'">
            <div class="notification is-danger">
              Can't find your purchased products? <a class="p4w-link-large" @click="activeTab = 'settings'">Click here
              to activate the plugin.</a>
            </div>
          </div>

          <div class="q-pa-lg flex flex-center">
            <q-pagination
                v-model="pagination.themePage"
                :max-pages="10"
                :max="getMaxThemePages"
            />
          </div>

          <ul class="p4w-product-list" v-show="themeCnt">
            <li v-for="theme in themes" :key="theme.id"
                class="p4w-product-row">
              <div class="pfwpItemInner">
                <div class="pfwpItemDetails">
                  <span v-html="theme.image"></span>

                  <span v-if="theme.id">
                    <a class="p4w-link-large" @click="openDetailsModal(theme)">{{ theme.name }}</a>
                    (<a :href="theme.serverUrl" target="_blank" rel="noopener noreferrer">
                    <img alt="Product Details" :src="detailsImage"></a>)
                  </span>
                  <span v-if="!theme.id" class="p4w-link-large" style="text-decoration: none;">
                    {{ theme.name }}
                  </span>

                  <div class="pfwpItemDesc" v-readmore:150="stripHTML(theme.description)"></div>
                  <div class="itemVers">
                    <div class="pfwpItemVer">{{ companyName }} Version:
                      <span class="p4w-product-version">{{ theme.version }}</span></div>
                    <div class="pfwpClientVer">
                      <span>Your Version: <strong>{{ theme.installedVersion || 'Not installed' }}</strong></span>
                    </div>
                  </div>
                </div>

                <div class="pfwpCTABtns">
                  <span v-if="theme.purchased && !theme.installedVersion"><button
                      class="button has-text-white"
                      :class="pluginizer ? 'pz-button' : 'p4w-button'"
                      @click="install(theme)">Install</button></span>
                  <span
                      v-if="theme.purchased && theme.installedVersion && theme.version && compareProductVersions(theme.installedVersion, theme.version) === -1"><button
                      class="button has-text-white"
                      :class="pluginizer ? 'pz-button' : 'p4w-button'"
                      @click="install(theme)">Update</button></span>
                  <span v-if="!theme.purchased && theme.id">
                    <a :href="getPurchaseUrl(theme.id, 1)"
                       class="button is-success" target="_blank"
                       :class="pluginizer ? 'pz-button' : 'p4w-button'"
                       rel="noopener noreferrer">Purchase</a></span>

                  <span v-if="theme.installed">
                    <a @click="activateTheme(theme)"
                       v-if="!theme.active"
                       class="button is-success" target="_blank"
                       :class="pluginizer ? 'pz-button' : 'p4w-button'"
                       rel="noopener noreferrer">Activate</a>
                  </span>
                </div>
              </div>
            </li>
          </ul>

          <div class="notification" v-show="getFilteredThemeCount() === 0">
            There are no themes matching your search.
          </div>

          <div class="notification" v-show="!themeCnt">
            You have not purchased any themes.
          </div>
        </div>

        <div class="q-pa-lg flex flex-center">
          <q-pagination
              v-model="pagination.themePage"
              :max-pages="10"
              :max="getMaxThemePages"
          />
        </div>

        <div class="numberItemSum">
          <p>There are <strong>{{ themeCnt }}</strong> themes.</p>
        </div>
      </div>
    </div>
  </div>


  <!-- Unlimited Downloads Tab -->


  <div v-show="activeTab === 'unlimited'" class="p4w-updater-tab-contents UDTab">
    <div v-if="!user.hasAllAccess && !isBusy">
      <a class="p4w-link-large" @click="activeTab = 'unlimited'">
        <img :src="user.allAccessImage" alt="Get Unlimited Downloads">
      </a>
    </div>

    <div class="panel">
      <div class="notification is-danger" v-show="!settings.key && !isBusy" v-cloak>
        Please enter your {{ companyName }} Username and Key to install your plugins.
      </div>

      <div class="panel" v-show="user.validCredentials === false && !isBusy" v-cloak>
        <div class="notification is-danger">
          The username or key entered were not correct.
        </div>
      </div>

      <div class="panel-block">
        <div class="p4w-updater-form-settings flex-section">
          <p style="margin: 0"><strong>Coupon Code:</strong></p>
          <input type="text" style="width:auto;margin: auto 10px;" class="input is-small" v-model="settings.coupon"
                 :placeholder="companyName + ' Coupon'">
          <p style="margin: 0">(Enter coupon code and click on your desired plan to see the new price)</p>
        </div>
      </div>

      <p class="panel-heading">Unlimited Downloads</p>
      <div class="panel-block">
        <div v-if="!pluginizer">
          <div class="flex-section is-centered">
            <div class="column">
              <a :href="getPurchaseUrl(0, -1)" target="_blank"><img alt="Purchase Monthly"
                                                                    src="https://pluginsforwp.com/wp-content/uploads/2024/03/Plugin-Manager-Monthly-Plan.jpg"></a>
            </div>
            <div class="column">
              <a :href="getPurchaseUrl(0, -2)" target="_blank"><img alt="Purchase Yearly"
                                                                    src="https://pluginsforwp.com/wp-content/uploads/2024/03/Plugin-Manager-Annual-Plan.jpg"></a>
            </div>
            <div class="column">
              <a :href="getPurchaseUrl(0, -3)" target="_blank"><img alt="Purchase Lifetime"
                                                                    src="https://pluginsforwp.com/wp-content/uploads/2024/03/Plugin-Manager-Lifetime-Plan.jpg"></a>
            </div>
          </div>
        </div>

        <div v-if="pluginizer">
          <div class="columns is-centered">
            <div class="column">
              <a :href="getPurchaseUrl(0, -1)" target="_blank"><img alt="Purchase Monthly"
                                                                    src="https://pluginizer.com/wp-content/uploads/2023/09/PZ-Monthly-plan.png"></a>
            </div>
            <div class="column">
              <a :href="getPurchaseUrl(0, -2)" target="_blank"><img alt="Purchase Yearly"
                                                                    src="https://pluginizer.com/wp-content/uploads/2023/09/PZ-Yearly-plan.png"></a>
            </div>
            <div class="column">
              <a :href="getPurchaseUrl(0, -3)" target="_blank"><img alt="Purchase Lifetime"
                                                                    src="https://pluginizer.com/wp-content/uploads/2023/09/PZ-Lifetime-plan.png"></a>
            </div>
          </div>
        </div>
      </div>

      <p style="text-align: center;margin: 30px auto;">Need more information?
        <a @click="openDetailsModal({serverUrl: user.allAccessUrl})">Click here.</a>
      </p>
    </div>
  </div>


  <!-- Settings Tab -->


  <div v-show="activeTab === 'settings'" class="p4w-updater-tab-contents">
    <div class="panel">
      <p class="panel-heading">Connect Plugin:</p>
      <div class="panel-block">
        <div class="p4w-updater-form-settings">
          <div class="notification is-danger" v-show="!settings.key && !isBusy" v-cloak>
            Please enter your {{ companyName }} Username and Key to install your plugins.
          </div>

          <div class="notification is-danger" v-show="user.validCredentials === false && !isBusy" v-cloak>
            The username or key entered were not correct.
          </div>

          <div>
            <label>
              Username:
              <input type="text" class="input is-small" :placeholder="companyName + ' Username'"
                     v-model="settings.username">
            </label>
            <label>
              Key:
              <input type="text" class="input is-small" :placeholder="companyName + ' Key'" v-model="settings.key">
            </label>
            <label v-if="!pluginizer">
              Affiliate Name:
              <input type="text" class="input is-small" :placeholder="companyName + ' Affiliate Name (optional)'"
                     v-model="settings.affiliate">
            </label>
          </div>

        </div>
      </div>
      <div class="p4w-updater-form-buttons panel-block">
        <input type="button" value="Save Settings" class="button has-text-white"
               :class="pluginizer ? 'pz-button' : 'p4w-button'"
               @click="submitSettings()">
      </div>
      <div class="panel-block">
        <a @click="modals.youtube = true">Where to find your Username and Key?</a>
      </div>
    </div>
  </div>
</div>


<!-- Modals below -->

<q-dialog v-model="modals.isInstallModalActive"
          :class="pluginizer ? 'p4w-modal-pz' : 'p4w-modal'">
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Install {{ product.name }}?</p>
      <button class="delete" aria-label="close" @click="modals.isInstallModalActive = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      Are you sure you want to install this {{ product.type }}?
    </q-card-section>

    <footer class="modal-card-foot">
      <button class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'"
              @click="doInstall(product)">Install
      </button>
      <button class="button" @click="modals.isInstallModalActive = false">Cancel</button>
    </footer>
  </div>
</q-dialog>

<q-dialog v-model="modals.isInstallOk"
          :class="pluginizer ? 'p4w-modal-pz' : 'p4w-modal'">
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Install Succeded: {{ product.name }}</p>
      <button class="delete" aria-label="close" @click="modals.isInstallOk = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      The installation of the {{ product.name }} {{ product.type }} was a success.
    </q-card-section>

    <footer class="modal-card-foot">
      <button class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'"
              @click="modals.isInstallOk = false">Ok
      </button>

      <button v-if="product.type === 'plugin'"
              class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'"
              @click="activatePlugin(product)">Activate Plugin
      </button>
      <button v-if="product.type === 'theme'"
              class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'"
              @click="activateTheme(product)">Activate Theme
      </button>
    </footer>
  </div>
</q-dialog>

<q-dialog v-model="modals.isInstallError"
          :class="pluginizer ? 'p4w-modal-pz' : 'p4w-modal'">
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Install Error: {{ product.name }}</p>
      <button class="delete" aria-label="close" @click="modals.isInstallError = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      <p>There was a problem with the installation, activation, or deactivation of the <strong>{{
          product.name
        }}</strong>
        {{ product.type }}.</p>
      <p>Please check that you have bought the product or have the proper subscription in case of Premium and Ultimate
        Items.</p>
      <p>Also note that some plugins require manual installation as they contain multiple plugins and/or themes in one
        package.</p>
      <p>If the problem persists and you have purchased the product or the proper subscription, please contact
        <a :href="'mailto:' + companyEmail">{{ companyName }}</a>.</p>
    </q-card-section>

    <footer class="modal-card-foot">
      <button class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'" @click="postError()">
        Ok
      </button>
    </footer>
  </div>
</q-dialog>

<q-dialog v-model="modals.isProductListError"
          :class="pluginizer ? 'p4w-modal-pz' : 'p4w-modal'">
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Error fetching products</p>
      <button class="delete" aria-label="close" @click="modals.isProductListError = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      <p>There was a problem fetching your products. Please check your username and password and try again.</p>
      <p>If the problem persists and you are using the correct credentials, please contact
        <a :href="'mailto:' + companyEmail">{{ companyName }}</a>.</p>
    </q-card-section>

    <footer class="modal-card-foot">
      <button class="button has-text-white" :class="pluginizer ? 'pz-button' : 'p4w-button'" @click="modals.isProductListError = false">
        Ok
      </button>
    </footer>
  </div>
</q-dialog>

<q-dialog v-model="modals.details"
          :class="pluginizer ? 'p4w-modal-large-pz' : 'p4w-modal-large'"
          full-width
          full-height>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">{{ product.name }} <span class="p4w-link-small"></span></p>
      <button class="delete" aria-label="close" @click="modals.details = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      <iframe id="p4w-store-iframe"
              :title="companyName"
              style="width: 100% !important; height: 100% !important;"
              width="100%"
              height="100%"
              :src="product.serverUrl">
      </iframe>
    </q-card-section>
  </div>
</q-dialog>

<q-dialog v-model="modals.youtube"
          :class="pluginizer ? 'p4w-modal-large-pz' : 'p4w-modal-large'"
          full-width
          full-height>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">How to find {{ companyName }} Username and Key <span class="p4w-link-small"></span>
      </p>
      <button class="delete" aria-label="close" @click="modals.youtube = false"></button>
    </header>

    <q-card-section class="modal-card-body">
      <iframe width="100%" height="100%" src="https://www.youtube.com/embed/kwnpeVF4qqY"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
              allowfullscreen></iframe>
    </q-card-section>
  </div>
</q-dialog>
</div>
<script setup>
</script>
