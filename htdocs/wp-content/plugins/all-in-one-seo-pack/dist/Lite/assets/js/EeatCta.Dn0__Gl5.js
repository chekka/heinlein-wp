import{f as k,r as D}from"./links.cMjoa9mX.js";import{A as $}from"./AddonConditions.BLZE2_Am.js";import{J as C}from"./JsonValues.D25FTfEu.js";import{u as R}from"./EeatCta.BAUm4qFR.js";import{C as L}from"./Index.DUPzmxCE.js";import{o as f,l as v,m as r,a as s,C as n,u as a,D as c,t as l,c as w,F as P,K as x,H as N,x as y,d as E}from"./vue.esm-bundler.CWQFYt9y.js";import{_ as t,s as i}from"./default-i18n.Bd0Z306Z.js";import{B as W}from"./Caret.iRBf3wcH.js";import{B as J}from"./Checkbox.D2dfpbIi.js";import{a as A,B as I,b as M,S as H}from"./index.BB7B6rSp.js";import{B as z}from"./RadioToggle.BLVmJ7Zx.js";import{C as j}from"./Blur.DNVDismY.js";import{C as B}from"./Card.B69_swGC.js";import{C as O}from"./SettingsRow.DQldd-1Z.js";import{C as T}from"./Tooltip.DiN2Zjvc.js";import{C as G}from"./Index.DTXYn-_e.js";import{S as q}from"./Plus.DrDYrwHh.js";import{R as F}from"./RequiredPlans.DAdiEWvO.js";import{_ as U}from"./_plugin-vue_export-helper.BN1snXvA.js";import"./helpers.D2xRWOvn.js";import"./addons.CjB9Xv4t.js";import"./upperFirst.CP4N4hLd.js";import"./_stringToArray.DnK4tKcY.js";import"./toString.XwB3Xa5p.js";import"./Row.CzuhYwfs.js";import"./constants.B6ynd7gz.js";import"./Checkmark.pCOnqh_g.js";import"./Slide.CRIn0kdn.js";import"./CheckSolid.ChbVSAiM.js";import"./license.ctOGGUd5.js";const K=()=>({getJsonValue:(o,d=null)=>{try{o=JSON.parse(o)}catch{o=d}return o},setJsonValue:o=>JSON.stringify(o),getJsonValues:o=>o.map(d=>JSON.parse(d)),setJsonValues:o=>o.map(d=>JSON.stringify(d))});var X={VITE_AIOSEO_DEV_PORT:"8085",VITE_AIOSEO_DOMAIN:"localhost",VITE_TEXTDOMAIN:"all-in-one-seo-pack",VITE_TEXTDOMAIN_PRO:"aioseo-pro",VITE_VERSION:"Lite",VITE_SHORT_NAME:"AIOSEO",VITE_NAME:"All in One SEO",VITE_TRUSEO_WEB_WORKER:"true",BASE_URL:"./",MODE:"production",DEV:!1,PROD:!0,SSR:!1};const Q={id:"aioseo-author-seo"},Y={class:"aioseo-description"},Z=["innerHTML"],ee={class:"aioseo-description"},te=["innerHTML"],oe={class:"topics-table"},se={class:"tooltip-wrapper"},ae={class:"tooltip-wrapper"},ne={class:"tooltip-wrapper"},ie=s("td",null,null,-1),re={class:"name"},le={class:"url"},ce={class:"same-as-urls"},ue={class:"actions"},pe={colspan:"4"},de={__name:"EeatBlur",setup(p){const{getJsonValue:m}=K(),_=[{name:"WordPress",url:"https://wordpress.org",sameAsUrls:[]},{name:"SEO",url:"https://aioseo.com",sameAsUrls:[]},{name:"Schema Markup",url:"https://schema.org",sameAsUrls:[]}],e="all-in-one-seo-pack",o={settings:t("Settings",e),experienceTopics:t("Author Experience Topics (E-E-A-T)",e),experienceTopicsDescription:i(t("The following settings will be added directly to an author's schema meta data via the %1$sknowsAbout%2$s property. This property helps with the Experience aspect of Google's E-E-A-T guidelines. After setting the global options here, you can add them directly in an authors profile page.",e),"<code>","</code>"),name:t("Name",e),url:t("URL",e),sameAsUrls:t("Same As URLs",e),addItem:t("Add Item",e),delete:t("Delete",e),nameTooltip:t('The name of the item the author knows about (e.g. "Amazon").',e),urlTooltip:t('The URL of the item the author knows about (e.g. "https://amazon.com").',e),sameAsUrlsTooltip:t('Additional URLs to help identify the item (e.g. "https://en.wikipedia.org/wiki/Amazon_(company)").',e),sameAsUrlsPlaceholder:t("Enter a URL and press enter",e),tagPlaceholder:t("Press enter to insert a URL",e),authorBioInjection:t("Append Author Bio to Posts",e),authorBioInjectionDescription:i(t("Choose whether %1$s should automatically append a compact author bio at the end of every post. You can also manually insert the author bio using the Author Bio block.",e),X.VITE_AIOSEO_SHORT_NAME),postTypes:t("Post Types",e),includeAllPostTypes:t("Include All Post Types",e),selectPostTypes:t("Select the post types for which you want to automatically inject an author bio.",e)},d={block:{desc:i('<p>The following blocks are available in the Block Editor:</p><ul style="list-style:disc; margin-left: 24px;"><li>AIOSEO - Author Bio</li><li>AIOSEO - Author Name</li><li>AIOSEO - Reviewer Name</li></ul>')},shortcode:{multiple:[{copy:"[aioseo_eeat_author_bio]",desc:t("Use the following shortcode to display the author bio.",e),hasAdvanced:!0,attributes:[{name:"compact",description:i(t("Whether the compact author bio should be output or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following shortcode attributes can be used to override the default settings:",e)},{copy:"[aioseo_eeat_author_tooltip]",desc:t("Use the following shortcode to display the author name.",e),hasAdvanced:!0,attributes:[{name:"show-label",description:i(t('Whether to display the "Written By" label or not. Defaults to %1$s.',e),"<code>true</code>")},{name:"show-image",description:i(t("Whether to display the author image or not. Defaults to %1$s.",e),"<code>true</code>")},{name:"show-tooltip",description:i(t("Whether to display the popup when someone hovers over the name or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following shortcode attributes can be used to override the default settings:",e)},{copy:"[aioseo_eeat_reviewer_tooltip]",desc:t("Use the following shortcode to display the reviewer name.",e),hasAdvanced:!0,attributes:[{name:"show-label",description:i(t('Whether to display the "Reviewed By" label or not. Defaults to %1$s.',e),"<code>true</code>")},{name:"show-image",description:i(t("Whether to display the reviewer image or not. Defaults to %1$s.",e),"<code>true</code>")},{name:"show-tooltip",description:i(t("Whether to display the popup when someone hovers over the name or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following shortcode attributes can be used to override the default settings:",e)}]},php:{multiple:[{copy:"<?php if( function_exists( 'aioseo_eeat_author_bio' ) ) aioseo_eeat_author_bio(); ?>",desc:t("Use the following PHP code anywhere in your theme's post templates or author archive template to display a bio for the author.",e),hasAdvanced:!0,attributes:[{name:"$compact",description:i(t("Whether the compact author bio should be output or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following function arguments can be used to override the default settings:",e)},{copy:"<?php if( function_exists( 'aioseo_eeat_author_tooltip' ) ) aioseo_eeat_author_tooltip(); ?>",desc:t("Use the following PHP code anywhere in your theme's post templates to display a bio for the post author.",e),hasAdvanced:!0,attributes:[{name:"$showLabel",description:i(t('Whether to display the "Written By" label or not. Defaults to %1$s.',e),"<code>true</code>")},{name:"$showImage",description:i(t("Whether to display the author image or not. Defaults to %1$s.",e),"<code>true</code>")},{name:"$showTooltip",description:i(t("Whether to display the popup when someone hovers over the name or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following function arguments can be used to override the default settings:",e)},{copy:"<?php if( function_exists( 'aioseo_eeat_reviewer_tooltip' ) ) aioseo_eeat_reviewer_tooltip(); ?>",desc:t("Use the following PHP code anywhere in your theme's post templates to display a bio for the post reviewer.",e),hasAdvanced:!0,attributes:[{name:"$showLabel",description:i(t('Whether to display the "Reviewed By" label or not. Defaults to %1$s.',e),"<code>true</code>")},{name:"$showImage",description:i(t("Whether to display the reviewer image or not. Defaults to %1$s.",e),"<code>true</code>")},{name:"$showTooltip",description:i(t("Whether to display the popup when someone hovers over the name or not. Defaults to %1$s.",e),"<code>true</code>")}],attributesDescription:t("The following function arguments can be used to override the default settings:",e)}]}};return(h,b)=>(f(),v(a(j),null,{default:r(()=>[s("div",Q,[n(a(B),{id:"aioseo-author-seo-settings",slug:"authorSeoSettings","header-text":o.settings,noSlide:""},{default:r(()=>[n(a(G),{options:d,plural:""}),n(a(O),{name:o.authorBioInjection},{content:r(()=>[n(a(z),{value:"true",name:"authorBioInjection",options:[{label:h.$constants.GLOBAL_STRINGS.disabled,value:!1,activeClass:"dark"},{label:h.$constants.GLOBAL_STRINGS.enabled,value:!0}]},null,8,["options"]),s("div",Y,[c(l(o.authorBioInjectionDescription)+" ",1),s("span",{innerHTML:h.$links.getDocLink(h.$constants.GLOBAL_STRINGS.learnMore,"eeatAuthorBioInjection",!0)},null,8,Z)])]),_:1},8,["name"]),n(a(O),{name:o.postTypes},{content:r(()=>[n(a(J),{size:"medium",value:"true"},{default:r(()=>[c(l(o.includeAllPostTypes),1)]),_:1}),s("div",ee,l(o.selectPostTypes),1)]),_:1},8,["name"])]),_:1},8,["header-text"]),n(a(B),{id:"aioseo-author-seo-topics",slug:"authorSeoTopics","header-text":o.experienceTopics,noSlide:""},{default:r(()=>[s("div",{class:"aioseo-settings-row aioseo-section-description",innerHTML:o.experienceTopicsDescription},null,8,te),s("table",oe,[s("thead",null,[s("tr",null,[s("td",null,[s("div",se,[c(l(o.name)+" ",1),n(a(T),null,{tooltip:r(()=>[c(l(o.nameTooltip),1)]),default:r(()=>[n(a(A))]),_:1})])]),s("td",null,[s("div",ae,[c(l(o.url)+" ",1),n(a(T),null,{tooltip:r(()=>[c(l(o.urlTooltip),1)]),default:r(()=>[n(a(A))]),_:1})])]),s("td",null,[s("div",ne,[c(l(o.sameAsUrls)+" ",1),n(a(T),null,{tooltip:r(()=>[c(l(o.sameAsUrlsTooltip),1)]),default:r(()=>[n(a(A))]),_:1})])]),ie])]),s("tbody",null,[(f(),w(P,null,x(_,(u,S)=>s("tr",{class:N({even:S%2===0}),key:S},[s("td",re,[n(a(I),{size:"medium",modelValue:u.name,"onUpdate:modelValue":g=>u.name=g,"append-icon":"circle-close","append-icon-on-value":"",onAppendIconClick:g=>u.name=""},null,8,["modelValue","onUpdate:modelValue","onAppendIconClick"])]),s("td",le,[n(a(I),{size:"medium",modelValue:u.url,"onUpdate:modelValue":g=>u.url=g,"append-icon":"circle-close","append-icon-on-value":"",onAppendIconClick:g=>u.url=""},null,8,["modelValue","onUpdate:modelValue","onAppendIconClick"])]),s("td",ce,[n(a(M),{size:"medium",multiple:"",taggable:"",options:a(m)(u.sameAsUrls)||[],modelValue:a(m)(u.sameAsUrls)||[],placeholder:o.sameAsUrlsPlaceholder,"tag-placeholder":o.tagPlaceholder},null,8,["options","modelValue","placeholder","tag-placeholder"])]),s("td",ue,[n(a(T),{type:"action"},{tooltip:r(()=>[c(l(o.delete),1)]),default:r(()=>[n(a(H))]),_:1})])],2)),64))]),s("tfoot",null,[s("tr",null,[s("td",pe,[n(a(W),{size:"small-table",type:"black"},{default:r(()=>[n(a(q)),c(" "+l(o.addItem),1)]),_:1})])])])])]),_:1},8,["header-text"])])]),_:1}))}},he={setup(){const{addonSlug:p,features:m,strings:_}=R();return{addonSlug:p,features:m,licenseStore:k(),links:D,strings:_}},components:{Cta:L,EeatBlur:de,RequiredPlans:F},mixins:[$,C]};function me(p,m,_,e,o,d){const h=y("eeat-blur"),b=y("required-plans"),u=y("cta");return f(),w("div",null,[n(h),n(u,{"cta-link":e.links.getPricingUrl("eeat","eeat-upsell"),"button-text":e.strings.ctaButtonText,"learn-more-link":e.links.getUpsellUrl("eeat",null,p.$isPro?"pricing":"liteUpgrade"),"feature-list":e.features,alignTop:""},{"header-text":r(()=>[c(l(e.strings.headerText),1)]),description:r(()=>[n(b,{addon:"aioseo-eeat"}),c(" "+l(e.strings.description),1)]),_:1},8,["cta-link","button-text","learn-more-link","feature-list"])])}const V=U(he,[["render",me]]),_e={components:{EeatCta:V,EeatCtaLite:V},mixins:[$],data(){return{addonSlug:"aioseo-eeat"}}},fe={class:"author-seo-cta"};function ge(p,m,_,e,o,d){const h=y("eeat-cta",!0),b=y("eeat-cta-lite");return f(),w("div",fe,[p.shouldShowUpdate||p.shouldShowActivate?(f(),v(h,{key:0})):E("",!0),p.shouldShowLite?(f(),v(b,{key:1})):E("",!0)])}const Fe=U(_e,[["render",ge]]);export{Fe as default};
