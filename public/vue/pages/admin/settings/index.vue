<template>
  <div>
    <main>
      <div class="container">
        <div class="row">
          <div class="col">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
              <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                  <h1 class="mb-0 pb-0 display-4" id="title">
                    {{ $t("Settings") }}
                  </h1>
                  <nav
                    class="breadcrumb-container d-inline-block"
                    aria-label="breadcrumb"
                  >
                    <ul class="breadcrumb pt-0">
                      <li class="breadcrumb-item">
                        <nuxt-link :to="{ name: 'home' }">
                          {{ $t("Home") }}
                        </nuxt-link>
                      </li>
                    </ul>
                  </nav>
                </div>
                <!-- Title End -->
                <!-- tabs -->
                <div class="mt-5">
                  <section class="scroll-section" id="lines">
                    <div class="card mb-5">
                      <div class="card-body">
                        <ul class="nav card-header-tabs nav-tabs nav-tabs-line" id="mySettingTab" role="tablist">
                          <li class="nav-item" v-for="(gSettings, group) in settings">
                            <a
                              style="cursor: pointer"
                              class="nav-link"
                              @click="activeTab = group"
                              :class="[activeTab === group ? 'active' : '']"
                              :id="'tab-' + group"
                              data-toggle="tab"
                              role="tab"
                              :aria-controls="'tab-' + group"
                              aria-selected="true"
                            >
                              {{ group }}
                            </a>
                          </li>
                        </ul>
                        <div class="tab-content" id="mySettingTabContent">
                          <div
                            v-for="(gSettings, group) in settings"
                            v-if="activeTab === group"
                            class="tab-pane  row d-flex mt-4"
                            :id="'tab-' + group"
                            role="tabpanel"
                            :aria-labelledby="'tab-' + group"
                          >
                            <div
                              v-for="(setting, index) in gSettings"
                              class="row"
                            >
                              <div class="col-md-2" style="margin-top: 7px">
                                <label :for="setting.key">{{
                                    setting.key.replace(/_/g, " ").toLowerCase()
                                  }}</label>
                              </div>

                              <div class="" v-if="setting.type === 'textarea' && setting.key != 'DESCRIPTION'">
                                <div style="margin: 10px"
                                     v-if="setting.key === 'MAIL_NOTIFY_NEW_USER' || setting.key === 'MAIL_NOTIFY_CHANGE_PASSWORD'">
                                  <b>User name:</b> [[name]]
                                  <b>User email:</b> [[email]]
                                  <b>User Password:</b> [[password]]
                                </div>
                                <div style="margin: 10px"
                                     v-if="setting.key === 'MAIL_NOTIFY_NEW_REQUEST'
                                        || setting.key === 'MAIL_NOTIFY_EDIT_REQUEST'
                                        || setting.key === 'MAIL_NOTIFY_ASSIGNED_REQUEST'
                                        || setting.key === 'MAIL_NOTIFY_RETURNED_REQUEST'
                                        || setting.key === 'MAIL_NOTIFY_CLOSED_REQUEST'
                                        || setting.key === 'MAIL_NOTIFY_APPROVE_SECRET_REQUEST'
                                ">
                                  <b>User name:</b> [[name]]
                                  <b>User email:</b> [[email]]
                                  <b>Request Number:</b> [[requestNo]]
                                  <b>Request URL:</b> [[mailLink]]
                                </div>
                                <div style="margin: 10px" v-if="setting.key === 'MAIL_NOTIFY_CHANGE_REQUEST_STATUS'">
                                  <b>User name:</b> [[name]]
                                  <b>User email:</b> [[email]]
                                  <b>Status:</b> [[status]]
                                </div>
                                <vue-editor
                                  v-model="settings[group][index].value"
                                  :id="setting.key"
                                ></vue-editor>

                              </div>

                              <div class="form-group col-md-4" style="margin-top: 5px" v-else-if="setting.type === 'textarea' && setting.key === 'DESCRIPTION'">
                                <textarea class="form-control" rows="5" :name="setting.key" v-model="settings[group][index].value">
                                </textarea>
                              </div>

                              <div v-else-if="setting.type === 'file'" style="margin-top: 5px"
                                   class="form-group col-md-4">
                                <img
                                  :src="getImageUrl(setting.value)"
                                  :alt="setting.key"
                                  style="max-height: 60px;cursor: pointer;"
                                  :onclick="
                        'document.getElementById(\'' +
                          setting.key +
                          '\').click()'
                      "
                                />
                                <input
                                  :type="setting.type"
                                  :id="setting.key"
                                  class="form-control"
                                  style="position:absolute;z-index:-1;display: none;"
                                  @change="upload($event, setting.key, group, index)"
                                />
                              </div>
                              <div v-else-if="setting.type === 'boolean'" style="margin-top: 5px"
                                   class="form-group col-md-4">
                                <label class="col">
                                  <input
                                    type="radio"
                                    :name="setting.key"
                                    v-model="settings[group][index].value"
                                    :selected="setting.value === 'true'"
                                    value="true"
                                  />
                                  {{ $t("True") }}
                                </label>
                                <label class="col">
                                  <input
                                    type="radio"
                                    :name="setting.key"
                                    v-model="settings[group][index].value"
                                    :selected="setting.value === 'false'"
                                    value="false"
                                  />
                                  {{ $t("False") }}
                                </label>
                              </div>

                              <div v-else-if="setting.type === 'select' && setting.key==='ASSIGN_REQUEST'" style="margin-top: 5px"
                                   class="form-group col-md-4">
                                <select class="form-control" v-model="settings[group][index].value">
                                  <option value="1" :selected="setting.value === 1">{{ $t("Same Organization & have Template Permission") }}</option>
                                  <option value="2" :selected="setting.value === 2">{{ $t("Not Same Organization & haven't Template Permission") }}</option>
                                </select>
                              </div>

                              <div class="form-group col-md-4" style="margin-top: 5px" v-else>
                                <input
                                  :type="setting.type"
                                  :id="setting.key"
                                  class="form-control"
                                  v-model="settings[group][index].value"
                                />
                              </div>

                            </div>
                          </div>
                        </div>
                        <button
                          type="button"
                          class="btn btn-primary mt-4"
                          @click="update"
                        >
                          {{ $t("Update")}} ...
                        </button>
                      </div>
                    </div>
                  </section>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import {VueEditor, Quill} from "vue2-editor";

