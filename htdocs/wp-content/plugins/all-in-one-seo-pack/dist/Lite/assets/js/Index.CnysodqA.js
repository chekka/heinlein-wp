import{f as V,u as z,I as R,e as W,h as w,a as j,m as X}from"./links.BhxvVKuk.js";import{l as J}from"./license.B4xmRPjf.js";import{a as Q}from"./allowed.BqqivOa5.js";/* empty css             */import{g as Z,r as tt}from"./params.B3T1WKlC.js";import{a as et}from"./Ellipse.BqPxWN8A.js";import{S as G,d as it,B as st,c as q,e as F,C as ot}from"./Caret.Cuasz9Up.js";import{b as nt,C as rt,G as at}from"./Header.Y0Z-knGL.js";import{C as ct,a as lt}from"./ScrollAndHighlight.4Hg7p3w8.js";import{S as dt}from"./Logo.CuK32Muc.js";import{o as n,c as l,a as i,y as u,l as g,d as f,D as d,H as v,F as N,L,t as a,I as A,m as _,E as b,x,q as E,T as O}from"./vue.esm-bundler.DzelZkHk.js";import{_ as k}from"./_plugin-vue_export-helper.BN1snXvA.js";import{S as ut}from"./Support.B5EAN5JN.js";import{C as ft}from"./Tabs.DlfLDOi9.js";import{_ as H}from"./default-i18n.BtxsUzQk.js";import{n as ht}from"./isArrayLikeObject.CkjpbQo7.js";import{U as mt}from"./Url.DOSCnr7T.js";import{D as _t}from"./Date.Byi1_l89.js";import{S as pt}from"./Exclamation.BU2oeqa4.js";import{S as gt}from"./Gear.CzHv0eD2.js";import{T as I}from"./Slide.BfXXFx9A.js";const U="all-in-one-seo-pack",vt=()=>({strings:{notifications:H("Notifications",U),newNotifications:H("New Notifications",U),activeNotifications:H("Active Notifications",U)}}),yt={},bt={viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg",class:"aioseo-description"},kt=i("path",{d:"M0 0h24v24H0V0z",fill:"none"},null,-1),St=i("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M8 16h8v2H8zm0-4h8v2H8zm6-10H6c-1.1 0-2 .9-2 2v16c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm4 18H6V4h7v5h5v11z",fill:"currentColor"},null,-1),$t=[kt,St];function Nt(t,e){return n(),l("svg",bt,$t)}const Ct=k(yt,[["render",Nt]]),wt={},Dt={viewBox:"0 0 24 24",fill:"none",xmlns:"http://www.w3.org/2000/svg",class:"aioseo-folder-open"},Lt=i("path",{d:"M0 0h24v24H0V0z",fill:"none"},null,-1),Pt=i("path",{"fill-rule":"evenodd","clip-rule":"evenodd",d:"M20 6h-8l-2-2H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm0 12H4V8h16v10z",fill:"currentColor"},null,-1),Tt=[Lt,Pt];function At(t,e){return n(),l("svg",Dt,Tt)}const It=k(wt,[["render",At]]),Bt={setup(){return{licenseStore:V(),rootStore:z(),helpPanelStore:R(),settingsStore:W()}},components:{CoreApiBar:ct,CoreLicenseKeyBar:lt,CoreUpgradeBar:nt,SvgAioseoLogo:dt,SvgClose:G,SvgDescription:Ct,SvgFolderOpen:It,SvgSupport:ut},data(){return{searchItem:null,strings:{close:this.$t.__("Close",this.$td),search:this.$t.__("Search",this.$td),viewAll:this.$t.__("View All",this.$td),docs:this.$t.__("Docs",this.$td),viewDocumentation:this.$t.__("View Documentation",this.$td),browseDocumentation:this.$t.sprintf(this.$t.__("Browse documentation, reference material, and tutorials for %1$s.",this.$td),"AIOSEO"),viewAllDocumentation:this.$t.__("View All Documentation",this.$td),getSupport:this.$t.__("Get Support",this.$td),submitTicket:this.$t.__("Submit a ticket and our world class support team will be in touch soon.",this.$td),submitSupportTicket:this.$t.__("Submit a Support Ticket",this.$td),upgradeToPro:this.$t.__("Upgrade to Pro",this.$td)}}},computed:{filteredDocs(){return this.searchItem!==""?Object.values(this.helpPanelStore.docs).filter(t=>this.searchItem!==null?t.title.toLowerCase().includes(this.searchItem.toLowerCase()):null):null}},methods:{inputSearch:function(t){it(()=>{this.searchItem=t},1e3)},toggleSection:function(t){t.target.parentNode.parentNode.classList.toggle("opened")},toggleDocs:function(t){t.target.previousSibling.classList.toggle("opened"),t.target.style.display="none"},toggleModal(){document.getElementById("aioseo-help-modal").classList.toggle("visible"),document.body.classList.toggle("modal-open")},getCategoryDocs(t){return Object.values(this.helpPanelStore.docs).filter(e=>e.categories.flat().includes(t)?e:null)}}},Mt={id:"aioseo-help-modal",class:"aioseo-help"},Ht={class:"aioseo-help-header"},Ut={class:"logo"},Et=["href"],Ot=["title"],zt={class:"help-content"},qt={id:"aioseo-help-search"},Vt={id:"aioseo-help-result"},Rt={class:"aioseo-help-docs"},jt={class:"icon"},Gt=["href"],Ft={id:"aioseo-help-categories"},xt={class:"aioseo-help-categories-toggle"},Yt={class:"folder-open"},Kt={class:"title"},Wt=i("span",{class:"dashicons dashicons-arrow-right-alt2"},null,-1),Xt={class:"aioseo-help-docs"},Jt={class:"icon"},Qt=["href"],Zt={class:"aioseo-help-additional-docs"},te={class:"icon"},ee=["href"],ie={id:"aioseo-help-footer"},se={class:"aioseo-help-footer-block"},oe=["href"],ne={class:"aioseo-help-footer-block"},re=["href"];function ae(t,e,r,c,s,o){const m=u("core-upgrade-bar"),p=u("core-license-key-bar"),h=u("core-api-bar"),y=u("svg-aioseo-logo"),P=u("svg-close"),B=u("base-input"),D=u("svg-description"),Y=u("svg-folder-open"),T=u("base-button"),K=u("svg-support");return n(),l("div",Mt,[!t.$isPro&&c.settingsStore.settings.showUpgradeBar&&c.rootStore.pong?(n(),g(m,{key:0})):f("",!0),t.$isPro&&c.licenseStore.isUnlicensed&&c.rootStore.pong?(n(),g(p,{key:1})):f("",!0),c.rootStore.pong?f("",!0):(n(),g(h,{key:2})),i("div",Ht,[i("div",Ut,[c.licenseStore.isUnlicensed?(n(),l("a",{key:0,href:t.$links.utmUrl("header-logo"),target:"_blank"},[d(y,{id:"aioseo-help-logo"})],8,Et)):f("",!0),c.licenseStore.isUnlicensed?f("",!0):(n(),g(y,{key:1,id:"aioseo-help-logo"}))]),i("div",{id:"aioseo-help-close",title:s.strings.close,onClick:e[0]||(e[0]=v((...S)=>o.toggleModal&&o.toggleModal(...S),["stop"]))},[d(P)],8,Ot)]),i("div",zt,[i("div",qt,[d(B,{type:"text",size:"medium",placeholder:s.strings.search,"onUpdate:modelValue":e[1]||(e[1]=S=>o.inputSearch(S))},null,8,["placeholder"])]),i("div",Vt,[i("ul",Rt,[(n(!0),l(N,null,L(o.filteredDocs,(S,C)=>(n(),l("li",{key:C},[i("span",jt,[d(D)]),i("a",{href:t.$links.utmUrl("help-panel-doc","",S.url),rel:"noopener noreferrer",target:"_blank"},a(S.title),9,Gt)]))),128))])]),i("div",Ft,[i("ul",xt,[(n(!0),l(N,null,L(c.helpPanelStore.categories,(S,C)=>(n(),l("li",{key:C,class:A(["aioseo-help-category",{opened:C==="getting-started"}])},[i("header",{onClick:e[2]||(e[2]=v($=>o.toggleSection($),["stop"]))},[i("span",Yt,[d(Y)]),i("span",Kt,a(S),1),Wt]),i("ul",Xt,[(n(!0),l(N,null,L(o.getCategoryDocs(C).slice(0,5),($,M)=>(n(),l("li",{key:M},[i("span",Jt,[d(D)]),i("a",{href:t.$links.utmUrl("help-panel-doc","",$.url),rel:"noopener noreferrer",target:"_blank"},a($.title),9,Qt)]))),128)),i("div",Zt,[(n(!0),l(N,null,L(o.getCategoryDocs(C).slice(5,o.getCategoryDocs(C).length),($,M)=>(n(),l("li",{key:M},[i("span",te,[d(D)]),i("a",{href:t.$links.utmUrl("help-panel-doc","",$.url),rel:"noopener noreferrer",target:"_blank"},a($.title),9,ee)]))),128))]),o.getCategoryDocs(C).length>=5?(n(),g(T,{key:0,class:"aioseo-help-docs-viewall gray medium",onClick:e[3]||(e[3]=v($=>o.toggleDocs($),["stop"]))},{default:_(()=>[b(a(s.strings.viewAll)+" "+a(S)+" "+a(s.strings.docs),1)]),_:2},1024)):f("",!0)])],2))),128))])]),i("div",ie,[i("div",se,[i("a",{href:t.$links.utmUrl("help-panel-all-docs","","https://aioseo.com/docs/"),rel:"noopener noreferrer",target:"_blank"},[d(D),i("h3",null,a(s.strings.viewDocumentation),1),i("p",null,a(s.strings.browseDocumentation),1),d(T,{class:"aioseo-help-docs-viewall gray small"},{default:_(()=>[b(a(s.strings.viewAllDocumentation),1)]),_:1})],8,oe)]),i("div",ne,[i("a",{href:!t.$isPro||!c.licenseStore.license.isActive?t.$links.getUpsellUrl("help-panel","get-support","liteUpgrade"):t.$links.utmUrl("help-panel-support","","https://aioseo.com/account/support/"),rel:"noopener noreferrer",target:"_blank"},[d(K),i("h3",null,a(s.strings.getSupport),1),i("p",null,a(s.strings.submitTicket),1),t.$isPro&&c.licenseStore.license.isActive?(n(),g(T,{key:0,class:"aioseo-help-docs-support blue small"},{default:_(()=>[b(a(s.strings.submitSupportTicket),1)]),_:1})):f("",!0),!t.$isPro||!c.licenseStore.license.isActive?(n(),g(T,{key:1,class:"aioseo-help-docs-support green small"},{default:_(()=>[b(a(s.strings.upgradeToPro),1)]),_:1})):f("",!0)],8,re)])])])])}const ce=k(Bt,[["render",ae]]),le={computed:{notificationsCount(){const t=w();return this.dismissed?t.dismissedNotificationsCount:t.activeNotificationsCount},notifications(){const t=w();return this.dismissed?t.dismissedNotifications:t.activeNotifications},notificationTitle(){return this.dismissed?this.strings.notifications:this.strings.newNotifications}}},de=""+window.__aioseoDynamicImportPreload__("images/dannie-detective.C0gjJQEP.png"),ue={setup(){return{notificationsStore:w()}},emits:["dismissed-notification"],components:{BaseButton:st,SvgCircleCheck:q,SvgCircleClose:F,SvgCircleExclamation:pt,SvgGear:gt,TransitionSlide:I},mixins:[mt,_t],props:{notification:{type:Object,required:!0}},data(){return{active:!0,strings:{dismiss:this.$t.__("Dismiss",this.$td)}}},computed:{getIcon(){switch(this.notification.type){case"warning":return"svg-circle-exclamation";case"error":return"svg-circle-close";case"info":return"svg-gear";case"success":default:return"svg-circle-check"}},getDate(){return this.dateSqlToLocalRelative(this.notification.start)}},methods:{processDismissNotification(){this.active=!1,this.notificationsStore.dismissNotifications([this.notification.slug]),this.$emit("dismissed-notification")}}},fe={class:"icon"},he={class:"body"},me={class:"title"},_e={class:"date"},pe=["innerHTML"],ge={class:"actions"};function ve(t,e,r,c,s,o){const m=u("base-button"),p=u("transition-slide");return n(),g(p,{class:"aioseo-notification",active:s.active},{default:_(()=>[i("div",null,[i("div",fe,[(n(),g(x(o.getIcon),{class:A(r.notification.type)},null,8,["class"]))]),i("div",he,[i("div",me,[i("div",null,a(r.notification.title),1),i("div",_e,a(o.getDate),1)]),i("div",{class:"notification-content",innerHTML:r.notification.content},null,8,pe),i("div",ge,[r.notification.button1_label&&r.notification.button1_action?(n(),g(m,{key:0,size:"small",type:"gray",tag:t.getTagType(r.notification.button1_action),href:t.getHref(r.notification.button1_action),target:t.getTarget(r.notification.button1_action),onClick:e[0]||(e[0]=h=>t.processButtonClick(r.notification.button1_action,1)),loading:t.button1Loading},{default:_(()=>[b(a(r.notification.button1_label),1)]),_:1},8,["tag","href","target","loading"])):f("",!0),r.notification.button2_label&&r.notification.button2_action?(n(),g(m,{key:1,size:"small",type:"gray",tag:t.getTagType(r.notification.button2_action),href:t.getHref(r.notification.button2_action),target:t.getTarget(r.notification.button2_action),onClick:e[1]||(e[1]=h=>t.processButtonClick(r.notification.button2_action,2)),loading:t.button2Loading},{default:_(()=>[b(a(r.notification.button2_label),1)]),_:1},8,["tag","href","target","loading"])):f("",!0),r.notification.dismissed?f("",!0):(n(),l("a",{key:2,href:"#",class:"dismiss",onClick:e[2]||(e[2]=v((...h)=>o.processDismissNotification&&o.processDismissNotification(...h),["stop","prevent"]))},a(s.strings.dismiss),1))])])])]),_:1},8,["active"])}const ye=k(ue,[["render",ve]]),be={setup(){return{licenseStore:V(),notificationsStore:w(),optionsStore:j(),rootStore:z()}},emits:["dismissed-notification"],components:{SvgCircleCheck:q,TransitionSlide:I},props:{notification:{type:Object,required:!0}},data(){return{step:1,active:!0,strings:{dismiss:this.$t.__("Dismiss",this.$td),yesILoveIt:this.$t.__("Yes, I love it!",this.$td),notReally:this.$t.__("Not Really...",this.$td),okYouDeserveIt:this.$t.__("Ok, you deserve it",this.$td),nopeMaybeLater:this.$t.__("Nope, maybe later",this.$td),giveFeedback:this.$t.__("Give feedback",this.$td),noThanks:this.$t.__("No thanks",this.$td)}}},computed:{title(){switch(this.step){case 2:return this.$t.__("That's Awesome!",this.$td);case 3:return this.$t.__("Help us improve",this.$td);default:return this.$t.sprintf(this.$t.__("Are you enjoying %1$s?",this.$td),"AIOSEO")}},content(){switch(this.step){case 2:return this.$t.__("Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?",this.$td);case 3:return this.$t.sprintf(this.$t.__("We're sorry to hear you aren't enjoying %1$s. We would love a chance to improve. Could you take a minute and let us know what we can do better?",this.$td),"All in One SEO");default:return""}},feedbackUrl(){const t=this.optionsStore.options.general&&this.licenseStore.licenseKey?this.licenseStore.licenseKey:"",e=this.$isPro?"pro":"lite";return this.$links.utmUrl("notification-review-notice",this.rootStore.aioseo.version,"https://aioseo.com/plugin-feedback/?wpf7528_24="+encodeURIComponent(this.rootStore.aioseo.urls.home)+"&wpf7528_26="+t+"&wpf7528_27="+e+"&wpf7528_28="+this.rootStore.aioseo.version)}},methods:{processDismissNotification(t=!1){this.active=!1,this.notificationsStore.dismissNotifications([this.notification.slug+(t?"-delay":"")]),this.$emit("dismissed-notification")}}},ke={class:"icon"},Se={class:"body"},$e={class:"title"},Ne=["innerHTML"],Ce={class:"actions"};function we(t,e,r,c,s,o){const m=u("svg-circle-check"),p=u("base-button"),h=u("transition-slide");return n(),g(h,{class:"aioseo-notification",active:s.active},{default:_(()=>[i("div",null,[i("div",ke,[d(m,{class:"success"})]),i("div",Se,[i("div",$e,[i("div",null,a(o.title),1)]),i("div",{class:"notification-content",innerHTML:o.content},null,8,Ne),i("div",Ce,[s.step===1?(n(),l(N,{key:0},[d(p,{size:"small",type:"blue",onClick:e[0]||(e[0]=v(y=>s.step=2,["stop"]))},{default:_(()=>[b(a(s.strings.yesILoveIt),1)]),_:1}),d(p,{size:"small",type:"gray",onClick:e[1]||(e[1]=v(y=>s.step=3,["stop"]))},{default:_(()=>[b(a(s.strings.notReally),1)]),_:1})],64)):f("",!0),s.step===2?(n(),l(N,{key:1},[d(p,{tag:"a",href:"https://wordpress.org/support/plugin/all-in-one-seo-pack/reviews/?filter=5#new-post",size:"small",type:"blue",target:"_blank",rel:"noopener noreferrer",onClick:e[2]||(e[2]=y=>o.processDismissNotification(!1))},{default:_(()=>[b(a(s.strings.okYouDeserveIt),1)]),_:1}),d(p,{size:"small",type:"gray",onClick:e[3]||(e[3]=v(y=>o.processDismissNotification(!0),["stop","prevent"]))},{default:_(()=>[b(a(s.strings.nopeMaybeLater),1)]),_:1})],64)):f("",!0),s.step===3?(n(),l(N,{key:2},[d(p,{tag:"a",href:o.feedbackUrl,size:"small",type:"blue",target:"_blank",rel:"noopener noreferrer",onClick:e[4]||(e[4]=y=>o.processDismissNotification(!1))},{default:_(()=>[b(a(s.strings.giveFeedback),1)]),_:1},8,["href"]),d(p,{size:"small",type:"gray",onClick:e[5]||(e[5]=v(y=>o.processDismissNotification(!1),["stop","prevent"]))},{default:_(()=>[b(a(s.strings.noThanks),1)]),_:1})],64)):f("",!0),r.notification.dismissed?f("",!0):(n(),l("a",{key:3,class:"dismiss",href:"#",onClick:e[6]||(e[6]=v(y=>o.processDismissNotification(!1),["stop","prevent"]))},a(s.strings.dismiss),1))])])])]),_:1},8,["active"])}const De=k(be,[["render",we]]),Le={setup(){return{notificationsStore:w()}},emits:["dismissed-notification"],components:{SvgCircleCheck:q,TransitionSlide:I},props:{notification:{type:Object,required:!0}},data(){return{active:!0,strings:{dismiss:this.$t.__("Dismiss",this.$td),yesILoveIt:this.$t.__("Yes, I love it!",this.$td),notReally:this.$t.__("Not Really...",this.$td),okYouDeserveIt:this.$t.__("Ok, you deserve it",this.$td),nopeMaybeLater:this.$t.__("Nope, maybe later",this.$td),giveFeedback:this.$t.__("Give feedback",this.$td),noThanks:this.$t.__("No thanks",this.$td)}}},computed:{title(){return this.$t.sprintf(this.$t.__("Are you enjoying %1$s?",this.$td),"AIOSEO")},content(){return this.$t.sprintf(this.$t.__("Hey, we noticed you have been using %1$s for some time - that’s awesome! Could you please do us a BIG favor and give it a 5-star rating on WordPress to help us spread the word and boost our motivation?",this.$td),"<strong>All in One SEO</strong>")}},methods:{processDismissNotification(t=!1){this.active=!1,this.notificationsStore.dismissNotifications([this.notification.slug+(t?"-delay":"")]),this.$emit("dismissed-notification")}}},Pe={class:"icon"},Te={class:"body"},Ae={class:"title"},Ie=["innerHTML"],Be={class:"actions"};function Me(t,e,r,c,s,o){const m=u("svg-circle-check"),p=u("base-button"),h=u("transition-slide");return n(),g(h,{class:"aioseo-notification",active:s.active},{default:_(()=>[i("div",null,[i("div",Pe,[d(m,{class:"success"})]),i("div",Te,[i("div",Ae,[i("div",null,a(o.title),1)]),i("div",{class:"notification-content",innerHTML:o.content},null,8,Ie),i("div",Be,[d(p,{tag:"a",href:"https://wordpress.org/support/plugin/all-in-one-seo-pack/reviews/?filter=5#new-post",size:"small",type:"blue",target:"_blank",rel:"noopener noreferrer",onClick:e[0]||(e[0]=y=>o.processDismissNotification(!1))},{default:_(()=>[b(a(s.strings.okYouDeserveIt),1)]),_:1}),d(p,{size:"small",type:"gray",onClick:e[1]||(e[1]=v(y=>o.processDismissNotification(!0),["stop","prevent"]))},{default:_(()=>[b(a(s.strings.nopeMaybeLater),1)]),_:1}),r.notification.dismissed?f("",!0):(n(),l("a",{key:0,class:"dismiss",href:"#",onClick:e[2]||(e[2]=v(y=>o.processDismissNotification(!1),["stop","prevent"]))},a(s.strings.dismiss),1))])])])]),_:1},8,["active"])}const He=k(Le,[["render",Me]]),Ue={components:{SvgCircleClose:F,TransitionSlide:I},props:{notification:{type:Object,required:!0}},data(){return{active:!0,strings:{title:this.$t.sprintf(this.$t.__("%1$s Addons Not Configured Properly",this.$td),"AIOSEO"),learnMore:this.$t.__("Learn More",this.$td),upgrade:this.$t.__("Upgrade",this.$td)}}},computed:{content(){let t="<ul>";return this.notification.addons.forEach(e=>{t+="<li><strong>AIOSEO - "+e.name+"</strong></li>"}),t+="</ul>",this.notification.message+t}}},Ee={class:"icon"},Oe={class:"body"},ze={class:"title"},qe=["innerHTML"],Ve={class:"actions"};function Re(t,e,r,c,s,o){const m=u("svg-circle-close"),p=u("base-button"),h=u("transition-slide");return n(),g(h,{class:"aioseo-notification",active:s.active},{default:_(()=>[i("div",null,[i("div",Ee,[d(m,{class:"error"})]),i("div",Oe,[i("div",ze,[i("div",null,a(s.strings.title),1)]),i("div",{class:"notification-content",innerHTML:o.content},null,8,qe),i("div",Ve,[d(p,{size:"small",type:"green",tag:"a",href:t.$links.utmUrl("notification-unlicensed-addons"),target:"_blank"},{default:_(()=>[b(a(s.strings.upgrade),1)]),_:1},8,["href"])])])])]),_:1},8,["active"])}const je=k(Ue,[["render",Re]]),Ge={emits:["toggle-dismissed","dismissed-notification"],components:{CoreNotification:ye,NotificationsReview:De,NotificationsReview2:He,NotificationsUnlicensedAddons:je},props:{dismissedCount:{type:Number,required:!0},notifications:{type:Array,required:!0}},data(){return{dannieDetectiveImg:de,strings:{greatScott:this.$t.__("Great Scott! Where'd they all go?",this.$td),noNewNotifications:this.$t.__("You have no new notifications.",this.$td),seeDismissed:this.$t.__("See Dismissed Notifications",this.$td)}}},methods:{getAssetUrl:ht}},Fe={class:"aioseo-notification-cards"},xe={key:"no-notifications"},Ye={class:"no-notifications"},Ke=["src"],We={class:"great-scott"},Xe={class:"no-new-notifications"};function Je(t,e,r,c,s,o){return n(),l("div",Fe,[r.notifications.length?(n(!0),l(N,{key:0},L(r.notifications,m=>(n(),g(x(m.component?m.component:"core-notification"),{key:m.slug,notification:m,ref_for:!0,ref:"notification",onDismissedNotification:e[0]||(e[0]=p=>t.$emit("dismissed-notification"))},null,40,["notification"]))),128)):f("",!0),r.notifications.length?f("",!0):(n(),l("div",xe,[E(t.$slots,"no-notifications",{},()=>[i("div",Ye,[i("img",{alt:"Dannie the Detective",src:o.getAssetUrl(s.dannieDetectiveImg)},null,8,Ke),i("div",We,a(s.strings.greatScott),1),i("div",Xe,a(s.strings.noNewNotifications),1),r.dismissedCount?(n(),l("a",{key:0,href:"#",class:"dismiss",onClick:e[1]||(e[1]=v(m=>t.$emit("toggle-dismissed"),["stop","prevent"]))},a(s.strings.seeDismissed),1)):f("",!0)])])]))])}const Qe=k(Ge,[["render",Je]]),Ze={setup(){const t=w(),{strings:e}=vt();return{notificationsStore:t,composableStrings:e}},components:{CoreNotificationCards:Qe,SvgClose:G},mixins:[le],data(){return{dismissed:!1,maxNotifications:Number.MAX_SAFE_INTEGER,currentPage:0,totalPages:1,strings:X(this.composableStrings,{dismissedNotifications:this.$t.__("Dismissed Notifications",this.$td),dismissAll:this.$t.__("Dismiss All",this.$td)})}},watch:{"notificationsStore.showNotifications"(t){t?(this.currentPage=0,this.setMaxNotifications(),this.addBodyClass()):this.removeBodyClass()},dismissed(){this.setMaxNotifications()},notifications(){this.setMaxNotifications()}},computed:{filteredNotifications(){return[...this.notifications].splice(this.currentPage===0?0:this.currentPage*this.maxNotifications,this.maxNotifications)},pages(){const t=[];for(let e=0;e<this.totalPages;e++)t.push({number:e+1});return t}},methods:{escapeListener(t){t.key==="Escape"&&this.notificationsStore.showNotifications&&this.notificationsStore.toggleNotifications()},addBodyClass(){document.body.classList.add("aioseo-show-notifications")},removeBodyClass(){document.body.classList.remove("aioseo-show-notifications")},documentClick(t){if(!this.notificationsStore.showNotifications)return;const e=t&&t.target?t.target:null,r=document.querySelector("#wp-admin-bar-aioseo-notifications");if(r&&(r===e||r.contains(e)))return;const c=document.querySelector("#toplevel_page_aioseo .wp-first-item"),s=document.querySelector("#toplevel_page_aioseo .wp-first-item .aioseo-menu-notification-indicator");if(c&&c.contains(s)&&(c===e||c.contains(e)))return;const o=this.$refs["aioseo-notifications"];o&&(o===e||o.contains(e))||this.notificationsStore.toggleNotifications()},notificationsLinkClick(t){t.preventDefault(),this.notificationsStore.toggleNotifications()},processDismissAllNotifications(){const t=[];this.notifications.forEach(e=>{t.push(e.slug)}),this.notificationsStore.dismissNotifications(t).then(()=>{this.setMaxNotifications()})},setMaxNotifications(){const t=this.currentPage;this.currentPage=0,this.totalPages=1,this.maxNotifications=Number.MAX_SAFE_INTEGER,this.$nextTick(async()=>{const e=[],r=document.querySelectorAll(".notification-menu .aioseo-notification");r&&r.forEach(s=>{let o=s.offsetHeight;const m=window.getComputedStyle?getComputedStyle(s,null):s.currentStyle,p=parseInt(m.marginTop)||0,h=parseInt(m.marginBottom)||0;o+=p+h,e.push(o)});const c=document.querySelector(".notification-menu .aioseo-notification-cards");if(c){let s=0,o=0;for(let m=0;m<e.length&&(o+=e[m],!(o>c.offsetHeight));m++)s++;this.maxNotifications=s||1,this.totalPages=Math.ceil(e.length/s)}this.currentPage=t>this.totalPages-1?this.totalPages-1:t})}},mounted(){document.addEventListener("keydown",this.escapeListener),document.addEventListener("mousedown",this.documentClick);const t=document.querySelector("#wp-admin-bar-aioseo-notifications .ab-item");t&&t.addEventListener("mousedown",this.notificationsLinkClick);const e=document.querySelector("#toplevel_page_aioseo .wp-first-item"),r=document.querySelector("#toplevel_page_aioseo .wp-first-item .aioseo-menu-notification-indicator");e&&r&&e.addEventListener("mousedown",this.notificationsLinkClick)}},ti={class:"aioseo-notifications",ref:"aioseo-notifications"},ei={key:0,class:"notification-menu"},ii={class:"notification-header"},si={class:"new-notifications"},oi={class:"dismissed-notifications"},ni={class:"notification-footer"},ri={class:"pagination"},ai=["onClick"],ci={key:0,class:"dismiss-all"};function li(t,e,r,c,s,o){const m=u("svg-close"),p=u("core-notification-cards");return n(),l("div",ti,[d(O,{name:"notifications-slide"},{default:_(()=>[c.notificationsStore.showNotifications?(n(),l("div",ei,[i("div",ii,[i("span",si,"("+a(t.notificationsCount)+") "+a(t.notificationTitle),1),i("div",oi,[!s.dismissed&&c.notificationsStore.dismissedNotificationsCount?(n(),l("a",{key:0,href:"#",onClick:e[0]||(e[0]=v(h=>s.dismissed=!0,["stop","prevent"]))},a(s.strings.dismissedNotifications),1)):f("",!0),s.dismissed&&c.notificationsStore.dismissedNotificationsCount?(n(),l("a",{key:1,href:"#",onClick:e[1]||(e[1]=v(h=>s.dismissed=!1,["stop","prevent"]))},a(s.strings.activeNotifications),1)):f("",!0)]),i("div",{onClick:e[2]||(e[2]=v((...h)=>c.notificationsStore.toggleNotifications&&c.notificationsStore.toggleNotifications(...h),["stop"]))},[d(m)])]),d(p,{class:"notification-cards",notifications:o.filteredNotifications,dismissedCount:c.notificationsStore.dismissedNotificationsCount,onToggleDismissed:e[3]||(e[3]=h=>s.dismissed=!s.dismissed)},null,8,["notifications","dismissedCount"]),i("div",ni,[i("div",ri,[s.totalPages>1?(n(!0),l(N,{key:0},L(o.pages,(h,y)=>(n(),l("div",{class:A(["page-number",{active:h.number===1+s.currentPage}]),key:y,onClick:v(P=>s.currentPage=h.number-1,["stop"])},a(h.number),11,ai))),128)):f("",!0)]),s.dismissed?f("",!0):(n(),l("div",ci,[t.notifications.length?(n(),l("a",{key:0,href:"#",class:"dismiss",onClick:e[4]||(e[4]=v((...h)=>o.processDismissAllNotifications&&o.processDismissAllNotifications(...h),["stop","prevent"]))},a(s.strings.dismissAll),1)):f("",!0)]))])])):f("",!0)]),_:1}),d(O,{name:"notifications-fade"},{default:_(()=>[c.notificationsStore.showNotifications?(n(),l("div",{key:0,onClick:e[5]||(e[5]=(...h)=>c.notificationsStore.toggleNotifications&&c.notificationsStore.toggleNotifications(...h)),class:"overlay"})):f("",!0)]),_:1})],512)}const di=k(Ze,[["render",li]]),ui={setup(){return{helpPanelStore:R(),notificationsStore:w(),rootStore:z(),optionsStore:j()}},components:{CoreAlert:ot,CoreHeader:rt,CoreHelp:ce,CoreMainTabs:ft,CoreNotifications:di,GridContainer:at},mixins:[et],props:{pageName:{type:String,required:!0},showTabs:{type:Boolean,default(){return!0}},showSaveButton:{type:Boolean,default(){return!0}},excludeTabs:{type:Array,default(){return[]}},containerClasses:{type:Array,default(){return[]}}},data(){return{tabsKey:0,strings:{saveChanges:this.$t.__("Save Changes",this.$td)}}},watch:{excludeTabs(){this.tabsKey+=1}},computed:{tabs(){return this.$router.options.routes.filter(t=>t.name&&t.meta&&t.meta.name).filter(t=>Q(t.meta.access)).filter(t=>!t.meta.license||J.hasMinimumLevel(t.meta.license)).filter(t=>!(t.meta.display==="lite"&&this.$isPro||t.meta.display==="pro"&&!this.$isPro)).filter(t=>!this.excludeTabs.includes(t.name)).map(t=>({slug:t.name,name:t.meta.name,url:{name:t.name},access:t.meta.access,pro:!!t.meta.pro}))},shouldShowSaveButton(){if(this.$route&&this.$route.name){const t=this.$router.options.routes.find(e=>e.name===this.$route.name);if(t&&t.meta&&t.meta.hideSaveButton)return!1}return this.showSaveButton},errorSaving(){const t=this.$isPro?"https://aioseo.com/plugin/pro-support":"https://aioseo.com/plugin/lite-support";return this.$t.sprintf(this.$t.__("Oops! It looks like an error occurred while saving the changes. Please try again or %1$scontact our support team%2$s.",this.$td),'<a href="'+this.$links.utmUrl("error-saving",this.rootStore.aioseo.page,t)+'" target="_blank">',"</a>")}},mounted(){Z().notifications&&(this.notificationsStore.showNotifications||this.notificationsStore.toggleNotifications(),setTimeout(()=>{tt("notifications")},500)),this.notificationsStore.force&&this.notificationsStore.active.length&&(this.notificationsStore.force=!1,this.notificationsStore.toggleNotifications())}},fi={class:"aioseo-main"},hi=["innerHTML"],mi={key:2,class:"save-changes"};function _i(t,e,r,c,s,o){const m=u("core-notifications"),p=u("core-header"),h=u("core-alert"),y=u("core-main-tabs"),P=u("base-button"),B=u("grid-container"),D=u("core-help");return n(),l("div",null,[d(m),i("div",fi,[d(p,{"page-name":r.pageName},null,8,["page-name"]),d(B,{class:A(r.containerClasses)},{default:_(()=>[c.optionsStore.saveError?(n(),g(h,{key:0,type:"red"},{default:_(()=>[i("div",{innerHTML:o.errorSaving},null,8,hi)]),_:1})):f("",!0),r.showTabs?(n(),g(y,{key:s.tabsKey,tabs:o.tabs,showSaveButton:o.shouldShowSaveButton},{extra:_(()=>[E(t.$slots,"extra")]),_:3},8,["tabs","showSaveButton"])):f("",!0),d(O,{name:"route-fade",mode:"out-in"},{default:_(()=>[E(t.$slots,"default")]),_:3}),o.shouldShowSaveButton?(n(),l("div",mi,[d(P,{type:"blue",size:"medium",loading:c.rootStore.loading,onClick:t.processSaveChanges},{default:_(()=>[b(a(s.strings.saveChanges),1)]),_:1},8,["loading","onClick"])])):f("",!0)]),_:3},8,["class"])]),c.helpPanelStore.docs&&Object.keys(c.helpPanelStore.docs).length?(n(),g(D,{key:0})):f("",!0)])}const Ei=k(ui,[["render",_i]]);export{Ei as C,le as N,Qe as a,vt as u};
