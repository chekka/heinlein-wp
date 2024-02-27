import{f as M,a as A}from"./links.BhxvVKuk.js";import{J as L}from"./JsonValues.D25FTfEu.js";import{M as k}from"./MaxCounts.DHV7qSQX.js";import{B}from"./RadioToggle.XiBFFWmC.js";import{C}from"./Caret.Cuasz9Up.js";import{C as O}from"./ProBadge.Dgq0taM8.js";import{C as D}from"./RobotsMeta.DjWj_HSz.js";import{C as T}from"./SettingsRow.B0N4hwjp.js";import{y as l,o as s,c as w,D as d,m as n,l as r,d as a,E as p,t as c,a as S}from"./vue.esm-bundler.DzelZkHk.js";import{_ as x}from"./_plugin-vue_export-helper.BN1snXvA.js";import{t as G}from"./tags.Bp6OFtD5.js";import{T as I}from"./Tags.BmZ4Q9eM.js";import{C as j}from"./GoogleSearchPreview.D8LsBN4F.js";import{C as R}from"./HtmlTagsEditor.CoHm5iUc.js";const N={setup(){return{licenseStore:M(),optionsStore:A()}},components:{BaseRadioToggle:B,CoreAlert:C,CoreProBadge:O,CoreRobotsMeta:D,CoreSettingsRow:T},mixins:[L,k],props:{type:{type:String,required:!0},object:{type:Object,required:!0},options:{type:Object,required:!0},showBulk:Boolean,noMetaBox:Boolean,includeKeywords:Boolean},data(){return{titleCount:0,descriptionCount:0,strings:{robotsSetting:this.$t.__("Robots Meta Settings",this.$td),bulkEditing:this.$t.__("Bulk Editing",this.$td),readOnly:this.$t.__("Read Only",this.$td),otherOptions:this.$t.__("Other Options",this.$td),showDateInGooglePreview:this.$t.__("Show Date in Google Preview",this.$td),keywords:this.$t.__("Keywords",this.$td),removeCatBase:this.$t.__("Remove Category Base Prefix",this.$td),removeCatBaseUpsell:this.$t.sprintf(this.$t.__("Remove Category Base Prefix is a %1$s feature. %2$s",this.$td),"PRO",this.$links.getUpsellLink("search-appearance-advanced",this.$constants.GLOBAL_STRINGS.learnMore,"remove-category-base-prefix",!0))}}},computed:{removeCatBase:{get(){return this.$isPro?this.optionsStore.options.searchAppearance.advanced.removeCatBase:!1},set(t){this.optionsStore.options.searchAppearance.advanced.removeCatBase=t}},title(){return this.$t.sprintf(this.$t.__("%1$s Title",this.$td),this.object.singular)},showPostThumbnailInSearch(){return this.$t.sprintf(this.$t.__("Show %1$s Thumbnail in Google Custom Search",this.$td),this.object.singular)},showMetaBox(){return this.$t.sprintf(this.$t.__("Show %1$s Meta Box",this.$td),"AIOSEO")}}},U={class:"aioseo-sa-ct-advanced"},P=["innerHTML"],E={class:"other-options"};function q(t,i,e,b,o,u){const v=l("core-robots-meta"),m=l("core-settings-row"),g=l("base-radio-toggle"),y=l("core-pro-badge"),f=l("core-alert"),h=l("base-toggle"),V=l("base-select");return s(),w("div",U,[d(m,{name:o.strings.robotsSetting},{content:n(()=>[d(v,{options:e.options.advanced.robotsMeta,mainOptions:e.options},null,8,["options","mainOptions"])]),_:1},8,["name"]),e.showBulk?(s(),r(m,{key:0,name:o.strings.bulkEditing,align:""},{content:n(()=>[d(g,{modelValue:e.options.advanced.bulkEditing,"onUpdate:modelValue":i[0]||(i[0]=_=>e.options.advanced.bulkEditing=_),name:`${e.object.name}BulkEditing`,options:[{label:t.$constants.GLOBAL_STRINGS.disabled,value:"disabled"},{label:t.$constants.GLOBAL_STRINGS.enabled,value:"enabled"},{label:o.strings.readOnly,value:"read-only"}]},null,8,["modelValue","name","options"])]),_:1},8,["name"])):a("",!0),e.type==="taxonomies"&&e.object.name==="category"?(s(),r(m,{key:1,align:""},{name:n(()=>[p(c(o.strings.removeCatBase)+" ",1),b.licenseStore.isUnlicensed?(s(),r(y,{key:0})):a("",!0)]),content:n(()=>[d(g,{disabled:b.licenseStore.isUnlicensed,modelValue:u.removeCatBase,"onUpdate:modelValue":i[1]||(i[1]=_=>u.removeCatBase=_),name:"removeCatBase",options:[{label:t.$constants.GLOBAL_STRINGS.no,value:!1,activeClass:"dark"},{label:t.$constants.GLOBAL_STRINGS.yes,value:!0}]},null,8,["disabled","modelValue","options"]),b.licenseStore.isUnlicensed?(s(),r(f,{key:0,class:"inline-upsell",type:"blue"},{default:n(()=>[S("div",{innerHTML:o.strings.removeCatBaseUpsell},null,8,P)]),_:1})):a("",!0)]),_:1})):a("",!0),!e.noMetaBox&&(!b.licenseStore.isUnlicensed||e.type!=="taxonomies")?(s(),r(m,{key:2,name:o.strings.otherOptions},{content:n(()=>[S("div",E,[d(h,{modelValue:e.options.advanced.showMetaBox,"onUpdate:modelValue":i[2]||(i[2]=_=>e.options.advanced.showMetaBox=_)},{default:n(()=>[p(c(u.showMetaBox),1)]),_:1},8,["modelValue"])])]),_:1},8,["name"])):a("",!0),b.optionsStore.options.searchAppearance.advanced.useKeywords&&e.includeKeywords?(s(),r(m,{key:3,name:o.strings.keywords,align:""},{content:n(()=>[d(V,{multiple:"",taggable:"",options:t.getJsonValue(e.options.advanced.keywords)||[],modelValue:t.getJsonValue(e.options.advanced.keywords)||[],"onUpdate:modelValue":i[3]||(i[3]=_=>e.options.advanced.keywords=t.setJsonValue(_)),"tag-placeholder":o.strings.tagPlaceholder},null,8,["options","modelValue","tag-placeholder"])]),_:1},8,["name"])):a("",!0)])}const le=x(N,[["render",q]]),$={components:{BaseRadioToggle:B,CoreAlert:C,CoreGoogleSearchPreview:j,CoreHtmlTagsEditor:R,CoreSettingsRow:T},mixins:[k,I],props:{type:{type:String,required:!0},object:{type:Object,required:!0},separator:{type:String,required:!0},options:{type:Object,required:!0},edit:{type:Boolean,default(){return!0}}},data(){return{tags:G,titleCount:0,descriptionCount:0,strings:{showInSearchResults:this.$t.__("Show in Search Results",this.$td),clickToAddTitle:this.$t.__("Click on the tags below to insert variables into your title.",this.$td),metaDescription:this.$t.__("Meta Description",this.$td),clickToAddDescription:this.$t.__("Click on the tags below to insert variables into your meta description.",this.$td)}}},watch:{show(t){if(t){this.options.advanced.robotsMeta.noindex=!1,this.options.advanced.robotsMeta.nofollow===!1&&this.options.advanced.robotsMeta.noarchive===!1&&this.options.advanced.robotsMeta.notranslate===!1&&this.options.advanced.robotsMeta.noimageindex===!1&&this.options.advanced.robotsMeta.nosnippet===!1&&this.options.advanced.robotsMeta.noodp===!1&&parseInt(this.options.advanced.robotsMeta.maxSnippet)===-1&&parseInt(this.options.advanced.robotsMeta.maxVideoPreview)===-1&&this.options.advanced.robotsMeta.maxImagePreview.toLowerCase()==="large"&&(this.options.advanced.robotsMeta.default=!0);return}this.options.advanced.robotsMeta.default=!1,this.options.advanced.robotsMeta.noindex=!0}},computed:{title(){return this.$t.sprintf(this.$t.__("%1$s Title",this.$td),this.object.singular)},show(){return this.options.show},noIndexDescription(){return this.$t.sprintf(this.$t.__('Choose whether your %1$s should be included in search results. If you select "No", then your %1$s will be noindexed and excluded from the sitemap so that search engines ignore them.',this.$td),this.object.label)},noindexAlertDescription(){return this.$t.sprintf(this.$t.__("Your %1$s will be noindexed and excluded from the sitemap so that search engines ignore them. You can still control how their page title looks like below.",this.$td),this.object.label)}},methods:{}},J={class:"aioseo-sa-ct-title-description"},K={class:"aioseo-description"},H={key:0};function Y(t,i,e,b,o,u){const v=l("base-radio-toggle"),m=l("core-alert"),g=l("core-settings-row"),y=l("core-google-search-preview"),f=l("core-html-tags-editor");return s(),w("div",J,[d(g,{name:o.strings.showInSearchResults,align:""},{content:n(()=>[e.edit?(s(),r(v,{key:0,modelValue:e.options.show,"onUpdate:modelValue":i[0]||(i[0]=h=>e.options.show=h),name:`${e.object.name}ShowInSearch`,options:[{label:t.$constants.GLOBAL_STRINGS.no,value:!1,activeClass:"dark"},{label:t.$constants.GLOBAL_STRINGS.yes,value:!0}]},null,8,["modelValue","name","options"])):a("",!0),e.edit?a("",!0):(s(),r(v,{key:1,modelValue:!0,name:`${e.object.name}ShowInSearch`,options:[{label:t.$constants.GLOBAL_STRINGS.no,value:!1,activeClass:"dark"},{label:t.$constants.GLOBAL_STRINGS.yes,value:!0}]},null,8,["name","options"])),S("div",K,[e.options.show?(s(),w("span",H,c(u.noIndexDescription),1)):a("",!0),e.options.show?a("",!0):(s(),r(m,{key:1,type:"blue"},{default:n(()=>[p(c(u.noindexAlertDescription),1)]),_:1}))])]),_:1},8,["name"]),e.edit?(s(),r(g,{key:0,name:t.$constants.GLOBAL_STRINGS.preview},{content:n(()=>[d(y,{title:t.parseTags(e.options.title),description:t.parseTags(e.options.metaDescription)},null,8,["title","description"])]),_:1},8,["name"])):a("",!0),d(g,{name:u.title},{content:n(()=>[e.edit?(s(),r(f,{key:0,modelValue:e.options.title,"onUpdate:modelValue":i[1]||(i[1]=h=>e.options.title=h),"line-numbers":!1,single:"","tags-context":`${e.object.name}Title`,"default-tags":o.tags.getDefaultTags(e.type,e.object.name,"title")},{"tags-description":n(()=>[p(c(o.strings.clickToAddTitle),1)]),_:1},8,["modelValue","tags-context","default-tags"])):a("",!0),e.edit?a("",!0):(s(),r(f,{key:1,"line-numbers":!1,single:"","tags-context":`${e.object.name}Title`,"default-tags":o.tags.getDefaultTags(e.type,e.object.name,"title")},{"tags-description":n(()=>[p(c(o.strings.clickToAddTitle),1)]),_:1},8,["tags-context","default-tags"]))]),_:1},8,["name"]),e.options.show?(s(),r(g,{key:1,name:o.strings.metaDescription},{content:n(()=>[e.edit?(s(),r(f,{key:0,modelValue:e.options.metaDescription,"onUpdate:modelValue":i[2]||(i[2]=h=>e.options.metaDescription=h),"line-numbers":!1,description:"","tags-context":`${e.object.name}Description`,"default-tags":o.tags.getDefaultTags(e.type,e.object.name,"description")},{"tags-description":n(()=>[p(c(o.strings.clickToAddDescription),1)]),_:1},8,["modelValue","tags-context","default-tags"])):a("",!0),e.edit?a("",!0):(s(),r(f,{key:1,"line-numbers":!1,"tags-context":`${e.object.name}Description`,"default-tags":o.tags.getDefaultTags(e.type,e.object.name,"description")},{"tags-description":n(()=>[p(c(o.strings.clickToAddDescription),1)]),_:1},8,["tags-context","default-tags"]))]),_:1},8,["name"])):a("",!0)])}const de=x($,[["render",Y],["__scopeId","data-v-720a9d0c"]]);export{le as A,de as T};