export default {
  name: "index",
  layout: "admin",
  middleware: ["auth", "admin"],
  components: {VueEditor},
  head() {
    return {
      title: "settings"
    };
  },
  data() {
    return {
      settings: [],
      activeTab: "app",
      appUrl:''
    }
  },
  created() {
    this.getSettingData()
  },
  methods:{
    getImageUrl(name) {
      return this.appUrl + "/" + name.replace("public", "storage");
    },
    getSettingData()
    {
      window.jQueryStartLoading()
      return this.$axios.get("admin/settings")
        .then(response => {
          this.settings = response.data.data
          this.appUrl = response.data.appUrl
          window.jQueryEndLoading();
        }).catch(error => {
          console.log(error)
          window.jQueryEndLoading()
        })
    },
    update() {
      this.validationErrors = [];
      let data = [];
      window.jQueryStartLoading()
      for (let group in this.settings)
        for (let setting in this.settings[group])
          data.push(this.settings[group][setting]);
      this.$axios
        .put("admin/settings", {
          data: data
        })
        .then(response => {
          if(response.data.code == 200){
            window.jQueryEndLoading();
            window.jQueryToast(this.$t("Update_setting_successfully"), "success");
          }else{
            window.jQueryEndLoading();
            window.jQueryToast(this.$t("error_in_update"), "danger");
          }
        })
        .catch(error => {
          window.jQueryEndLoading();
          console.log(error)
          if (error.response.data.message === "Validation Error")
            this.validationErrors = error.response.data.errors;
        });
    },
    upload(e, key, group, index) {
      console.log('group is : ',group)
      console.log('key is : ',key)
      this.loading = true;
      let file = document.getElementById(key).files[0];
      let formData = new FormData();
      formData.append("file", file);
      formData.append("folder", "settings");
      formData.append("group", group);
      formData.append("key", key);
      this.$axios
        .post("admin/settings/uploader", formData)
        .then(response => {
          if (response.data.id) {
            this.settings[group][index].value = response.data.value;
            this.update();
          }
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    }
  }
}
</script>
