import{M as p}from"./links.DOdXC3mL.js";import{a as i}from"./addons.Z32PCU1d.js";import{R as m,a as u}from"./RequiresUpdate.De8-DduM.js";import{C as _}from"./Index.DOS69WTb.js";import{a as l}from"./Header.ClCSDZrb.js";import{_ as s}from"./_plugin-vue_export-helper.BN1snXvA.js";import{o,c as n,v as d,B as f,l as g,k as h,q as k}from"./runtime-dom.esm-bundler.tPRhSV4q.js";import x from"./Overview.DDDbW6vL.js";import"./default-i18n.DXRQgkn2.js";import"./helpers.CXsRrhc8.js";import"./upperFirst.yVnsg4QL.js";import"./_stringToArray.DnK4tKcY.js";import"./toString.zLSwYOtv.js";import"./RequiresUpdate.FatbCDTI.js";import"./license.BEch2NZa.js";import"./allowed.CbvFsadp.js";/* empty css             */import"./params.B3T1WKlC.js";import"./Ellipse.CoDaSPOK.js";import"./Caret.Ke5gylGO.js";import"./ScrollAndHighlight.CY6uLkOC.js";import"./LogoGear.BbumEdXr.js";import"./Logo.bX-u9KVJ.js";import"./Support.DcbjlfoT.js";import"./Tabs.CvdL3nC2.js";import"./TruSeoScore.DmC22Awy.js";import"./Information.Bv8uKEyF.js";import"./Slide.fjAuzpC8.js";import"./Url.CWVJVoT6.js";import"./Date.abl_uWS6.js";import"./constants.qeJG2F0i.js";import"./Exclamation.DGJubTNT.js";import"./Gear.DwDaVskn.js";import"./AnimatedNumber.DeYVxHDv.js";import"./numbers.BT5e8rgb.js";import"./index.BR_tv7_M.js";import"./AddonConditions.BFVZ2btl.js";import"./Index.DyvJ1GBk.js";import"./Row.DRnp1mVs.js";import"./Blur.CvHKqkVq.js";import"./Card.DPoAfijm.js";import"./Tooltip.DhkkBQWW.js";import"./InternalOutbound.DBUIpJG6.js";import"./DonutChartWithLegend.B20yT2Df.js";import"./SeoSiteScore.DFn0k99t.js";import"./Row.ClzcKm5C.js";import"./RequiredPlans.BRtESoGg.js";const $={};function v(t,r){return o(),n("div")}const A=s($,[["render",v]]),b={};function S(t,r){return o(),n("div")}const y=s(b,[["render",S]]),R={};function T(t,r){return o(),n("div")}const w=s(R,[["render",T]]),C={};function B(t,r){return o(),n("div")}const L=s(C,[["render",B]]),M={setup(){return{linkAssistantStore:p()}},components:{CoreMain:_,CoreProcessingPopup:l,DomainsReport:A,LinksReport:y,Overview:x,PostReport:w,Settings:L},mixins:[m,u],data(){return{strings:{pageName:this.$t.__("Link Assistant",this.$td)}}},computed:{excludedTabs(){const t=(i.isActive("aioseo-link-assistant")?this.getExcludedUpdateTabs("aioseo-link-assistant"):this.getExcludedActivationTabs("aioseo-link-assistant"))||[];return t.push("post-report"),t}},mounted(){window.aioseoBus.$on("changes-saved",()=>{this.linkAssistantStore.getMenuData()}),this.$isPro&&this.linkAssistantStore.suggestionsScan.percent!==100&&i.isActive("aioseo-link-assistant")&&!i.requiresUpgrade("aioseo-link-assistant")&&i.hasMinimumVersion("aioseo-link-assistant")&&this.linkAssistantStore.pollSuggestionsScan()}},P={class:"aioseo-link-assistant"};function q(t,r,U,D,e,a){const c=d("core-main");return o(),n("div",P,[f(c,{"page-name":e.strings.pageName,"exclude-tabs":a.excludedTabs,showTabs:t.$route.name!=="post-report"},{default:g(()=>[(o(),h(k(t.$route.name)))]),_:1},8,["page-name","exclude-tabs","showTabs"])])}const Bt=s(M,[["render",q]]);export{Bt as default};
