/* 
 * Options file
 */

"use strict";

var options = {
    controlsSection: ['getUpSection', 'getDownSection', 'getBgSection', 'getMainSettingsSection', 'getCopy', 'getDel']
    , colorGrid: [
        { name: 'bg-1', title: 'Background 1', styleProperty: 'background-color:'
            , domIdentif: '.bg-1', darkColor: '#353B4A', lightColor: '#ffffff', domIdentifPreloader: ' #preloader'}
        , { name: 'bg-2', title: 'Background 2', styleProperty: 'background-color:'
            , domIdentif: '.bg-2', darkColor: '#222222', lightColor: '#f8f9fb'}
        , { name: 'bg-3', title: 'Background 3', styleProperty: 'background-color:'
            , domIdentif: '.bg-3', darkColor: '#2b356f', lightColor: '#e9fbff'}
        , { name: 'font-color', title: 'Font color', styleProperty: 'color:'
            , domIdentif: '', darkColor: '#ffffff', lightColor: '#444444'}
        , { name: 'highliht-text', title: 'Highlight text', styleProperty: 'color:'
            , domIdentif: 'mark', darkColor: '#000000', lightColor: '#000000'}
        , { name: 'highliht-background', title: 'Highlight background', styleProperty: 'background-color:'
            , domIdentif: 'mark', darkColor: '#ffff60', lightColor: '#ffff60'}
        , { name: 'h1', title: 'H1 color', styleProperty: 'color:'
            , domIdentif: 'h1', darkColor: '#ffffff', lightColor: '#504ABC' }
        , { name: 'h2', title: 'H2 color', styleProperty: 'color:'
            , domIdentif: 'h2', darkColor: '#ffffff', lightColor: '#504ABC' }
        , { name: 'h3', title: 'H3 color', styleProperty: 'color:'
            , domIdentif: 'h3', darkColor: '#ffffff', lightColor: '#504ABC' }
        , { name: 'h4', title: 'H4 color', styleProperty: 'color:'
            , domIdentif: 'h4', darkColor: '#ffffff', lightColor: '#555555' }
        , { name: 'separator', title: 'Separator', styleProperty: 'border-color:'
            , domIdentif: ['.sep-b:after', '.sep-full-b:after', ' hr', ' .border-box'], darkColor: '#777777', lightColor: '#eeeeee' }
        , { name: 'link-color', title: 'Link', styleProperty: 'color:'
            , domIdentif: ['a:not(.btn):not(.gallery-box):not(.goodshare)', 'a.btn-link'], darkColor: '#ffffff', lightColor: '#222'}
        , { name: 'link-hover-color', title: 'Link hover', styleProperty: 'color:'
            , domIdentif: 'a:not(.btn):not(.gallery-box):not(.goodshare):hover', darkColor: '#00a7ff', lightColor: '#00a7ff'}
        , { name: 'btn-primary-color', title: 'Primary button', styleProperty: 'background-color:'
            , domIdentif: '.btn-primary', darkColor: '#07bcf7', lightColor: '#07bcf7'}
        , { name: 'btn-primary-hover-color', title: 'Primary button hover', styleProperty: 'background-color:'
            , domIdentif: '.btn-primary:hover', darkColor: '#039dd0', lightColor: '#039dd0'}
        , { name: 'btn-def-color', title: 'Default button', styleProperty: 'color:'
            , domIdentif: '.btn-default', darkColor: '#ffffff', lightColor: '#555555'}
        , { name: 'btn-def-hover-color', title: 'Default button hover', styleProperty: 'color:'
            , domIdentif: '.btn-default:hover', darkColor: '#eeeeee', lightColor: '#222222'}
        , { name: 'icons', title: 'Icons color', styleProperty: 'color:'
            , domIdentif: 'i.icon-color', darkColor: '#FFFFFF', lightColor: '#EF9FFF'}
        , { name: 'preloader-color', title: 'Preloader color', styleProperty: 'border-color:'
            , domIdentif: '#preloader div', darkColor: '#f3f3f3!important', lightColor: '#504ABC!important'}
    ]
    , typographyGrid: [
        { domIdentif: ['/*body*/'], fontSize: '16px', styleprop: {fontStyle: 'inherit', fontWeight: '300', textTransform: 'inherit'}}
        , { domIdentif: 'h1', fontSize: '72px', styleprop: {fontStyle: 'inherit', fontWeight: '300', textTransform: 'inherit'}}
        , { domIdentif: 'h2', fontSize: '36px', styleprop: {fontStyle: 'inherit', fontWeight: '300', textTransform: 'inherit'}}
        , { domIdentif: 'h3', fontSize: '26px', styleprop: {fontStyle: 'inherit', fontWeight: '300', textTransform: 'inherit'}}
        , { domIdentif: 'h4', fontSize: '18px', styleprop: {fontStyle: 'inherit', fontWeight: '300', textTransform: 'inherit'}}
    ]
    , editElementsList: [
        {
            group: 'button'
            , mode: 'edit-elements'
            , btnContlType: 'wrap'
            , domIdentif: ['.btn:not(button)']
            , positionControl: 'outside-top'
            , controlsElement: ['getButtonSettings', 'getStaticLink', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'link'
            , mode: 'edit-elements'
            , btnContlType: 'wrap'
            , domIdentif: ['a.spr-option-link']
            , positionControl: 'outside-top'
            , controlsElement: ['getStaticLink']
        }
        , {
            group: 'form-button'
            , mode: 'edit-elements'
            , btnContlType: 'wrap'
            , domIdentif: ['.contact_form [type=submit]', '.subscribe_form [type=submit]']
            , positionControl: 'outside-top'
            , controlsElement: ['getButtonSettings']
        }
        , {
            group: 'images'
            , mode: 'edit-elements'
            , domIdentif: ['img:not(.spr-option-imgsettings):not(.spr-option-img-nosettings)']
            , positionControl: 'inside-top'
            , controlsElement: ['getImageSettings', 'getLink', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'imagesInLink'
            , mode: 'edit-elements'
            , domIdentif: ['.spr-option-link-img']
            , positionControl: 'inside-top ctrl-top-left'
            , controlsElement: ['getImageSettings', 'getStaticLink', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'content-images'
            , mode: 'edit-elements'
            , domIdentif: ['img.spr-option-imgsettings']
            , positionControl: 'inside-top'
            , controlsElement: ['getImageSettings']
        }
        , {
            group: 'iframe'
            , mode: 'edit-elements'
            , domIdentif: ['.video-iframe']
            , positionControl: 'outside-top'
            , controlsElement: ['getVideoLink', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'contactform'
            , mode: 'edit-elements'
            , editType: '-form'
            , domIdentif: ['form.contact_form']
            , positionControl: 'outside-top'
            , controlsElement: ['getFormPSuccess', 'getFormPError', 'getFormSettings', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'subscribeform'
            , mode: 'edit-elements'
            , editType: '-form'
            , domIdentif: ['form.subscribe_form']
            , positionControl: 'outside-top'
            , controlsElement: ['getFormPSuccess', 'getFormPError', 'getSubscribeFormSettings', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'icons'
            , mode: 'edit-typography'
            , btnContlType: 'wrap'
            , editType: '-icons'
            , domIdentif: ['i']
            , positionControl: 'outside-top'
            , controlsElement: ['getIconsCheck']
        }
        , {
            group: 'text'
            , mode: 'edit-elements'
            , domIdentif: ['p', 'h1', 'h2', 'h3', 'h4:not(.spr-option-no)', '.text-list li', '.text-icon-list li']
            , positionControl: 'outside-top'
            , controlsElement: ['getCopyElement', 'getDelElement']
        }
        , {
            group: 'element-copy-del'
            , mode: 'edit-elements'
            , domIdentif: ['.spr-option-copy-del', '.content-box', '.team-box', '.price-box', '.icons-row i', '.nav > li']
            , positionControl: 'outside-top'
            , controlsElement: ['getCopyElement', 'getDelElement']
        }
        , {
            group: 'element-copy-del-item'
            , mode: 'edit-elements'
            , domIdentif: ['.spr-gallery .item:not(.spr-option-link-img)']
            , positionControl: 'inside-top'
            , controlsElement: ['getCopyElement', 'getDelElement']
        }
        , {
            group: 'element-del'
            , mode: 'edit-elements'
            , domIdentif: ['.share-list li', '.spr-option-del']
            , positionControl: 'outside-top'
            , controlsElement: ['getDelElement']
        }
        , {
            group: 'linkItem'
            , mode: 'edit-elements'
            , domIdentif: ['.social-list li']
            , positionControl: 'outside-top'
            , controlsElement: ['getLink', 'getCopyElement', 'getDelElement']
        }
        , {
            group: 'span'
            , mode: 'edit-typography'
            , editType: '-typography'
            , domIdentif: ['span.spr-option-textedit', '.fullwidth-grid .caption', '.text-list li']
            , positionControl: 'outside-top'
            , controlsElement: ['getTextBold', 'getTextItalic', 'getTextUpper', 'getTextUnderline', 'getTextStrikethrough', 'getTextMarker', 'getTextLink']
        }
        , {
            group: 'btn-span'
            , mode: 'edit-typography'
            , editType: '-typography'
            , domIdentif: ['span.spr-option-textedit-link', '.gallery-box span.caption']
            , positionControl: 'outside-top'
            , controlsElement: ['getTextBold', 'getTextItalic', 'getTextUpper', 'getTextUnderline', 'getTextStrikethrough', 'getTextMarker']
        }
        , {
            group: 'text'
            , mode: 'edit-typography'
            , editType: '-typography'
            , domIdentif: ['p', 'small', 'h1', 'h2', 'h3', 'h4']
            , positionControl: 'outside-top'
            , controlsElement: ['getTextBold', 'getTextItalic', 'getTextUpper', 'getTextUnderline', 'getTextStrikethrough', 'getTextMarker', 'getTextLink', 'getTextAlignLeft', 'getTextAlignCenter', 'getTextAlignRight']
        }
        , {
            group: 'divbackground'
            , mode: 'edit-elements'
            , domIdentif: ['.half-container-left:not(.g-map)', '.half-container-right:not(.g-map)']
            , positionControl: 'outside-top'
            , controlsElement: ['getBgDiv']
        }
        , {
            group: 'modalbackground'
            , mode: 'edit-sections'
            , domIdentif: ['.modal-content']
            , positionControl: 'flex-center popup-bg'
            , controlsElement: ['getBgSection']
        }
        , {
            group: 'map'
            , mode: 'edit-elements'
            , domIdentif: ['.g-map']
            , positionControl: 'outside-top'
            , controlsElement: ['getGMapSettings', 'getCopyElement', 'getDelElement']
        }
    ]
    , popupContent: {
        success: '<div class="modal-dialog">'
        + '<div class="modal-content text-center">'
        + '<div class="modal-header">'
        + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
        + '</div>'
        + '<div class="modal-body">'
        + '<i class="content-icon icon icon-checkmark-circle icon-size-xl icon-color mb-50"></i>'
        + '<h3 class="mb-25 mailchimp-data-message">Your message was sent successfully!</h3>'
        + '<p class="mb-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual.</p>'
        + '<a href="#" class="btn btn-default">Download</a>'
        + '</div>'
        + '<div class="bg bg-type-cover"></div>'
        + '</div>'
        + '</div>'
        , error: '<div class="modal-dialog">'
        + '<div class="modal-content text-center">'
        + '<div class="modal-header">'
        + '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'
        + '</div>'
        + '<div class="modal-body">'
        + '<i class="content-icon icon icon-warning icon-size-xl icon-color mb-50"></i>'
        + '<h3 class="mb-25">Oops! Something went wrong!</h3>'
        + '<p class="mb-50">In our work we try to use only the most modern, convenient and interesting solutions. We want the template you downloaded look unique and new for such a long time as it is possible. Our elements have no excessive gloss, but they are always actual.</p>'
        + '<a href="#" class="btn btn-danger">Ask support</a>'
        + '</div>'
        + '<div class="bg bg-type-cover"></div>'
        + '</div>'
        + '</div>'
        , successStyle: '\n\tbackground-image: url(images/gallery/bg-modal-success.jpg);\n\topacity:0.25;'
        , errorStyle: '\n\tbackground-image: url(images/gallery/bg-modal-error.jpg);\n\topacity:0.1;'
    }
    , baseFilesForProject: {
        css: [
            'bootstrap.css'
            , 'icons.css'
            , 'style.css'
        ]
        , js: [
            'jquery-2.1.4.min.js'
            , 'bootstrap.min.js'
        ]
        , fonts: [
            'iconfont.eot'
            , 'iconfont.svg'
            , 'iconfont.ttf'
            , 'iconfont.woff'
        ]
        , plugins: [
            'https://maps.googleapis.com/maps/api/js?key=AIzaSyCByts0vn5uAYat3aXEeK0yWL7txqfSMX8'
            , 'https://cdn.jsdelivr.net/jquery.goodshare.js/3.2.8/goodshare.min.js'
        ]
    }
};